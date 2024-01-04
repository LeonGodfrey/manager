<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        $accounts = Account::where('org_id', $user->org_id)
            ->where('type', 'Asset') // Filter by type = 'Asset'
            ->orderBy('subtype')
            ->get(); // Use get() to execute the query
        return view('organization.accounts.index', compact('accounts', 'user', 'organization'));
    }

    public function equity()
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        $accounts = Account::where('org_id', $user->org_id)
            ->where('type', 'Equity') // Filter by type = 'Equity'
            ->orderBy('subtype')
            ->get(); // Use get() to execute the query
        return view('organization.accounts.equity', compact('accounts', 'user', 'organization'));
    }
    public function expense()
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        $accounts = Account::where('org_id', $user->org_id)
            ->where('type', 'Expense') // Filter by type = 'Expense'
            ->orderBy('subtype')
            ->get(); // Use get() to execute the query
        return view('organization.accounts.expense', compact('accounts', 'user', 'organization'));
    }
    public function income()
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        // Retrieve all branches belonging to the organization
        $accounts = Account::where('org_id', $user->org_id)
            ->where('type', 'Income') // Filter by type = 'income'
            ->orderBy('subtype')
            ->get(); // Use get() to execute the query
        return view('organization.accounts.income', compact('accounts', 'user', 'organization'));
    }
    public function liability()
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        // Retrieve all branches belonging to the organization
        $accounts = Account::where('org_id', $user->org_id)
            ->where('type', 'Liability') // Filter by type = 'liability'
            ->orderBy('subtype')
            ->get(); // Use get() to execute the query
        return view('organization.accounts.liability', compact('accounts', 'user', 'organization'));
    }

    public function create()
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        // Retrieve all branches belonging to the organization
        return view('organization.accounts.create', compact('user', 'organization'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'number' => 'nullable|max:255',
            'type' => 'required|in:Asset,Equity,Expense,Income,Liability',
            'subtype' => 'nullable|max:255',
            'org_id' => 'required|exists:organizations,id',
        ]);

        // Create a new account
        $account = new Account();
        $account->fill($validatedData);
        $account->save();

        return redirect()->route('settings.accounts.index')->with('success', 'Account created successfully.');
    }

    public function edit(Account $account)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        return view('organization.accounts.edit', compact('account', 'user', 'organization'));
    }

    public function update(Request $request, Account $account)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'number' => 'nullable|max:255',
            'type' => 'required|in:Asset,Equity,Expense,Income',
            'subtype' => 'nullable|max:255',
        ]);
        // Update the account with the validated data
        $account->update($validatedData);
        return redirect()->route('settings.accounts.index')->with('success', 'account updated successfully.');
    }

    // -----------cash accounts-----------------
    public function cash_account_index(Request $request)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        $branches = Branch::where('org_id', $user->org_id)->get();

        $search = $request->input('search');

        $query = Account::where('org_id', $user->org_id)
            ->where('type', 'Asset') // Filter by type = 'Asset'
            ->where('subtype', 'User Cash Account');

        if (!empty($search)) {
            // If search input is not empty, apply the search filter
            $query->where('name', 'like', "%$search%");
        }

        // Sort clients by updated_at in descending order
        $accounts = $query->orderBy('updated_at', 'desc')->paginate(20);
        return view('cash-accounts.index', compact('accounts', 'user', 'organization'));
    }

    public function cash_account(Account $account)
    {
        // Retrieve the authenticated user and organization
        $user = Auth::user();
        $organization = Organization::find($user->org_id);

        // Retrieve transactions associated with the cash account
        $transactions = Transaction::whereHas('details', function ($query) use ($account) {
            $query->where('account_id', $account->id);
        })->orderBy('created_at', 'desc')->paginate(20);

        $details = [];
        foreach ($transactions as $transaction) {
            $transactionDetails = TransactionDetail::where('transaction_id', $transaction->id)->get();
            $details[$transaction->id] = $transactionDetails;
        }

        return view('cash-accounts.account', compact('transactions', 'details', 'user', 'organization', 'account'));
    }

    public function bank_account(Request $request)
    {      
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        $branches = Branch::where('org_id', $user->org_id)->get();

        $search = $request->input('search');

        $query = Account::where('org_id', $user->org_id)
            ->where('type', 'Asset') // Filter by type = 'Asset'
            ->where('subtype', 'Cash at Bank');

        if (!empty($search)) {
            // If search input is not empty, apply the search filter
            $query->where('name', 'like', "%$search%");
        }


        $accounts = $query->orderBy('updated_at', 'desc')->paginate(20);
        return view('cash-accounts.bank-accounts', compact('accounts', 'user', 'organization'));
    }

    public function vault_account(Request $request)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        $branches = Branch::where('org_id', $user->org_id)->get();

        $search = $request->input('search');

        $query = Account::where('org_id', $user->org_id)
            ->where('type', 'Asset') // Filter by type = 'Asset'
            ->where('subtype', 'Vault Account');

        if (!empty($search)) {
            // If search input is not empty, apply the search filter
            $query->where('name', 'like', "%$search%");
        }

        // Sort clients by updated_at in descending order
        $accounts = $query->orderBy('updated_at', 'desc')->paginate(20);
        return view('cash-accounts.vault-accounts', compact('accounts', 'user', 'organization'));
    }

    public function mobile_money_account(Request $request)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        $branches = Branch::where('org_id', $user->org_id)->get();

        $search = $request->input('search');

        $query = Account::where('org_id', $user->org_id)
            ->where('type', 'Asset') // Filter by type = 'Asset'
            ->where('subtype', 'Mobile Money');

        if (!empty($search)) {
            // If search input is not empty, apply the search filter
            $query->where('name', 'like', "%$search%");
        }

        // Sort clients by updated_at in descending order
        $accounts = $query->orderBy('updated_at', 'desc')->paginate(20);
        return view('cash-accounts.mobile-money-accounts', compact('accounts', 'user', 'organization'));
    }
}
