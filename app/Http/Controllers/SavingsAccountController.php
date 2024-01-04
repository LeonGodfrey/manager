<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Client;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\SavingsAccount;
use App\Models\SavingsProduct;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SavingsAccountController extends Controller
{
    public function index(Client $client, SavingsAccount $account)
    {
        // Retrieve the authenticated user and organization
        $user = Auth::user();
        $organization = Organization::find($user->org_id);
         // Retrieve all branches belonging to the organization
         $branches = Branch::where('org_id', $user->org_id)->get();
         //get client's age
         $dob = Carbon::parse($client->dob);
         $age = $dob->age;

        // Retrieve transactions associated with the cash account
        $transactions = Transaction::whereHas('details', function ($query) use ($account) {
            $query->where('savings_account_id', $account->id);
        })->orderBy('created_at', 'desc')->paginate(50);

        $details = [];
        foreach ($transactions as $transaction) {
            $transactionDetails = TransactionDetail::where('transaction_id', $transaction->id)->get();
            $details[$transaction->id] = $transactionDetails;
        }

        return view('savings-accounts.index', compact('transactions', 'details', 'user', 'organization', 'account', 'client', 'age'));
    }

    public function ledger(Client $client, SavingsAccount $account)
    {
        // Retrieve the authenticated user and organization
        $user = Auth::user();
        $organization = Organization::find($user->org_id);
         // Retrieve all branches belonging to the organization
         $branches = Branch::where('org_id', $user->org_id)->get();
         //get client's age
         $dob = Carbon::parse($client->dob);
         $age = $dob->age;

        // Retrieve transactions associated with the cash account
        $transactions = Transaction::whereHas('details', function ($query) use ($account) {
            $query->where('savings_account_id', $account->id);
        })->orderBy('created_at', 'desc')->paginate(50);

        $details = [];
        foreach ($transactions as $transaction) {
            $transactionDetails = TransactionDetail::where('transaction_id', $transaction->id)->get();
            $details[$transaction->id] = $transactionDetails;
        }

        return view('savings-accounts.ledger', compact('transactions', 'details', 'user', 'organization', 'account', 'client', 'age'));
    }
    
    public function create(Client $client)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        // Retrieve all branches belonging to the organization
        $branches = Branch::where('org_id', $user->org_id)->get();
        $savings_products = SavingsProduct::where('org_id', $user->org_id)->get();
        //get client's age
        $dob = Carbon::parse($client->dob);
        $age = $dob->age;

         // Fetching the Asset account related to the user
         $cash_account = Account::where('user_id', $user->id)
         ->where('type', 'Asset')
         ->first();
        $cash_account_id = $cash_account ? $cash_account->id : null;

        return view('savings-accounts.create', compact('user', 'organization', 'branches', 'savings_products', 'client', 'age', 'cash_account_id'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'org_id' => 'required|exists:organizations,id',
            'branch_id' => 'required|exists:branches,id',
            'client_id' => 'required|exists:clients,id',
            'savings_product_id' => 'required|exists:savings_products,id',
            'amount' => 'required|numeric|min:0',
            'account_name' => 'nullable|max:255',
            'date' => 'required|date',
        ]);

        try {
            // Start a database transaction
            DB::beginTransaction();

            // Create a new Savings account
            $savings_account = new SavingsAccount();
            $savings_account->fill([
                'org_id' =>  $request->org_id,
                'branch_id' =>  $request->branch_id,
                'client_id' =>  $request->client_id,
                'savings_product_id' =>  $request->savings_product_id,
                'account_name' =>  $request->account_name,
                'balance' =>  $request->amount,
                'opened_at' =>  $request->date,
                'last_transacted_at' =>  $request->date,
                'status' =>  'active',
            ]);
            //dd($savings_account);
            $savings_account->save();

            // Create a new Transaction
            $transaction = new Transaction();
            $transaction->fill([
                'org_id' =>  $request->org_id,
                'branch_id' =>  $request->branch_id,
                'client_id' =>  $request->client_id,
                'savings_account_id' => $savings_account->id,
                'user_id' =>  $request->user_id,
                'type' =>  'Deposit',
                'amount' =>  $request->amount,
                'date' =>  $request->date,
            ]);
           // dd($transaction);
            $transaction->save();

            $cash_account = Account::where('user_id', $request->user_id)->first();
            // Create transaction details - debit
            $detail1 = new TransactionDetail();
            $detail1->fill([
                'org_id' => $transaction->org_id,
                'transaction_id' => $transaction->id,
                'account_id' => $cash_account->id,
                'amount' => $transaction->amount,
                'type' => 'Deposit',
                'debit_credit' => 'Debit',
            ]);
            $detail1->save();

            $cash_account->balance += $transaction->amount; // debit an asset account
            $cash_account->save();

            $savings_product_account = Account::where('savings_product_id', $request->savings_product_id)->first();
            // Create transaction details - credit
            $detail2 = new TransactionDetail();
            $detail2->fill([
                'org_id' => $transaction->org_id,
                'transaction_id' => $transaction->id,
                'account_id' => $savings_product_account->id,
                'amount' => $transaction->amount,
                'type' => 'Deposit',
                'debit_credit' => 'Credit',
            ]);
            $detail2->save();

            $savings_product_account->balance += $transaction->amount; // credit a laibility account
            $savings_product_account->save();

            // Commit the database transaction
            DB::commit();

            $client = Client::findOrFail($validatedData['client_id']);
            return redirect()->route('clients.client', ['client' => $client])->with('success', 'Transaction registered successfully.');
        } catch (\Exception $e) {
            // If an exception occurs, rollback the database transaction
            DB::rollback();

            return back()->withInput()->with('error', 'Failed to register the transaction. Please try again.');
        }
    }

    public function deposit(Client $client, SavingsAccount $account)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        // Retrieve all branches belonging to the organization
        $branches = Branch::where('org_id', $user->org_id)->get();
        //get client's age
        $dob = Carbon::parse($client->dob);
        $age = $dob->age;

        // Fetching the Asset account related to the user
        $cash_account = Account::where('user_id', $user->id)
        ->where('type', 'Asset')
        ->first();
       $cash_account_id = $cash_account ? $cash_account->id : null;

        return view('savings-accounts.deposit', compact('user', 'organization', 'branches', 'account', 'client', 'age', 'cash_account_id'));
    }

    public function store_deposit(Request $request)
    {
        $validatedData = $request->validate([
            'org_id' => 'required|exists:organizations,id',
            'branch_id' => 'required|exists:branches,id',
            'client_id' => 'required|exists:clients,id',
            'savings_product_id' => 'required|exists:savings_products,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        try {
            // Start a database transaction
            DB::beginTransaction();

            // Create a new Transaction
            $transaction = new Transaction();
            $transaction->fill([
                'org_id' =>  $request->org_id,
                'branch_id' =>  $request->branch_id,
                'client_id' =>  $request->client_id,
                'savings_account_id' => $request->savings_account_id,
                'user_id' =>  $request->user_id,
                'type' =>  'Deposit',
                'amount' =>  $request->amount,
                'date' =>  $request->date,
            ]);
            $transaction->save();

            $cash_account = Account::where('user_id', $request->user_id)->first();
            // Create transaction details - debit
            $detail1 = new TransactionDetail();
            $detail1->fill([
                'org_id' => $transaction->org_id,
                'transaction_id' => $transaction->id,
                'account_id' => $cash_account->id,
                'amount' => $transaction->amount,
                'type' => 'Deposit',
                'debit_credit' => 'Debit',
            ]);
            $detail1->save();

            $cash_account->balance += $transaction->amount; // debit an asset account
            $cash_account->save();

            $savings_product_account = Account::where('savings_product_id', $request->savings_product_id)->first();
            // Create transaction details - credit
            $detail2 = new TransactionDetail();
            $detail2->fill([
                'org_id' => $transaction->org_id,
                'transaction_id' => $transaction->id,
                'account_id' => $savings_product_account->id,
                'amount' => $transaction->amount,
                'type' => 'Deposit',
                'debit_credit' => 'Credit',
            ]);
            $detail2->save();

            $savings_product_account->balance += $transaction->amount; // credit a laibility account
            $savings_product_account->save();

            //update account balance
            $savings_account = SavingsAccount::findOrFail($request->savings_account_id);
            $savings_account->balance += $transaction->amount;
            $savings_account->save();

            // Commit the database transaction
            DB::commit();

            $client = Client::findOrFail($validatedData['client_id']);
            return redirect()->route('clients.client', ['client' => $client])->with('success', 'Transaction registered successfully.');
        } catch (\Exception $e) {
            // If an exception occurs, rollback the database transaction
            DB::rollback();

            return back()->withInput()->with('error', 'Failed to register the transaction. Please try again.');
        }
    }



    public function reverse_deposit(Request $request)
    {
        //debit detail     
        try {
            // Start a database transaction
            DB::beginTransaction();
            // Find the transaction to reverse
            $transaction = Transaction::findOrFail($request->transaction_id);
           
            // Ensure the transaction is not already reversed
            if ($transaction->is_reversed) {
                return back()->with('error', 'Transaction is already reversed.');
            }

            // Check if the cash account has enough funds
            $cashAccount = Account::where('user_id', $transaction->user_id)->first(); // Fetch a single record
           // dd($cashAccount);
            if ($cashAccount->balance < $transaction->amount) {
                // If the balance is insufficient, rollback the transaction and return an error message
                DB::rollback();
                return back()->withInput()->with('error', 'Insufficient funds in the cash account to cover this transaction!.');
            }

            // Create a new reverse transaction
            $reverseTransaction = new Transaction();
            $reverseTransaction->fill([
                'org_id' => $transaction->org_id,
                'branch_id' => $transaction->branch_id,
                'user_id' => $request->user_id,
                'client_id' => $transaction->client_id,
                'savings_account_id' => $transaction->savings_account_id,
                'receipt_number' => $transaction->receipt_number,
                'type' => $transaction->type,
                'description' => $transaction->description,
                'amount' => $transaction->amount,
                'date' => $request->date,
                'reverses' => $request->transaction_id,
            ]);
           // dd($reverseTransaction);
            $reverseTransaction->save();
           

            // Reverse the transaction and mark it as reversed
            $transaction->reversed_by = $reverseTransaction->id;
            $transaction->is_reversed = true;
            $transaction->reversal_reason = $request->reversal_reason;
            $transaction->save();
            //dd($transaction);

            //credit detail     
            $savingsAccount = SavingsAccount::findOrFail($transaction->savings_account_id);
            $savings_product_account = Account::where('savings_product_id', $savingsAccount->savings_product_id)->first();
            $reverseDetail2 = new TransactionDetail();
            $reverseDetail2->fill([
                'org_id' => $reverseTransaction->org_id,
                'transaction_id' => $reverseTransaction->id,
                'account_id' =>  $savings_product_account->id,
                'amount' => $transaction->amount, // Reverse the amount
                'type' => 'Deposit',
                'debit_credit' => 'Debit',
            ]);
            $reverseDetail2->save();
            
            $savings_product_account->balance -= $transaction->amount; // debit a laibility account
            $savings_product_account->save();


            //debit detail 
            $reverseDetail1 = new TransactionDetail();
            $reverseDetail1->fill([
                'org_id' => $reverseTransaction->org_id,
                'transaction_id' => $reverseTransaction->id,
                'account_id' =>  $cashAccount->id,
                'amount' => $transaction->amount, // Reverse the amount
                'type' => $transaction->type,
                'debit_credit' => 'Credit',
            ]);
            $reverseDetail1->save();

            $cashAccount->balance -= $transaction->amount; // credit an asset account
           // dd($cashAccount);
            $cashAccount->save();


            //update account balance
            $savings_account = SavingsAccount::findOrFail($transaction->savings_account_id);
            $savings_account->balance -= $transaction->amount;
            $savings_account->save();

            // Commit the database transaction
            DB::commit();

            return back()->with('success', 'Transaction reversed successfully.');
        } catch (\Exception $e) {
            // If an exception occurs, rollback the database transaction
            DB::rollback();

            return back()->with('error', 'Failed to reverse the transaction. Please try again.');
        }
    }

    //withdraw
    public function withdraw(Client $client, SavingsAccount $account)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        // Retrieve all branches belonging to the organization
        $branches = Branch::where('org_id', $user->org_id)->get();
        //get client's age
        $dob = Carbon::parse($client->dob);
        $age = $dob->age;

        // Fetching the Asset account related to the user
        $cash_account = Account::where('user_id', $user->id)
        ->where('type', 'Asset')
        ->first();
       $cash_account_id = $cash_account ? $cash_account->id : null;

        return view('savings-accounts.withdraw', compact('user', 'organization', 'branches', 'account', 'client', 'age', 'cash_account_id'));
    }

    public function store_withdraw(Request $request)
    {
        $validatedData = $request->validate([
            'org_id' => 'required|exists:organizations,id',
            'branch_id' => 'required|exists:branches,id',
            'client_id' => 'required|exists:clients,id',
            'savings_product_id' => 'required|exists:savings_products,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        try {
            // Start a database transaction
            DB::beginTransaction();

             // Check if the cash account has enough funds
             $cash_account = Account::where('user_id', $request->user_id)->first();
             // dd($cash_account);
              if ($cash_account->balance < $request->amount) {
                  // If the balance is insufficient, rollback the transaction and return an error message
                  DB::rollback();
                  return back()->withInput()->with('error', 'Insufficient funds in the cash account to cover this transaction!.');
              }

              //check if savings account has enough funds
              $savings_account = SavingsAccount::findOrFail($request->savings_account_id);
              if ($savings_account->balance < $request->amount) {
                // If the balance is insufficient, rollback the transaction and return an error message
                DB::rollback();
                return back()->withInput()->with('error', 'Insufficient funds in the Savings account to cover this transaction!.');
            }

            // Create a new Transaction
            $transaction = new Transaction();
            $transaction->fill([
                'org_id' =>  $request->org_id,
                'branch_id' =>  $request->branch_id,
                'client_id' =>  $request->client_id,
                'savings_account_id' => $request->savings_account_id,
                'user_id' =>  $request->user_id,
                'type' =>  'Withdrawal',
                'amount' =>  $request->amount,
                'date' =>  $request->date,
            ]);
            $transaction->save();

            $savings_product_account = Account::where('savings_product_id', $request->savings_product_id)->first();
            // Create transaction details - debit
            $detail2 = new TransactionDetail();
            $detail2->fill([
                'org_id' => $transaction->org_id,
                'transaction_id' => $transaction->id,
                'account_id' => $savings_product_account->id,
                'amount' => $transaction->amount,
                'type' => 'Withdrawal',
                'debit_credit' => 'Debit',
            ]);
            $detail2->save();

            $savings_product_account->balance -= $transaction->amount; // Debit a laibility account
            $savings_product_account->save();

           
            // Create transaction details - credit
            $detail1 = new TransactionDetail();
            $detail1->fill([
                'org_id' => $transaction->org_id,
                'transaction_id' => $transaction->id,
                'account_id' => $cash_account->id,
                'amount' => $transaction->amount,
                'type' => 'Withdrawal',
                'debit_credit' => 'Credit',
            ]);
            $detail1->save();

            $cash_account->balance -= $transaction->amount; // credit an asset account
            $cash_account->save();

            //update account balance
           
            $savings_account->balance -= $transaction->amount;
            $savings_account->save();

            // Commit the database transaction
            DB::commit();

            $client = Client::findOrFail($validatedData['client_id']);
            return redirect()->route('clients.client', ['client' => $client])->with('success', 'Transaction registered successfully.');
        } catch (\Exception $e) {
            // If an exception occurs, rollback the database transaction
            DB::rollback();

            return back()->withInput()->with('error', 'Failed to register the transaction. Please try again.');
        }
    }



    public function reverse_withdraw(Request $request)
    {
        //debit detail     
        try {
            // Start a database transaction
            DB::beginTransaction();
            // Find the transaction to reverse
            $transaction = Transaction::findOrFail($request->transaction_id);
           
            // Ensure the transaction is not already reversed
            if ($transaction->is_reversed) {
                return back()->with('error', 'Transaction is already reversed.');
            }

            // Check if the cash account has enough funds
            $cashAccount = Account::where('user_id', $transaction->user_id)->first(); // Fetch a single record
           // dd($cashAccount);
            if ($cashAccount->balance < $transaction->amount) {
                // If the balance is insufficient, rollback the transaction and return an error message
                DB::rollback();
                return back()->withInput()->with('error', 'Insufficient funds in the cash account to cover this transaction!.');
            }

            // Create a new reverse transaction
            $reverseTransaction = new Transaction();
            $reverseTransaction->fill([
                'org_id' => $transaction->org_id,
                'branch_id' => $transaction->branch_id,
                'user_id' => $request->user_id,
                'client_id' => $transaction->client_id,
                'savings_account_id' => $transaction->savings_account_id,
                'receipt_number' => $transaction->receipt_number,
                'type' => $transaction->type,
                'description' => $transaction->description,
                'amount' => $transaction->amount,
                'date' => $request->date,
                'reverses' => $request->transaction_id,
            ]);
           // dd($reverseTransaction);
            $reverseTransaction->save();
           

            // Reverse the transaction and mark it as reversed
            $transaction->reversed_by = $reverseTransaction->id;
            $transaction->is_reversed = true;
            $transaction->reversal_reason = $request->reversal_reason;
            $transaction->save();
            //dd($transaction);

            //debit detail 
            $reverseDetail1 = new TransactionDetail();
            $reverseDetail1->fill([
                'org_id' => $reverseTransaction->org_id,
                'transaction_id' => $reverseTransaction->id,
                'account_id' =>  $cashAccount->id,
                'amount' => $transaction->amount, // Reverse the amount
                'type' => 'Withdrawal',
                'debit_credit' => 'Debit',
            ]);
            $reverseDetail1->save();

            $cashAccount->balance += $transaction->amount; // credit an asset account
            $cashAccount->save();

            //credit detail     
            $savingsAccount = SavingsAccount::findOrFail($transaction->savings_account_id);
            $savings_product_account = Account::where('savings_product_id', $savingsAccount->savings_product_id)->first();
            $reverseDetail2 = new TransactionDetail();
            $reverseDetail2->fill([
                'org_id' => $reverseTransaction->org_id,
                'transaction_id' => $reverseTransaction->id,
                'account_id' =>  $savings_product_account->id,
                'amount' => $transaction->amount, // Reverse the amount
                'type' => 'Withdrawal',
                'debit_credit' => 'Credit',
            ]);
            $reverseDetail2->save();
            
            $savings_product_account->balance += $transaction->amount; // credit a laibility account
            $savings_product_account->save();

            //update account balance
            $savings_account = SavingsAccount::findOrFail($transaction->savings_account_id);
            $savings_account->balance += $transaction->amount;
            $savings_account->save();

            // Commit the database transaction
            DB::commit();

            return back()->with('success', 'Transaction reversed successfully.');
        } catch (\Exception $e) {
            // If an exception occurs, rollback the database transaction
            DB::rollback();

            return back()->with('error', 'Failed to reverse the transaction. Please try again.');
        }
    }
    
}
