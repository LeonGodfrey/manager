<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Client;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function other_income_index()
    {
        $user = Auth::user();
        $organization = Organization::find($user->org_id);
        $transactionsQuery = Transaction::where('org_id', $user->org_id)->where('type', 'Other Income');

        if ($user->branch->branch_name !== 'Head Office') {
            $transactionsQuery->where('branch_id', $user->branch_id);
        }

        $transactions = $transactionsQuery->orderBy('created_at', 'desc')->paginate(40);

        $details = [];
        foreach ($transactions as $transaction) {
            $transactionDetails = TransactionDetail::where('transaction_id', $transaction->id)->get();
            $details[$transaction->id] = $transactionDetails;
        }

        return view('transactions.other-income.index', compact('transactions', 'details', 'user', 'organization'));
    }


    public function other_income_create()
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);

        // Fetching all Income accounts for selection
        $accounts = Account::where('org_id', $user->org_id)
            ->where('type', 'Income')
            ->get();

        // Fetching the Asset account related to the user
        $cash_account = Account::where('user_id', $user->id)
            ->where('type', 'Asset')
            ->first(); // Fetch a single record

        // Now, you can retrieve the ID or other attributes of the cash account
        $cash_account_id = $cash_account ? $cash_account->id : null;

        return view('transactions.other-income.create', compact('user', 'organization', 'accounts', 'cash_account_id'));
    }

    public function client_fees_create(Client $client)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);

        // Fetching all Income accounts for selection
        $accounts = Account::where('org_id', $user->org_id)
            ->where('type', 'Income')
            ->get();

        // Fetching the Asset account related to the user
        $cash_account = Account::where('user_id', $user->id)
            ->where('type', 'Asset')
            ->first(); // Fetch a single record

        // Now, you can retrieve the ID or other attributes of the cash account
        $cash_account_id = $cash_account ? $cash_account->id : null;

        return view('clients.client-fees', compact('user', 'organization', 'accounts', 'cash_account_id', 'client'));
    }


    public function other_income_store(Request $request)
    {
        // Validate the input data
        //dd($request);
        $validatedData = $request->validate([
            'org_id' => 'required|exists:organizations,id',
            'branch_id' => 'required|exists:branches,id',
            'user_id' => 'required|exists:users,id',
            'particular' => 'required|max:255',
            'type' => 'required|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'cash_account_id' => 'required|exists:accounts,id',
            'account_id' => 'required|exists:accounts,id',
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
                'user_id' =>  $request->user_id,
                'particular' =>  $request->particular,
                'type' =>  $request->type,
                'description' =>  $request->description,
                'amount' =>  $request->amount,
                'date' =>  $request->date,
            ]);
            $transaction->save();

            // Create transaction details - debit
            $detail1 = new TransactionDetail();
            $detail1->fill([
                'org_id' => $transaction->org_id,
                'transaction_id' => $transaction->id,
                'account_id' => $request->cash_account_id,
                'amount' => $transaction->amount,
                'type' => 'Other Income',
                'debit_credit' => 'Debit',
            ]);
            $detail1->save();

            // Create transaction details - credit
            $detail2 = new TransactionDetail();
            $detail2->fill([
                'org_id' => $transaction->org_id,
                'transaction_id' => $transaction->id,
                'account_id' => $request->account_id,
                'amount' => $transaction->amount,
                'type' => 'Other Income',
                'debit_credit' => 'Credit',
            ]);
            $detail2->save();

            $cashAccount = Account::findOrFail($request->cash_account_id);
            $cashAccount->balance += $transaction->amount;
            $cashAccount->save();

            $incomeAccount = Account::findOrFail($request->account_id);
            $incomeAccount->balance += $transaction->amount;
            $incomeAccount->save();

            // Commit the database transaction
            DB::commit();

            return redirect()->route('transactions.other-income.index')->with('success', 'Other Income Transaction registered successfully.');
        } catch (\Exception $e) {
            // If an exception occurs, rollback the database transaction
            DB::rollback();

            return back()->withInput()->with('error', 'Failed to register the transaction. Please try again.');
        }
    }


    //clients fees
    public function client_fees_store(Request $request)
    {
        // Validate the input data
        //dd($request);
        $validatedData = $request->validate([
            'org_id' => 'required|exists:organizations,id',
            'branch_id' => 'required|exists:branches,id',
            'user_id' => 'required|exists:users,id',
            'client_id' => 'nullable|exists:clients,id',
            'type' => 'required|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'cash_account_id' => 'required|exists:accounts,id',
            'account_id' => 'required|exists:accounts,id',
            'date' => 'required|date',
        ]);

        $client = Client::findOrFail($request->client_id);
        try {
            // Start a database transaction
            DB::beginTransaction();

            // Create a new Transaction
            $transaction = new Transaction();
            $transaction->fill([
                'org_id' =>  $request->org_id,
                'branch_id' =>  $request->branch_id,
                'user_id' =>  $request->user_id,
                'client_id' =>  $request->client_id,
                'particular' =>  $request->particular,
                'type' =>  $request->type,
                'description' =>  $request->description,
                'amount' =>  $request->amount,
                'date' =>  $request->date,
            ]);
            $transaction->save();

            // Create transaction details - debit
            $detail1 = new TransactionDetail();
            $detail1->fill([
                'org_id' => $transaction->org_id,
                'transaction_id' => $transaction->id,
                'account_id' => $request->cash_account_id,
                'amount' => $transaction->amount,
                'type' => 'Other Income',
                'debit_credit' => 'Debit',
            ]);
            $detail1->save();

            // Create transaction details - credit
            $detail2 = new TransactionDetail();
            $detail2->fill([
                'org_id' => $transaction->org_id,
                'transaction_id' => $transaction->id,
                'account_id' => $request->account_id,
                'amount' => $transaction->amount,
                'type' => 'Other Income',
                'debit_credit' => 'Credit',
            ]);
            $detail2->save();

            $cashAccount = Account::findOrFail($request->cash_account_id);
            $cashAccount->balance += $transaction->amount;
            $cashAccount->save();

            $incomeAccount = Account::findOrFail($request->account_id);
            $incomeAccount->balance += $transaction->amount;
            $incomeAccount->save();

            // Commit the database transaction
            DB::commit();

            return redirect()->route('clients.client', ['client' => $client])->with('success', 'Transaction registered successfully.');
        } catch (\Exception $e) {
            // If an exception occurs, rollback the database transaction
            DB::rollback();

            return back()->withInput()->with('error', 'Failed to register the transaction. Please try again.');
        }
    }

    //reverse other income
    public function other_income_reverse(Request $request)
{
    //dd($request);
    //debit detail     
    $debit_account = TransactionDetail::where('transaction_id', $request->transaction_id)->where('debit_credit', 'Debit')->first(); // Fetch a single record
    $debit_account = $debit_account->account_id;
     //credi detail     
     $credit_account = TransactionDetail::where('transaction_id', $request->transaction_id)->where('debit_credit', 'Credit')->first(); // Fetch a single record
     $credit_account = $credit_account->account_id;
        
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
        $cashAccount = Account::findOrFail($debit_account);
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
            'user_id' => $transaction->user_id,
            'client_id' => $transaction->client_id,
            'receipt_number' => $transaction->receipt_number,
            'type' => $transaction->type,
            'description' => $transaction->description,
            'amount' => $transaction->amount,
            'date' => $request->date,
            'reverses' => $request->transaction_id,
        ]);
        $reverseTransaction->save();

        // Reverse the transaction and mark it as reversed
       $transaction->reversed_by = $reverseTransaction->id;
        $transaction->is_reversed = true;
        $transaction->reversal_reason = $request->reversal_reason;
        $transaction->save();

       
        $reverseDetail2 = new TransactionDetail();
        $reverseDetail2->fill([
            'org_id' => $reverseTransaction->org_id,
            'transaction_id' => $reverseTransaction->id,
            'account_id' =>  $credit_account,
            'amount' => $transaction->amount, // Reverse the amount
            'type' => $transaction->type,
            'debit_credit' => 'Debit',
        ]);
        $reverseDetail2->save();

         //details
         $reverseDetail1 = new TransactionDetail();
         $reverseDetail1->fill([
             'org_id' => $reverseTransaction->org_id,
             'transaction_id' => $reverseTransaction->id,
             'account_id' =>  $debit_account,
             'amount' => $transaction->amount, // Reverse the amount
             'type' => $transaction->type,
             'debit_credit' => 'Credit',
         ]);
         $reverseDetail1->save();

        // Update account balances to reverse the transaction
        $cashAccount = Account::findOrFail($debit_account);
        $cashAccount->balance -= $transaction->amount; // Reverse the amount
        $cashAccount->save();

        $incomeAccount = Account::findOrFail( $credit_account);
        $incomeAccount->balance -= $transaction->amount; // Reverse the amount
        $incomeAccount->save();

        // Commit the database transaction
        DB::commit();

        return back()->with('success', 'Transaction reversed successfully.');
    } catch (\Exception $e) {
        // If an exception occurs, rollback the database transaction
        DB::rollback();

        return back()->with('error', 'Failed to reverse the transaction. Please try again.');
    }
}

    //expenses
    public function expense_index()
    {
        $user = Auth::user();
        $organization = Organization::find($user->org_id);
        $transactionsQuery = Transaction::where('org_id', $user->org_id)->where('type', 'Expense');

        if ($user->branch->branch_name !== 'Head Office') {
            $transactionsQuery->where('branch_id', $user->branch_id);
        }

        $transactions = $transactionsQuery->orderBy('created_at', 'desc')->paginate(40);

        $details = [];
        foreach ($transactions as $transaction) {
            $transactionDetails = TransactionDetail::where('transaction_id', $transaction->id)->get();
            $details[$transaction->id] = $transactionDetails;
        }

        return view('transactions.expense.index', compact('transactions', 'details', 'user', 'organization'));
    }


    public function expense_create()
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);

        // Fetching all Expense accounts for selection
        $accounts = Account::where('org_id', $user->org_id)
            ->where('type', 'Expense')
            ->get();

        // Fetching the Asset account related to the user
        $cash_account = Account::where('user_id', $user->id)
            ->where('type', 'Asset')
            ->first(); // Fetch a single record

        // Now, you can retrieve the ID or other attributes of the cash account
        $cash_account_id = $cash_account ? $cash_account->id : null;

        return view('transactions.expense.create', compact('user', 'organization', 'accounts', 'cash_account_id'));
    }


    public function expense_store(Request $request)
    {

        $validatedData = $request->validate([
            'org_id' => 'required|exists:organizations,id',
            'branch_id' => 'required|exists:branches,id',
            'user_id' => 'required|exists:users,id',
            'receipt_number' => 'nullable|max:255',
            'type' => 'required|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'cash_account_id' => 'required|exists:accounts,id',
            'account_id' => 'required|exists:accounts,id',
            'date' => 'required|date',
        ]);

        try {

            // Check if the cash account has enough funds
            $cashAccount = Account::findOrFail($request->cash_account_id);
            if ($cashAccount->balance < $request->amount) {
                // If the balance is insufficient, rollback the transaction and return an error message
                DB::rollback();
                return back()->withInput()->with('error', 'Insufficient funds in the cash account to cover this transaction!.');
            }

            // Start a database transaction
            DB::beginTransaction();

            // Create a new Transaction
            $transaction = new Transaction();
            $transaction->fill([
                'org_id' =>  $request->org_id,
                'branch_id' =>  $request->branch_id,
                'user_id' =>  $request->user_id,
                'receipt_number' =>  $request->receipt_number,
                'type' =>  $request->type,
                'description' =>  $request->description,
                'amount' =>  $request->amount,
                'date' =>  $request->date,
            ]);
            $transaction->save();

            // Create transaction details - debit
            $detail1 = new TransactionDetail();
            $detail1->fill([
                'org_id' => $transaction->org_id,
                'transaction_id' => $transaction->id,
                'account_id' => $request->account_id,
                'amount' => $transaction->amount,
                'type' => 'Expense',
                'debit_credit' => 'Debit',
            ]);
            $detail1->save();

            // Create transaction details - credit
            $detail2 = new TransactionDetail();
            $detail2->fill([
                'org_id' => $transaction->org_id,
                'transaction_id' => $transaction->id,
                'account_id' => $request->cash_account_id,
                'amount' => $transaction->amount,
                'type' => 'Expense',
                'debit_credit' => 'credit',
            ]);
            $detail2->save();
            //debit expense
            $expenseAccount = Account::findOrFail($request->account_id);
            $expenseAccount->balance += $transaction->amount;
            $expenseAccount->save();
            //credit asset
            $cashAccount = Account::findOrFail($request->cash_account_id);
            $cashAccount->balance -= $transaction->amount;
            $cashAccount->save();

            // Commit the database transaction
            DB::commit();

            return redirect()->route('transactions.expense.index')->with('success', 'Expense Transaction registered successfully.');
        } catch (\Exception $e) {
            // If an exception occurs, rollback the database transaction
            DB::rollback();

            return back()->withInput()->with('error', 'Failed to register the transaction. Please try again.');
        }
    }


    //reverse expense
    public function expense_reverse(Request $request)
{
    //dd($request);
    //debit detail     
    $debit_account = TransactionDetail::where('transaction_id', $request->transaction_id)->where('debit_credit', 'Debit')->first(); // Fetch a single record
    $debit_account = $debit_account->account_id;
     //credi detail     
     $credit_account = TransactionDetail::where('transaction_id', $request->transaction_id)->where('debit_credit', 'Credit')->first(); // Fetch a single record
     $credit_account = $credit_account->account_id;
        
    try {
        // Start a database transaction
        DB::beginTransaction();

        // Find the transaction to reverse
        $transaction = Transaction::findOrFail($request->transaction_id);

        // Ensure the transaction is not already reversed
        if ($transaction->is_reversed) {
            return back()->with('error', 'Transaction is already reversed.');
        }             

        // Create a new reverse transaction
        $reverseTransaction = new Transaction();
        $reverseTransaction->fill([
            'org_id' => $transaction->org_id,
            'branch_id' => $transaction->branch_id,
            'user_id' => $transaction->user_id,
            'receipt_number' => $transaction->receipt_number,
            'type' => $transaction->type,
            'description' => $transaction->description,
            'amount' => $transaction->amount,
            'date' => $request->date,
            'reverses' => $request->transaction_id,
        ]);
        $reverseTransaction->save();

        // Reverse the transaction and mark it as reversed
       $transaction->reversed_by = $reverseTransaction->id;
        $transaction->is_reversed = true;
        $transaction->reversal_reason = $request->reversal_reason;
        $transaction->save();

       
        $reverseDetail2 = new TransactionDetail();
        $reverseDetail2->fill([
            'org_id' => $reverseTransaction->org_id,
            'transaction_id' => $reverseTransaction->id,
            'account_id' =>  $credit_account,
            'amount' => $transaction->amount, // Reverse the amount
            'type' => 'Expense',
            'debit_credit' => 'Debit',
        ]);
        $reverseDetail2->save();

         //details
         $reverseDetail1 = new TransactionDetail();
         $reverseDetail1->fill([
             'org_id' => $reverseTransaction->org_id,
             'transaction_id' => $reverseTransaction->id,
             'account_id' =>  $debit_account,
             'amount' => $transaction->amount, // Reverse the amount
             'type' => 'Expense',
             'debit_credit' => 'Credit',
         ]);
         $reverseDetail1->save();

        // Update account balances to reverse the transaction
        $expenseAccount = Account::findOrFail($debit_account);
        $expenseAccount->balance -= $transaction->amount; // Reverse the amount
        $expenseAccount->save();

        $cashAccount = Account::findOrFail( $credit_account);
        $cashAccount->balance += $transaction->amount; // Reverse the amount
        $cashAccount->save();

        // Commit the database transaction
        DB::commit();

        return back()->with('success', 'Transaction reversed successfully.');
    } catch (\Exception $e) {
        // If an exception occurs, rollback the database transaction
        DB::rollback();

        return back()->with('error', 'Failed to reverse the transaction. Please try again.');
    }
}

    //non-cash
    public function non_cash_index()
    {
        $user = Auth::user();
        $organization = Organization::find($user->org_id);
        $transactionsQuery = Transaction::where('org_id', $user->org_id)->where('type', 'Non Cash');

        if ($user->branch->branch_name !== 'Head Office') {
            $transactionsQuery->where('branch_id', $user->branch_id);
        }

        $transactions = $transactionsQuery->orderBy('created_at', 'desc')->paginate(40);

        $details = [];
        foreach ($transactions as $transaction) {
            $transactionDetails = TransactionDetail::where('transaction_id', $transaction->id)->get();
            $details[$transaction->id] = $transactionDetails;
        }

        return view('transactions.non-cash.index', compact('transactions', 'details', 'user', 'organization'));
    }


    public function non_cash_create()
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        // Retrieve all branches belonging to the organization
        $branches = Branch::where('org_id', $user->org_id)->get();
        // Fetching all accounts accounts for selection
        $accounts = Account::where('org_id', $user->org_id)
            ->get();


        return view('transactions.non-cash.create', compact('user', 'organization', 'accounts', 'branches'));
    }


    public function non_cash_store(Request $request)
    {
        $validatedData = $request->validate([
            'org_id' => 'required|exists:organizations,id',
            'branch_id' => 'required|exists:branches,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|max:255',
            'receipt_number' => 'nullable|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'credit_account' => 'required|exists:accounts,id',
            'debit_account' => 'required|exists:accounts,id',
            'date' => 'required|date',
        ]);

        try {

            // Start a database transaction
            DB::beginTransaction();
            // Check if the  account is the same
            if ($request->debit_account == $request->credit_account) {
                DB::rollback();
                return back()->withInput()->with('error', 'You may not Debit and Credit the same Ledger Account!.');
            }

            
            // Create a new Transaction
            $transaction = new Transaction();
            $transaction->fill([
                'org_id' =>  $request->org_id,
                'branch_id' =>  $request->branch_id,
                'user_id' =>  $request->user_id,
                'type' =>  $request->type,
                'description' =>  $request->description,
                'amount' =>  $request->amount,
                'date' =>  $request->date,
            ]);
            $transaction->save();

            // Create transaction details - debit
            $detail1 = new TransactionDetail();
            $detail1->fill([
                'org_id' => $transaction->org_id,
                'transaction_id' => $transaction->id,
                'account_id' => $request->debit_account,
                'amount' => $request->amount,
                'type' => 'Non Cash',
                'debit_credit' => 'Debit',
            ]);
            $detail1->save();

           

            // Create transaction details - credit
            $detail2 = new TransactionDetail();
            $detail2->fill([
                'org_id' => $transaction->org_id,
                'transaction_id' => $transaction->id,
                'account_id' => $request->credit_account,
                'amount' => $transaction->amount,
                'type' => 'Non Cash',
                'debit_credit' => 'credit',
            ]);
            $detail2->save();

            if ($request->debit_type == 'Asset') {
                //debit
                $debitAccount = Account::findOrFail($request->debit_account);
                $debitAccount->balance += $transaction->amount;
                $debitAccount->save();
            }
            if ($request->debit_type == 'Expense') {
                //debit
                $debitAccount = Account::findOrFail($request->debit_account);
                $debitAccount->balance += $transaction->amount;
                $debitAccount->save();
            }
            if ($request->debit_type == 'Equity') {
                //debit
                $debitAccount = Account::findOrFail($request->debit_account);
                $debitAccount->balance -= $transaction->amount;
                $debitAccount->save();
            }
            if ($request->debit_type == 'Income') {
                //debit
                $debitAccount = Account::findOrFail($request->debit_account);
                $debitAccount->balance -= $transaction->amount;
                $debitAccount->save();
            }

            if ($request->debit_type == 'Liability') {
                //debit
                $debitAccount = Account::findOrFail($request->debit_account);
                $debitAccount->balance -= $transaction->amount;
                $debitAccount->save();
            }

            //credit
            if ($request->credit_type == 'Asset') {
                //credit
                $creditAccount = Account::findOrFail($request->credit_account);
                $creditAccount->balance -= $transaction->amount;
                $creditAccount->save();
            }
            if ($request->credit_type == 'Expense') {
                //credit
                $creditAccount = Account::findOrFail($request->credit_account);
                $creditAccount->balance -= $transaction->amount;
                $creditAccount->save();
            }
            if ($request->credit_type == 'Equity') {
                //credit
                $creditAccount = Account::findOrFail($request->credit_account);
                $creditAccount->balance += $transaction->amount;
                $creditAccount->save();
            }
            if ($request->credit_type == 'Income') {
                //credit
                $creditAccount = Account::findOrFail($request->credit_account);
                $creditAccount->balance += $transaction->amount;
                $creditAccount->save();
            }
            if ($request->credit_type == 'Liability') {
                //credit
                $creditAccount = Account::findOrFail($request->credit_account);
                $creditAccount->balance += $transaction->amount;
                $creditAccount->save();
            }


            // Commit the database transaction
            DB::commit();

            return redirect()->route('transactions.non-cash.index')->with('success', 'Non Cash Transaction registered successfully.');
        } catch (\Exception $e) {
            // If an exception occurs, rollback the database transaction
            DB::rollback();

            return back()->withInput()->with('error', 'Failed to register the transaction. Please try again.');
        }
    }

    //reverse non cash
    public function non_cash_reverse(Request $request)
{
    
    //debit detail     
    $detail1 = TransactionDetail::where('transaction_id', $request->transaction_id)->where('debit_credit', 'Debit')->first(); // Fetch a single record
    $debit_account = $detail1->account_id;
    $detail1_type = $detail1->type;
     //credit detail     
     $detail2 = TransactionDetail::where('transaction_id', $request->transaction_id)->where('debit_credit', 'Credit')->first(); // Fetch a single record
     $credit_account = $detail2->account_id;
     $detail2_type = $detail2->type;
        
    try {
        // Start a database transaction
        DB::beginTransaction();

        // Find the transaction to reverse
        $transaction = Transaction::findOrFail($request->transaction_id);

        // Ensure the transaction is not already reversed
        if ($transaction->is_reversed) {
            return back()->with('error', 'Transaction is already reversed.');
        }        

        // Create a new reverse transaction
        $reverseTransaction = new Transaction();
        $reverseTransaction->fill([
            'org_id' => $transaction->org_id,
            'branch_id' => $transaction->branch_id,
            'user_id' => $transaction->user_id,
            'receipt_number' => $transaction->receipt_number,
            'type' => $transaction->type,
            'description' => $transaction->description,
            'amount' => $transaction->amount,
            'date' => $request->date,
            'reverses' => $request->transaction_id,
        ]);
        $reverseTransaction->save();

        // Reverse the transaction and mark it as reversed
       $transaction->reversed_by = $reverseTransaction->id;
        $transaction->is_reversed = true;
        $transaction->reversal_reason = $request->reversal_reason;
        $transaction->save();

       
        $reverseDetail2 = new TransactionDetail();
        $reverseDetail2->fill([
            'org_id' => $reverseTransaction->org_id,
            'transaction_id' => $reverseTransaction->id,
            'account_id' =>  $credit_account,
            'amount' => $transaction->amount, // Reverse the amount
            'type' => $detail2_type,
            'debit_credit' => 'Debit',
        ]);
        $reverseDetail2->save();

         //details
         $reverseDetail1 = new TransactionDetail();
         $reverseDetail1->fill([
             'org_id' => $reverseTransaction->org_id,
             'transaction_id' => $reverseTransaction->id,
             'account_id' =>  $debit_account,
             'amount' => $transaction->amount, // Reverse the amount
             'type' => $detail1_type,
             'debit_credit' => 'Credit',
         ]);
         $reverseDetail1->save();

         // Find the account type
        $debitAccount = Account::findOrFail($credit_account);
        $debit_type = $debitAccount->type; 
        
        
         if ($debit_type == 'Asset') {
            //debit
            $debitAccount = Account::findOrFail($credit_account);           
            $debitAccount->balance += $transaction->amount;
            $debitAccount->save();
           
        }
       
        if ($debit_type == 'Expense') {
            //debit
            $debitAccount = Account::findOrFail($credit_account);
            $debitAccount->balance += $transaction->amount;
            $debitAccount->save();
        }
       
        if ($debit_type == 'Equity') {
            //debit
            $debitAccount = Account::findOrFail($credit_account);
            $debitAccount->balance -= $transaction->amount;
            $debitAccount->save();
        }
        if ($debit_type == 'Income') {
            //debit
            $debitAccount = Account::findOrFail($credit_account);
            $debitAccount->balance -= $transaction->amount;
            $debitAccount->save();
        }

        if ($debit_type == 'Liability') {
            //debit
            $debitAccount = Account::findOrFail($credit_account);
            $debitAccount->balance -= $transaction->amount;
            $debitAccount->save();
        }
       // dd($debit_type);
         // Find the account type
         $creditAccount = Account::findOrFail($debit_account);
         //dd($creditAccount);
         $credit_type = $creditAccount->type; 
         

        //credit
        if ($credit_type == 'Asset') {
            //credit
            $creditAccount = Account::findOrFail($debit_account);
            $creditAccount->balance -= $transaction->amount;
            $creditAccount->save();
        }
        if ($credit_type == 'Expense') {
            //credit
            $creditAccount = Account::findOrFail($debit_account);
            $creditAccount->balance -= $transaction->amount;
            $creditAccount->save();
        }
        if ($credit_type == 'Equity') {
            //credit
            $creditAccount = Account::findOrFail($debit_account);
            $creditAccount->balance += $transaction->amount;
            $creditAccount->save();
        }
        if ($credit_type == 'Income') {
            //credit
            $creditAccount = Account::findOrFail($debit_account);
            $creditAccount->balance += $transaction->amount;
            $creditAccount->save();
        }
        if ($credit_type == 'Liability') {
            //credit
            $creditAccount = Account::findOrFail($request->debit_account);
            $creditAccount->balance += $transaction->amount;
            $creditAccount->save();
        }


        // Commit the database transaction
        DB::commit();

        return back()->with('success', 'Transaction reversed successfully.');
    } catch (\Exception $e) {
        // If an exception occurs, rollback the database transaction
        DB::rollback();

        return back()->with('error', 'Failed to reverse the transaction. Please try again.');
    }
}


      //cash transfer
      public function cash_transfer_index()
      {
          $user = Auth::user();
          $organization = Organization::find($user->org_id);
          $transactionsQuery = Transaction::where('org_id', $user->org_id)->where('type', 'Cash Transfer');
  
          if ($user->branch->branch_name !== 'Head Office') {
              $transactionsQuery->where('branch_id', $user->branch_id);
          }
  
          $transactions = $transactionsQuery->orderBy('created_at', 'desc')->paginate(40);
  
          $details = [];
          foreach ($transactions as $transaction) {
              $transactionDetails = TransactionDetail::where('transaction_id', $transaction->id)->get();
              $details[$transaction->id] = $transactionDetails;
          }
  
          return view('transactions.cash-transfer.index', compact('transactions', 'details', 'user', 'organization'));
      }
  
  
      public function cash_transfer_create()
      {
          // Retrieve the authenticated user
          $user = Auth::user();
          // Get the organization associated with the user
          $organization = Organization::find($user->org_id);
          
        // Retrieve all branches belonging to the organization
        $branches = Branch::where('org_id', $user->org_id)->get();
  
          // Fetching all cash accounts for selection
          $accountSubtypes = ['User Cash Account', 'Vault Account', 'Cash at Bank', 'Mobile Money'];

          $accounts = Account::where('org_id', $user->org_id)
              ->whereIn('subtype', $accountSubtypes)
              ->get();
          
  
          // Fetching the Asset account related to the user
          $cash_account = Account::where('user_id', $user->id)
              ->where('type', 'Asset')
              ->first(); // Fetch a single record
  
          // Now, you can retrieve the ID or other attributes of the cash account
          $cash_account_id = $cash_account ? $cash_account->id : null;
  
          return view('transactions.cash-transfer.create', compact('user', 'organization', 'accounts', 'cash_account_id', 'branches'));
      }
  
  
      public function cash_transfer_store(Request $request)
      {
          
          $validatedData = $request->validate([
            'org_id' => 'required|exists:organizations,id',
            'branch_id' => 'required|exists:branches,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|max:255',
            'subtype' => 'required|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'receipt_number' => 'nullable|max:255',
            'credit_account' => 'required|exists:accounts,id',
            'debit_account' => 'required|exists:accounts,id',
            'date' => 'required|date',          
        ]);
  
          try {
  
            // Check if the  account is the same
            if ($request->debit_account == $request->credit_account) {
                DB::rollback();
                return back()->withInput()->with('error', 'You may not transfer funds from and to the same Cash Account!.');
            }

            // Check if the cash account has enough funds
          $cashAccount = Account::findOrFail($request->credit_account);
          if ($cashAccount->balance < $request->amount) {
              // If the balance is insufficient, rollback the transaction and return an error message
              DB::rollback();
              return back()->withInput()->with('error', 'Insufficient funds in the cash account to cover this transaction!.');
          }
  
              // Start a database transaction
              DB::beginTransaction();
  
              // Create a new Transaction
              $transaction = new Transaction();
              $transaction->fill([
                  'org_id' =>  $request->org_id,
                  'branch_id' =>  $request->branch_id,
                  'user_id' =>  $request->user_id,
                  'receipt_number' =>  $request->receipt_number,
                  'type' =>  $request->type,
                  'subtype' =>  $request->subtype,
                  'description' =>  $request->description,
                  'amount' =>  $request->amount,
                  'date' =>  $request->date,
              ]);
              $transaction->save();
  
              // Create transaction details - debit
              $detail1 = new TransactionDetail();
              $detail1->fill([
                  'org_id' => $transaction->org_id,
                  'transaction_id' => $transaction->id,
                  'account_id' => $request->debit_account,
                  'amount' => $transaction->amount,
                  'type' => $request->type,
                  'debit_credit' => 'Debit',
              ]);
              $detail1->save();
  
              // Create transaction details - credit
              $detail2 = new TransactionDetail();
              $detail2->fill([
                  'org_id' => $transaction->org_id,
                  'transaction_id' => $transaction->id,
                  'account_id' => $request->credit_account,
                  'amount' => $transaction->amount,
                  'type' => $request->type,
                  'debit_credit' => 'credit',
              ]);
              $detail2->save();
              //debit
              $debitAccount = Account::findOrFail($request->debit_account);
              $debitAccount->balance += $transaction->amount;
              $debitAccount->save();
              //credit
              $creditAccount = Account::findOrFail($request->credit_account);
              $creditAccount->balance -= $transaction->amount;
              $creditAccount->save();
  
              // Commit the database transaction
              DB::commit();
  
              return redirect()->route('transactions.cash-transfer.index')->with('success', 'Cash Transfer Transaction registered successfully.');
        } catch (\Exception $e) {
            // If an exception occurs, rollback the database transaction
            DB::rollback();

            return back()->withInput()->with('error', 'Failed to register the transaction. Please try again.');
        }
      }


   
    //reverse expense
    public function cash_transfer_reverse(Request $request)
{
    
    //debit detail     
    $debit_account = TransactionDetail::where('transaction_id', $request->transaction_id)->where('debit_credit', 'Debit')->first(); // Fetch a single record
    $debit_type = $debit_account->type;
    $debit_account = $debit_account->account_id;
   
     //credi detail     
     $credit_account = TransactionDetail::where('transaction_id', $request->transaction_id)->where('debit_credit', 'Credit')->first(); // Fetch a single record
     $credit_type = $credit_account->type;
     $credit_account = $credit_account->account_id;
        
    try {
        

        // Start a database transaction
        DB::beginTransaction();

        // Find the transaction to reverse
        $transaction = Transaction::findOrFail($request->transaction_id);

       
        // Check if the cash account has enough funds
        $cashAccount = Account::findOrFail($debit_account);
        if ($cashAccount->balance < $transaction->amount) {
            // If the balance is insufficient, rollback the transaction and return an error message
            DB::rollback();
            return back()->withInput()->with('error', 'Insufficient funds in the cash account to cover this transaction!.');
        }
        // Ensure the transaction is not already reversed
        if ($transaction->is_reversed) {
            return back()->with('error', 'Transaction is already reversed.');
        }             

        // Create a new reverse transaction
        $reverseTransaction = new Transaction();
        $reverseTransaction->fill([
            'org_id' => $transaction->org_id,
            'branch_id' => $transaction->branch_id,
            'user_id' => $transaction->user_id,
            'receipt_number' => $transaction->receipt_number,
            'type' => $transaction->type,
            'subtype' => $transaction->subtype,
            'description' => $transaction->description,
            'amount' => $transaction->amount,
            'date' => $request->date,
            'reverses' => $request->transaction_id,
        ]);
        $reverseTransaction->save();

        // Reverse the transaction and mark it as reversed
       $transaction->reversed_by = $reverseTransaction->id;
        $transaction->is_reversed = true;
        $transaction->reversal_reason = $request->reversal_reason;
        $transaction->save();

       
        $reverseDetail2 = new TransactionDetail();
        $reverseDetail2->fill([
            'org_id' => $reverseTransaction->org_id,
            'transaction_id' => $reverseTransaction->id,
            'account_id' =>  $credit_account,
            'amount' => $transaction->amount, // Reverse the amount
            'type' => $credit_type,
            'debit_credit' => 'Debit',
        ]);
        $reverseDetail2->save();

         //details
         $reverseDetail1 = new TransactionDetail();
         $reverseDetail1->fill([
             'org_id' => $reverseTransaction->org_id,
             'transaction_id' => $reverseTransaction->id,
             'account_id' =>  $debit_account,
             'amount' => $transaction->amount, // Reverse the amount
             'type' => $debit_type,
             'debit_credit' => 'Credit',
         ]);
         $reverseDetail1->save();

        //credit reverse amount
        $creditAccount = Account::findOrFail($credit_account);
        $creditAccount->balance += $transaction->amount;
        $creditAccount->save();

        //debit
        $debitAccount = Account::findOrFail($debit_account);
        $debitAccount->balance -= $transaction->amount;
        $debitAccount->save();

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
