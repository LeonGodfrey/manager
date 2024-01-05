<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function cash_book(Account $account)
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

        return view('reports.cash-book', compact('transactions', 'details', 'user', 'organization', 'account'));
    }

}
