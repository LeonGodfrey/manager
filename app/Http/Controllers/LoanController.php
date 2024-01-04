<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Loan;
use App\Models\User;
use App\Models\Branch;
use App\Models\Client;
use App\Models\Account;
use App\Models\LoanProduct;
use App\Models\Transaction;
use App\Models\Organization;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LoanController extends Controller
{

    public function index(Request $request)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);

        $search = $request->input('search');
        $searchWords = explode(' ', $search); // Get the search input

        $clientQuery = Client::where('org_id', $user->org_id);

        if ($user->branch->branch_name !== 'Head Office') {
            // User is not in the 'Head Office' branch, so return clients for the specific branch
            $clientQuery->where('branch_id', $user->branch_id);
        }

        if (!empty($search)) {
            // If search input is not empty, apply the search filter for clients
            $clientQuery->where(function ($query) use ($searchWords) {
                foreach ($searchWords as $word) {
                    $query->orWhere('client_number', 'like', "%$word%")
                        ->orWhere('surname', 'like', "%$word%")
                        ->orWhere('given_name', 'like', "%$word%");
                }
            });
        }

        // Retrieve clients filtered by search
        $clients = $clientQuery->orderBy('updated_at', 'desc')->pluck('id');

        $loanQuery = Loan::where('org_id', $user->org_id);

        if ($user->branch->branch_name !== 'Head Office') {
            // User is not in the 'Head Office' branch, so return loans for the specific branch
            $loanQuery->where('branch_id', $user->branch_id);
        }

        // Retrieve loans that match the client IDs and have status pending_appraisal
        $loans = $loanQuery
            ->whereIn('client_id', $clients)
            ->where('status', 'pending_appraisal')
            ->orderBy('updated_at', 'desc')
            ->paginate(40);

        return view('loans.status.pending-appraisal', compact('loans', 'user', 'organization'));
    }

    //pending_approval
    public function appraised(Request $request)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);

        $search = $request->input('search');
        $searchWords = explode(' ', $search); // Get the search input

        $clientQuery = Client::where('org_id', $user->org_id);

        if ($user->branch->branch_name !== 'Head Office') {
            // User is not in the 'Head Office' branch, so return clients for the specific branch
            $clientQuery->where('branch_id', $user->branch_id);
        }

        if (!empty($search)) {
            // If search input is not empty, apply the search filter for clients
            $clientQuery->where(function ($query) use ($searchWords) {
                foreach ($searchWords as $word) {
                    $query->orWhere('client_number', 'like', "%$word%")
                        ->orWhere('surname', 'like', "%$word%")
                        ->orWhere('given_name', 'like', "%$word%");
                }
            });
        }

        // Retrieve clients filtered by search
        $clients = $clientQuery->orderBy('updated_at', 'desc')->pluck('id');

        $loanQuery = Loan::where('org_id', $user->org_id);

        if ($user->branch->branch_name !== 'Head Office') {
            // User is not in the 'Head Office' branch, so return loans for the specific branch
            $loanQuery->where('branch_id', $user->branch_id);
        }

        // Retrieve loans that match the client IDs and have status pending_approval
        $loans = $loanQuery
            ->whereIn('client_id', $clients)
            ->where('status', 'pending_approval')
            ->orderBy('updated_at', 'desc')
            ->paginate(40);

        return view('loans.status.pending-approval', compact('loans', 'user', 'organization'));
    }

    //approved
    public function approved(Request $request)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);

        $search = $request->input('search');
        $searchWords = explode(' ', $search); // Get the search input

        $clientQuery = Client::where('org_id', $user->org_id);

        if ($user->branch->branch_name !== 'Head Office') {
            // User is not in the 'Head Office' branch, so return clients for the specific branch
            $clientQuery->where('branch_id', $user->branch_id);
        }

        if (!empty($search)) {
            // If search input is not empty, apply the search filter for clients
            $clientQuery->where(function ($query) use ($searchWords) {
                foreach ($searchWords as $word) {
                    $query->orWhere('client_number', 'like', "%$word%")
                        ->orWhere('surname', 'like', "%$word%")
                        ->orWhere('given_name', 'like', "%$word%");
                }
            });
        }

        // Retrieve clients filtered by search
        $clients = $clientQuery->orderBy('updated_at', 'desc')->pluck('id');

        $loanQuery = Loan::where('org_id', $user->org_id);

        if ($user->branch->branch_name !== 'Head Office') {
            // User is not in the 'Head Office' branch, so return loans for the specific branch
            $loanQuery->where('branch_id', $user->branch_id);
        }

        // Retrieve loans that match the client IDs and have status approved
        $loans = $loanQuery
            ->whereIn('client_id', $clients)
            ->where('status', 'approved')
            ->orderBy('updated_at', 'desc')
            ->paginate(40);

        return view('loans.status.approved', compact('loans', 'user', 'organization'));
    }


    //disbursed
    public function disbursed(Request $request)
    {
        $user = Auth::user();
        $organization = Organization::find($user->org_id);

        $search = $request->input('search');
        $searchWords = explode(' ', $search); // Get the search input

        $clientQuery = Client::where('org_id', $user->org_id);

        if ($user->branch->branch_name !== 'Head Office') {
            // User is not in the 'Head Office' branch, so return clients for the specific branch
            $clientQuery->where('branch_id', $user->branch_id);
        }

        if (!empty($search)) {
            // If search input is not empty, apply the search filter for clients
            $clientQuery->where(function ($query) use ($searchWords) {
                foreach ($searchWords as $word) {
                    $query->orWhere('client_number', 'like', "%$word%")
                        ->orWhere('surname', 'like', "%$word%")
                        ->orWhere('given_name', 'like', "%$word%");
                }
            });
        }

        // Retrieve clients filtered by search
        $clients = $clientQuery->orderBy('updated_at', 'desc')->pluck('id');

        $loanQuery = Loan::where('org_id', $user->org_id);

        if ($user->branch->branch_name !== 'Head Office') {
            // User is not in the 'Head Office' branch, so return loans for the specific branch
            $loanQuery->where('branch_id', $user->branch_id);
        }

        // Retrieve loans that match the client IDs and have status disbursed
        $loans = $loanQuery
            ->whereIn('client_id', $clients)
            ->where('status', 'disbursed')
            ->orderBy('updated_at', 'desc')
            ->paginate(40);

        return view('loans.status.disbursed', compact('loans', 'user', 'organization'));
    }

    //cleared
    public function cleared(Request $request)
    {
        $user = Auth::user();
        $organization = Organization::find($user->org_id);

        $search = $request->input('search');
        $searchWords = explode(' ', $search); // Get the search input

        $clientQuery = Client::where('org_id', $user->org_id);

        if ($user->branch->branch_name !== 'Head Office') {
            // User is not in the 'Head Office' branch, so return clients for the specific branch
            $clientQuery->where('branch_id', $user->branch_id);
        }

        if (!empty($search)) {
            // If search input is not empty, apply the search filter for clients
            $clientQuery->where(function ($query) use ($searchWords) {
                foreach ($searchWords as $word) {
                    $query->orWhere('client_number', 'like', "%$word%")
                        ->orWhere('surname', 'like', "%$word%")
                        ->orWhere('given_name', 'like', "%$word%");
                }
            });
        }

        // Retrieve clients filtered by search
        $clients = $clientQuery->orderBy('updated_at', 'desc')->pluck('id');

        $loanQuery = Loan::where('org_id', $user->org_id);

        if ($user->branch->branch_name !== 'Head Office') {
            // User is not in the 'Head Office' branch, so return loans for the specific branch
            $loanQuery->where('branch_id', $user->branch_id);
        }

        // Retrieve loans that match the client IDs and have status cleared
        $loans = $loanQuery
            ->whereIn('client_id', $clients)
            ->where('status', 'cleared')
            ->orderBy('updated_at', 'desc')
            ->paginate(40);

        return view('loans.status.cleared', compact('loans', 'user', 'organization'));
    }

    //waived_off
    public function waived(Request $request)
    {
        $user = Auth::user();
        $organization = Organization::find($user->org_id);

        $search = $request->input('search');
        $searchWords = explode(' ', $search); // Get the search input

        $clientQuery = Client::where('org_id', $user->org_id);

        if ($user->branch->branch_name !== 'Head Office') {
            // User is not in the 'Head Office' branch, so return clients for the specific branch
            $clientQuery->where('branch_id', $user->branch_id);
        }

        if (!empty($search)) {
            // If search input is not empty, apply the search filter for clients
            $clientQuery->where(function ($query) use ($searchWords) {
                foreach ($searchWords as $word) {
                    $query->orWhere('client_number', 'like', "%$word%")
                        ->orWhere('surname', 'like', "%$word%")
                        ->orWhere('given_name', 'like', "%$word%");
                }
            });
        }

        // Retrieve clients filtered by search
        $clients = $clientQuery->orderBy('updated_at', 'desc')->pluck('id');

        $loanQuery = Loan::where('org_id', $user->org_id);

        if ($user->branch->branch_name !== 'Head Office') {
            // User is not in the 'Head Office' branch, so return loans for the specific branch
            $loanQuery->where('branch_id', $user->branch_id);
        }

        // Retrieve loans that match the client IDs and have status waived_off
        $loans = $loanQuery
            ->whereIn('client_id', $clients)
            ->where('status', 'waived_off')
            ->orderBy('updated_at', 'desc')
            ->paginate(40);

        return view('loans.status.waived-off', compact('loans', 'user', 'organization'));
    }

    //deferred
    public function deferred(Request $request)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);

        $search = $request->input('search');
        $searchWords = explode(' ', $search); // Get the search input

        $clientQuery = Client::where('org_id', $user->org_id);

        if ($user->branch->branch_name !== 'Head Office') {
            // User is not in the 'Head Office' branch, so return clients for the specific branch
            $clientQuery->where('branch_id', $user->branch_id);
        }

        if (!empty($search)) {
            // If search input is not empty, apply the search filter for clients
            $clientQuery->where(function ($query) use ($searchWords) {
                foreach ($searchWords as $word) {
                    $query->orWhere('client_number', 'like', "%$word%")
                        ->orWhere('surname', 'like', "%$word%")
                        ->orWhere('given_name', 'like', "%$word%");
                }
            });
        }

        // Retrieve clients filtered by search
        $clients = $clientQuery->orderBy('updated_at', 'desc')->pluck('id');

        $loanQuery = Loan::where('org_id', $user->org_id);

        if ($user->branch->branch_name !== 'Head Office') {
            // User is not in the 'Head Office' branch, so return loans for the specific branch
            $loanQuery->where('branch_id', $user->branch_id);
        }

        // Retrieve loans that match the client IDs and have status deferred
        $loans = $loanQuery
            ->whereIn('client_id', $clients)
            ->where('status', 'deferred')
            ->orderBy('updated_at', 'desc')
            ->paginate(40);

        return view('loans.status.deferred', compact('loans', 'user', 'organization'));
    }



    public function create(Client $client)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        // Retrieve all branches belonging to the organization
        $branches = Branch::where('org_id', $user->org_id)->get();
        // Retrieve all loan products belonging to the organization
        $loan_products = LoanProduct::where('org_id', $user->org_id)->get();

        //get client's age
        $dob = Carbon::parse($client->dob);
        $age = $dob->age;

        return view('loans.create', compact('user', 'organization', 'branches', 'loan_products', 'client', 'age'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'org_id' => 'required|exists:organizations,id',
            'branch_id' => 'required|exists:branches,id',
            'client_id' => 'required|exists:clients,id',
            'loan_product_id' => 'required|exists:loan_products,id',
            'application_amount' => 'required|numeric|min:0',
            'purpose' => 'nullable|string',
            'application_date' => 'required|date',
            'application_period' => 'required|integer|min:0',
            'status' => 'required|in:pending_appraisal,pending_approval,approved,disbursed,cleared,deferred,waived_off,written_off',
        ]);

        $loan_product = LoanProduct::findOrFail($request->loan_product_id);

        if ($request->application_period > $loan_product->max_loan_period) {
            return back()->withInput()->with('error', 'Loan Application Period cannot be greater than the Maximum Loan Period!.');
        }

        Loan::create($validatedData);

        $client = Client::findOrFail($validatedData['client_id']);
        return redirect()->route('clients.client', ['client' => $client])->with('success', 'Loan Created successfully.');
    }

    public function loan(Loan $loan)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        // Retrieve all loan products belonging to the organization
        $loan_products = LoanProduct::where('org_id', $user->org_id)->get();

        //get client
        $client = Client::findOrFail($loan->client_id);
        $dob = Carbon::parse($client->dob);
        $age = $dob->age;

        // Retrieve all users belonging to the organization and client branch
        $users = User::where('org_id', $user->org_id)
            ->get();

        // Fetching the Asset account related to the user
        $cash_account = Account::where('user_id', $user->id)
            ->where('type', 'Asset')
            ->first(); // Fetch a single record
        // Now, you can retrieve the ID or other attributes of the cash account
        $cash_account_id = $cash_account ? $cash_account->id : null;

        //pending_appraisal
        if ($loan->status == 'pending_appraisal') {
            return view('loans.pending-appraisal', compact('client', 'user', 'organization', 'age', 'loan', 'loan_products', 'users'));
        }
        //pending_approval
        if ($loan->status == 'pending_approval') {
            return view('loans.pending-approval', compact('client', 'user', 'organization', 'age', 'loan', 'loan_products', 'users'));
        }
        //approved
        if ($loan->status == 'approved') {
            return view('loans.approved', compact('client', 'user', 'organization', 'age', 'loan', 'loan_products', 'users', 'cash_account_id'));
        }
        //disbursed

        // payments
        $transactions = Transaction::where('org_id', $user->org_id)
            ->where('type', 'Payment')
            ->where('client_id', $client->id)
            ->where('loan_id', $loan->id)
            ->orderBy('created_at', 'desc')
            ->get();


        $details = [];
        foreach ($transactions as $transaction) {
            $transactionDetails = TransactionDetail::where('transaction_id', $transaction->id)->get();
            $details[$transaction->id] = $transactionDetails;
        }
        if ($loan->status == 'disbursed') {
            return view('loans.disbursed', compact('client', 'user', 'organization', 'age', 'loan', 'loan_products', 'users', 'transactions', 'details'));
        }
        //cleared
        if ($loan->status == 'cleared') {
            return view('loans.cleared', compact('client', 'user', 'organization', 'age', 'loan', 'loan_products', 'users', 'transactions', 'details'));
        }
        //defer
        if ($loan->status == 'deferred') {
            return view('loans.deferred', compact('client', 'user', 'organization', 'age', 'loan', 'loan_products', 'users'));
        }

        return redirect()->route('clients.client', ['client' => $client]);
    }


    public function update(Request $request, Loan $loan)
    {
        $validatedData = $request->validate([
            'loan_product_id' => 'required|exists:loan_products,id',
            'application_amount' => 'required|numeric|min:0',
            'purpose' => 'nullable|string',
            'application_date' => 'required|date',
            'application_period' => 'required|integer|min:0',

            'appraisal_amount' => 'nullable|numeric|min:0',
            'appraisal_period' => 'nullable|integer|min:0',
            'appraisal_date' => 'nullable|date',
            'appraisal_comment' => 'nullable|string',
            'status' => 'required|in:pending_appraisal,pending_approval,approved,disbursed,cleared,deferred,waived_off,written_off',

            'file_link1' => 'image|mimes:jpeg,png,jpg|max:2048',
            'file_link2' => 'image|mimes:jpeg,png,jpg|max:2048',
            'file_link3' => 'image|mimes:jpeg,png,jpg|max:2048',
            'file_link4' => 'image|mimes:jpeg,png,jpg|max:2048',
            'file_link5' => 'image|mimes:jpeg,png,jpg|max:2048',
            'file_link6' => 'image|mimes:jpeg,png,jpg|max:2048',
            'file_link7' => 'image|mimes:jpeg,png,jpg|max:2048',
            'file_link8' => 'image|mimes:jpeg,png,jpg|max:2048',

            'loan_officer_id' => 'nullable|exists:users,id',
            'approval_officer_id' => 'nullable|exists:users,id',
            'disbursement_officer_id' => 'nullable|exists:users,id',

            'approved_amount' => 'nullable|numeric|min:0',
            'approved_period' => 'nullable|integer|min:0',
            'approved_date' => 'nullable|date',
            'approved_interest_rate' => 'nullable|numeric',
            'approved_comment' => 'nullable|string',

            'disbursement_date' => 'nullable|date',
        ]);

        if ($request->appraisal_date < $request->application_date) {
            return back()->withInput()->with('error', 'Appriasal Date cannot be Earlier than Application Date!.');
        }

        if ($request->appraisal_amount > $request->application_amount) {
            return back()->withInput()->with('error', 'Appriasal Amount cannot be greater than Application Amount!.');
        }

        $loan_product = LoanProduct::findOrFail($request->loan_product_id);

        if ($request->appraisal_period > $loan_product->max_loan_period) {
            return back()->withInput()->with('error', 'Appriasal Period cannot be greater than the Maximum Loan Period!.');
        }

        //handle file file_link1
        if ($request->hasFile('file_link1')) {
            // Delete the old file_link1 file
            if ($loan->file_link1) {
                Storage::delete('public/' . $loan->file_link1);
            }
            $validatedData['file_link1'] = $request->file('file_link1')->store('loans', 'public');
        }
        //handle file file_link2
        if ($request->hasFile('file_link2')) {
            // Delete the old file_link2 file
            if ($loan->file_link2) {
                Storage::delete('public/' . $loan->file_link2);
            }
            $validatedData['file_link2'] = $request->file('file_link2')->store('loans', 'public');
        }
        //handle file file_link3
        if ($request->hasFile('file_link3')) {
            // Delete the old file_link3 file
            if ($loan->file_link3) {
                Storage::delete('public/' . $loan->file_link3);
            }
            $validatedData['file_link3'] = $request->file('file_link3')->store('loans', 'public');
        }
        //handle file file_link4
        if ($request->hasFile('file_link4')) {
            // Delete the old file_link4 file
            if ($loan->file_link4) {
                Storage::delete('public/' . $loan->file_link4);
            }
            $validatedData['file_link4'] = $request->file('file_link1')->store('loans', 'public');
        }
        //handle file file_link5
        if ($request->hasFile('file_link5')) {
            // Delete the old file_link5 file
            if ($loan->file_link5) {
                Storage::delete('public/' . $loan->file_link5);
            }
            $validatedData['file_link5'] = $request->file('file_link5')->store('loans', 'public');
        }
        //handle file file_link6
        if ($request->hasFile('file_link6')) {
            // Delete the old file_link6 file
            if ($loan->file_link6) {
                Storage::delete('public/' . $loan->file_link6);
            }
            $validatedData['file_link6'] = $request->file('file_link6')->store('loans', 'public');
        }
        //handle file file_link7
        if ($request->hasFile('file_link7')) {
            // Delete the old file_link7 file
            if ($loan->file_link7) {
                Storage::delete('public/' . $loan->file_link7);
            }
            $validatedData['file_link7'] = $request->file('file_link7')->store('loans', 'public');
        }
        //handle file file_link8
        if ($request->hasFile('file_link8')) {
            // Delete the old file_link8 file
            if ($loan->file_link8) {
                Storage::delete('public/' . $loan->file_link8);
            }
            $validatedData['file_link8'] = $request->file('file_link8')->store('loans', 'public');
        }


        // Update the loan with the validated data
        $loan->update($validatedData);
        return redirect()->route('loans.loan', ['loan' => $loan])->with('success', 'Loan Updated  successfully.');
    }

    //loan approval
    public function approve(Request $request, Loan $loan)
    {
        $validatedData = $request->validate([
            'approval_officer_id' => 'nullable|exists:users,id',
            'approved_amount' => 'nullable|numeric|min:0',
            'approved_period' => 'nullable|integer|min:0',
            'approved_date' => 'nullable|date',
            'approved_interest_rate' => 'nullable|numeric',
            'approved_comment' => 'nullable|string',
            'status' => 'required|in:pending_appraisal,pending_approval,approved,disbursed,cleared,deferred,waived_off,written_off',
        ]);

        if ($request->approved_date < $loan->appraisal_date) {
            return back()->withInput()->with('error', 'Approval Date cannot be Earlier than Appraisal Date!.');
        }

        if ($request->approved_amount > $loan->appraisal_amount) {
            return back()->withInput()->with('error', 'Approval Amount cannot be greater than Appraisal Amount!.');
        }

        $loan_product = LoanProduct::findOrFail($loan->loan_product_id);

        if ($request->approved_period > $loan_product->max_loan_period) {
            return back()->withInput()->with('error', 'Approval Period cannot be greater than the Maximum Loan Period!.');
        }

        // Update the loan with the validated data
        $loan->update($validatedData);
        return redirect()->route('loans.loan', ['loan' => $loan])->with('success', 'Loan Updated successfully.');
    }


    //loan Disbursement
    public function disburse(Request $request, Loan $loan)
    {
        $validatedData = $request->validate([
            'disbursement_officer_id' => 'nullable|exists:users,id',
            'approved_period' => 'nullable|integer|min:0',
            'disbursement_date' => 'nullable|date',
            'voucher_number' => 'nullable|string',
            'status' => 'required|in:pending_appraisal,pending_approval,approved,disbursed,cleared,deferred,waived_off,written_off',
        ]);


        try {

            // Start a database transaction
            DB::beginTransaction();

            if ($request->disbursement_date < $loan->approved_date) {
                return back()->withInput()->with('error', 'Disbursement Date cannot be Earlier than Approval Date!.');
            }

            if ($request->approved_period > $loan->loanProduct->max_loan_period) {
                return back()->withInput()->with('error', 'Disbursment Period cannot be greater than the Maximum Loan Period!.');
            }

            // Fetching the Asset account related to the user
            $cash_account = Account::where('user_id', $request->user_id)
                ->where('type', 'Asset')
                ->first(); // Fetch a single record
            if ($cash_account->balance < $loan->approved_amount) {
                // If the balance is insufficient, rollback the transaction and return an error message
                DB::rollback();
                return back()->withInput()->with('error', 'Insufficient funds in the cash account to cover this transaction!.');
            }

            // Update the loan with the validated data
            $loan->update($validatedData);

            //-------schedule----
            $principal = $loan->approved_amount;  // Get original loan amount
            $interestRate = $loan->approved_interest_rate / 100;  // Convert interest rate to decimal
            $tenure = $loan->approved_period;  // Get loan duration in months

            $dueDate = Carbon::parse($loan->disbursement_date);  // Set initial due date
            $dueDate->addMonth();

            for ($i = 1; $i <= $tenure; $i++) {
                $interest = $principal * $interestRate;  // Calculate interest for the current period declining balance
                $principalPayment = $principal / $tenure;  // Calculate principal payment

                $schedule = new Schedule();
                $schedule->fill([
                    'loan_id' => $loan->id,
                    'type'  => 'Schedule',
                    'date' => $dueDate->format('Y-m-d'),
                    'principal' => $principalPayment,
                    'interest' => $interest,
                ]);
                $schedule->save();
                // dd($schedule);
                $dueDate->addMonth();  // Set due date for the next month
            }

            $client = Client::findOrFail($loan->client_id);
            // Create a new Transaction
            $transaction = new Transaction();
            $transaction->fill([
                'org_id' =>  $request->org_id,
                'branch_id' =>  $request->branch_id,
                'user_id' =>  $request->user_id,
                'client_id' =>  $request->client_id,
                'type' =>  $request->type,
                'description' =>  $request->description,
                'amount' =>  $loan->approved_amount,
                'date' =>  $request->disbursement_date,
            ]);
            $transaction->save();
            //dd($transaction);
            // Fetching the Asset account related to the loan
            $loan_account = Account::where('loan_product_id', $loan->loan_product_id)
                ->where('type', 'Asset')
                ->first(); // Fetch a single record


            $detail1 = new TransactionDetail();
            $detail1->fill([
                'org_id' => $request->org_id,
                'transaction_id' => $transaction->id,
                'account_id' => $loan_account->id,
                'amount' => $transaction->amount,
                'type' =>  'Disbursement',
                'debit_credit' => 'Debit',
            ]);
            $detail1->save();


            // Create transaction details - credit
            $detail2 = new TransactionDetail();
            $detail2->fill([
                'org_id' => $transaction->org_id,
                'transaction_id' => $transaction->id,
                'account_id' => $cash_account->id,
                'amount' => $transaction->amount,
                'type' => $request->type,
                'debit_credit' => 'Credit',
            ]);
            $detail2->save();

            $loanAccount = Account::findOrFail($loan_account->id);
            $loanAccount->balance += $transaction->amount; //debit an asset account
            $loanAccount->save();

            $cashAccount = Account::findOrFail($cash_account->id);
            $cashAccount->balance -= $transaction->amount;
            $cashAccount->save();

            // Commit the database transaction
            DB::commit();

            return redirect()->route('clients.client', ['client' => $client])->with('success', 'Loan Disbursed successfully.');
        } catch (\Exception $e) {
            // If an exception occurs, rollback the database transaction
            DB::rollback();

            return back()->withInput()->with('error', 'Failed to register the transaction. Please try again.');
        }
    }

    //loan deferment
    public function defer(Request $request, Loan $loan)
    {
        $validatedData = $request->validate([
            'approval_officer_id' => 'nullable|exists:users,id',
            'approved_date' => 'nullable|date',
            'defer_comment' => 'nullable|string',
            'status' => 'required|in:pending_appraisal,pending_approval,approved,disbursed,cleared,deferred,waived_off,written_off',
        ]);

        $client = Client::findOrFail($loan->client_id);
        // Update the loan with the validated data
        $loan->update($validatedData);

        return redirect()->route('clients.client', ['client' => $client])->with('success', 'Loan Updated successfully.');
    }


    public function destroy(Loan $loan)
    {
        // Delete the loan
        $loan->delete();

        return redirect()->route('loans.index')->with('success', 'Loan deleted successfully.');
    }



    public function schedule(Loan $loan)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        $client = Client::find($loan->client_id);

        $principal = $loan->approved_amount;  // Get original loan amount
        $interestRate = $loan->approved_interest_rate / 100;  // Convert interest rate to decimal
        $tenure = $loan->approved_period;  // Get loan duration in months
        //$installmentAmount = $principal * ($interestRate / (1 - pow(1 + $interestRate, -$tenure)));  // Calculate fixed installment declining balance
        $installmentAmount = $principal * $interestRate + ($principal / $tenure);  // Calculate fixed installment flat


        $schedule = new Collection();  // Create a collection to store schedule data

        $balance = $principal;  // Initialize remaining balance
        $dueDate = Carbon::parse($loan->disbursement_date);  // Set initial due date
        $dueDate->addMonth();
        $totalamount = $installmentAmount * $tenure;

        for ($i = 1; $i <= $tenure; $i++) {
            //$interest = $balance * $interestRate;  // Calculate interest for the current period declining balance
            $interest = $principal * $interestRate;  // Calculate interest for the current period declining balance
            $principalPayment = $installmentAmount - $interest;  // Calculate principal payment
            $balance -= $principalPayment;  // Update remaining balance

            $schedule->push([
                'due_date' => $dueDate->format('l,  d-m-Y'),
                'principal' => $principalPayment,
                'interest' => $interest,
                'installment' => $installmentAmount,
                'balance' => $balance,
            ]);

            $dueDate->addMonth();  // Set due date for the next month
        }

        // Pass the schedule data to the view
        return view('loans.schedule', compact('user', 'organization', 'loan', 'client', 'schedule', 'totalamount'));
    }

    public function schedule_print(Loan $loan)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        $client = Client::find($loan->client_id);

        $principal = $loan->approved_amount;  // Get original loan amount
        $interestRate = $loan->approved_interest_rate / 100;  // Convert interest rate to decimal
        $tenure = $loan->approved_period;  // Get loan duration in months
        //$installmentAmount = $principal * ($interestRate / (1 - pow(1 + $interestRate, -$tenure)));  // Calculate fixed installment declining balance
        $installmentAmount = $principal * $interestRate + ($principal / $tenure);  // Calculate fixed installment flat

        $schedule = new Collection();  // Create a collection to store schedule data

        $balance = $principal;  // Initialize remaining balance
        $dueDate = Carbon::parse($loan->disbursement_date);  // Set initial due date
        $dueDate->addMonth();
        $totalamount = $installmentAmount * $tenure;

        for ($i = 1; $i <= $tenure; $i++) {
            //$interest = $balance * $interestRate;  // Calculate interest for the current period declining balance
            $interest = $principal * $interestRate;  // Calculate interest for the current period declining balance
            $principalPayment = $installmentAmount - $interest;  // Calculate principal payment
            $balance -= $principalPayment;  // Update remaining balance

            $schedule->push([
                'due_date' => $dueDate->format('l,  d-m-Y'),
                'principal' => $principalPayment,
                'interest' => $interest,
                'installment' => $installmentAmount,
                'balance' => $balance,
            ]);

            $dueDate->addMonth();  // Set due date for the next month
        }

        // Pass the schedule data to the view
        return view('loans.schedule-print', compact('user', 'organization', 'loan', 'client', 'schedule', 'totalamount'));
    }

    public function ledger(Loan $loan)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        $client = Client::find($loan->client_id);

        //installments in arrears
        $installments = Schedule::where('loan_id', $loan->id)->where('is_reversed', 0)->orderBy('date', 'asc')->get();

        return view('loans.ledger', compact('user', 'organization', 'loan', 'client', 'installments'));
    }


    public function loan_payment_create(Loan $loan)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        $client = Client::find($loan->client_id);
        // Fetching the Asset account related to the user
        $cash_account = Account::where('user_id', $user->id)
            ->where('type', 'Asset')
            ->first(); // Fetch a single record
        // Now, you can retrieve the ID or other attributes of the cash account
        $cash_account_id = $cash_account ? $cash_account->id : null;

        
        //this has taken alot of my brains 
        //installments in arrears
        $installments = Schedule::where('loan_id', $loan->id)->where('type', 'Schedule')->orderBy('date', 'asc')->get();

        //retrieve the payments made
        $payments = Schedule::where('loan_id', $loan->id)->where('type', 'Payment')->get();
        
        $totalPaidPrincipal = $payments->sum('principal');
        $totalPaidInterest = $payments->sum('interest');


        // Distribute the payments to the installments
        $timeline = collect([]);//hold all intallment and payments
        $principalPaid = 0;
        $interestPaid = 0;
        foreach ($installments as $installment) {
            $principalDue =  $installment->principal;
            $interestDue =  $installment->interest;           

            if ($totalPaidPrincipal > 0) {
                //dd($totalPaidPrincipal);
                
                if ($totalPaidPrincipal >=  $principalDue) {
                    $principalPaid = $principalDue;
                    $principalDue = 0;                    
                }else{
                    $principalPaid = $totalPaidPrincipal; //paid half principal
                    $principalDue -= $principalPaid;
                }
                $totalPaidPrincipal -= $principalPaid;
            }

            //interest
            if ($totalPaidInterest > 0) {
                if ($totalPaidInterest >=  $interestDue) {
                    $interestPaid = $interestDue; 
                    $interestDue = 0;                   
                }else{
                    $interestPaid = $totalPaidInterest;
                    $interestDue -= $interestPaid;
                }
                $totalPaidInterest -= $interestPaid;
            }

             //days in arrears
             $daysInArrears = now()->diffInDays($installment->date);
             // $diff = now()->diffInDays($model->created_at);
             $daysInArrears -= $loan->loanProduct->arrears_maturity_period;
             //dd($daysInArrears);
             $penalties = 0;
             $penalties += ( $principalDue + $interestDue) * ( $loan->loanProduct->penalty_rate / 100);
             //dd($penalties);

            $timeline->push([
                'date' => $installment->date,
                'type' => $installment->type,
                'principal' => $principalDue,
                'interest' => $interestDue,
                'paid_principal' => $principalPaid,
                'paid_interest' => $interestPaid,
                'penalties' =>  $penalties,
            ]);
        }
        
        $installmentsInArrears = collect($timeline)->filter(function ($installment) {
            return $installment['date'] < now()->format('Y-m-d') && ($installment['principal'] > 0 || $installment['interest'] > 0); // Compare due date with current date
        });


        return view('loans.payment', compact('user', 'organization', 'loan', 'client', 'cash_account_id', 'installmentsInArrears'));
    }


    //loan payment
    public function loan_payment_store(Request $request, Loan $loan)
    {

        $amount =  (int) str_replace(",", "", $request->amount);
        $paid_principal = (int) str_replace(",", "", $request->paid_principal);
        $paid_interest = (int) str_replace(",", "", $request->paid_interest);

        $total_instal = $paid_principal + $paid_interest;
        $paid_penalties = 0;
        if ($request->paid_penalties) {
            $paid_penalties = (int) str_replace(",", "", $request->paid_penalties);
            $total_instal += $paid_penalties;
        }

        try {
            // Start a database transaction
            DB::beginTransaction();

            // Fetching the Asset account related to the user
            $cash_account = Account::where('user_id', $request->user_id)
                ->where('type', 'Asset')
                ->first(); // Fetch a single record


            if ($amount != $total_instal) {
                DB::rollback();
                return back()->withInput()->with('error', 'Total Amount Paid should be equal to
                 (Principal + Interest + Penalties)!.');
            }

            //update schedule
            $schedule = new Schedule();
            $schedule->fill([
                'loan_id' => $loan->id,
                'type'  => 'Payment',
                'date' =>  $request->date,
                'principal' => $paid_principal,
                'interest' => $paid_interest,
                'penalties' => $paid_penalties,
            ]);
            $schedule->save();


            // Create a new Transaction
            $transaction = new Transaction();
            $transaction->fill([
                'org_id' =>  $request->org_id,
                'branch_id' =>  $request->branch_id,
                'user_id' =>  $request->user_id,
                'client_id' =>  $request->client_id,
                'loan_id' =>  $loan->id,
                'type' =>  'Payment',
                'description' =>  'Loan Payment',
                'amount' =>  $amount,
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
                'type' => 'Payment',
                'debit_credit' => 'Debit',
            ]);
            $detail1->save();

            $cash_account->balance += $transaction->amount; // debit an asset account
            $cash_account->save();


            //principal
            $principal_account = Account::where('loan_product_id', $loan->loan_product_id)->where('type', 'Asset')->first();
            //dd($principal_account);
            // Create transaction details - credit
            $detail2 = new TransactionDetail();
            $detail2->fill([
                'org_id' => $transaction->org_id,
                'transaction_id' => $transaction->id,
                'account_id' => $principal_account->id,
                'amount' => $paid_principal,
                'type' => 'Principal',
                'debit_credit' => 'Credit',
            ]);

            $detail2->save();

            $principal_account->balance -= $paid_principal; // credit an asset account
            $principal_account->save();
            // dd($principal_account);
            //interest
            $interest_account = Account::where('loan_product_id', $loan->loan_product_id)->where('type', 'Income')->first();
            // Create transaction details - credit
            $detail3 = new TransactionDetail();
            $detail3->fill([
                'org_id' => $transaction->org_id,
                'transaction_id' => $transaction->id,
                'account_id' => $interest_account->id,
                'amount' => $paid_interest,
                'type' => 'Interest',
                'debit_credit' => 'Credit',
            ]);
            $detail3->save();

            $interest_account->balance += $paid_interest; // credit an income account
            $interest_account->save();

            //penalties
            if ($request->paid_penalties) {
                //fetched the account acording to name   
                $penalties_account = Account::where('name', 'Late Payment Fees')->where('type', 'Income')->first();
                // Create transaction details - credit
                $detail3 = new TransactionDetail();
                $detail3->fill([
                    'org_id' => $transaction->org_id,
                    'transaction_id' => $transaction->id,
                    'account_id' => $penalties_account->id,
                    'amount' => $paid_penalties,
                    'type' => 'Penalties',
                    'debit_credit' => 'Credit',
                ]);
                $detail3->save();

                $penalties_account->balance += $paid_penalties; // credit an income account
                $penalties_account->save();
            }


            //update loan balance
            $loan->paid_principal += $paid_principal;
            $loan->paid_interest += $paid_interest;
            $total_interest =  $loan->approved_amount * ($loan->approved_interest_rate / 100) * $loan->approved_period; 
            if ($request->paid_penalties) {
                $loan->paid_penalties += $paid_penalties;
            }
            //update status
           if($loan->paid_principal == $loan->approved_amount && $loan->paid_interest == $total_interest){
            $loan->status = 'cleared';
           }
            $loan->save();
            // Commit the database transaction
            DB::commit();

            $client = Client::findOrFail($loan->client_id);
            return redirect()->route('clients.client', ['client' => $client])->with('success', 'Payment registered  successfully.');
        } catch (\Exception $e) {
            // If an exception occurs, rollback the database transaction
            DB::rollback();

            return back()->withInput()->with('error', 'Failed to register the transaction. Please try again.');
        }
    }
}
