<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Account;
use App\Models\Loan;
use App\Models\LoanProduct;
use App\Models\Transaction;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{

    public function cash_book(Request $request)
    {
        // Retrieve the authenticated user and organization
        $user = Auth::user();
        $organization = Organization::find($user->org_id);
        $branches = Branch::where('org_id', $user->org_id)->get();
        $accounts = Account::where('org_id', $user->org_id)
            ->where('type', 'Asset') // Filter by type = 'Asset'
            ->where('subtype', 'User Cash Account')
            ->get();

        $transactions = [];
        $details = [];
        $account_id = '';
        $account = '';
        $from_date = now()->format('Y-m-d');
        $to_date = now()->format('Y-m-d');

        
        return view('reports.cashbook', compact('transactions','user', 'organization', 'accounts', 'account', 'account_id', 'from_date', 'to_date'));
    }

    public function cash_book_filter(Request $request)
    {
        // Retrieve the authenticated user and organization
        $user = Auth::user();
        $organization = Organization::find($user->org_id);
        $branches = Branch::where('org_id', $user->org_id)->get();
        $accounts = Account::where('org_id', $user->org_id)
            ->where('type', 'Asset') // Filter by type = 'Asset'
            ->where('subtype', 'User Cash Account')
            ->get();


        $query = Transaction::query();
        // Apply filters
        if ($request->filled('account_id')) {
            $account_id = $request->account_id;
            $account = Account::findOrFail($account_id);

            $query->whereHas('details', function ($query) use ($request) {
                $query->where('account_id', $request->input('account_id'));
            });
        }

        if ($request->filled('from_date')) {
            $query->where('date', '>=', $request->input('from_date'));
            $from_date = $request->from_date;
        }
       
        if ($request->filled('to_date')) {
            $query->where('date', '<=', $request->input('to_date'));
            $to_date = $request->to_date;
        } 

        $transactions = $query->orderBy('created_at', 'asc')->get();

        $details = [];
        foreach ($transactions as $transaction) {
            $transactionDetails = TransactionDetail::where('transaction_id', $transaction->id)->get();
            $details[$transaction->id] = $transactionDetails;
        }


        return view('reports.cashbook', compact('transactions', 'details', 'user', 'organization', 'accounts', 'account', 'branches', 'account_id', 'from_date', 'to_date'));
    }


    public function disbursement(Request $request)
    {
        // Retrieve the authenticated user and organization
        $user = Auth::user();
        $organization = Organization::find($user->org_id);
        $branches = Branch::where('org_id', $user->org_id)->get();
        $credit_officers = User::where('org_id', $user->org_id) ->get();
        $loan_products = LoanProduct::where('org_id', $user->org_id) ->get();
        $from_date = now()->format('Y-m-d');
        $to_date = now()->format('Y-m-d');
        $branch = '';
        $branch_id = '';
        $credit_officer_id = '';
        $loan_product_id = '';
        $loans = [];
        
        return view('reports.loans.disbursement-report', compact('user', 'organization', 'branches', 'branch', 'loans', 'branch_id', 'credit_officer_id', 'loan_product_id', 'credit_officers', 'loan_products', 'from_date', 'to_date'));
    }
}
