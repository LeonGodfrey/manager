<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
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

       

      //  $account_id = 0;

      $transactions = [];
      $details = [];
        // Apply filters
        if ($request->filled('account_id')) {
            $query = Transaction::query();
            
            $query->whereHas('details', function ($query) use ($request) {
                $query->where('account_id', $request->input('account_id'));
            });


            if ($request->filled('from_date')) {
                $query->where('date', '>=', $request->input('from_date'));
            }
    
            if ($request->filled('to_date')) {
                $query->where('date', '<=', $request->input('to_date'));
            }
    
            $transactions = $query->orderBy('created_at', 'asc')->paginate(50);

            foreach ($transactions as $transaction) {
                $transactionDetails = TransactionDetail::where('transaction_id', $transaction->id)->get();
                $details[$transaction->id] = $transactionDetails;
            }

        }

       

        return view('reports.cashbook', compact('transactions', 'details', 'user', 'organization', 'accounts', 'branches'));
    }

}
