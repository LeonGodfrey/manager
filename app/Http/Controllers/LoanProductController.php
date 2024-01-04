<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\LoanProduct;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanProductController extends Controller
{
    public function index()
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        $loan_products = LoanProduct::where('org_id', $user->org_id)->where('status', 'Active')->get(); // Use get() to execute the query
        return view('organization.loan-products.index', compact('loan_products', 'user', 'organization'));
    }

    public function create()
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        return view('organization.loan-products.create', compact('user', 'organization'));
    }

    public function store(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'org_id' => 'required|exists:organizations,id',
            'name' => 'required|max:255',
            'interest_method' => 'required|in:Declining_balance,Flat',
            'interest_rate' => 'required|numeric',
            'payment_frequency' => 'required|in:Daily,Weekly,Monthly,Quarterly,Annually',
            'penalty_rate' => 'required|numeric',
            'grace_period' => 'required|integer',
            'charge_interest_grace_period' => 'required|boolean',
            'arrears_maturity_period' => 'required|integer',
            'max_loan_period' => 'required|integer',
        ]);

        // Create a new loan product
        $loan_product = new LoanProduct();
        $loan_product->fill($validatedData);
        $loan_product->save();

        // Create an Asset account with the subtype "Loan Product"
        $account = new Account();
        $account->name = 'Principle '.$loan_product->name; // Use the loan_product name as the account name
        $account->type = 'Asset';
        $account->subtype = 'Loan Product';
        $account->org_id = $loan_product->org_id; // Set the organization ID for the account
        $account->loan_product_id = $loan_product->id;
        $account->save();
        // Create an Income account for interest income
        $account = new Account();
        $account->name = 'Interest '.$loan_product->name; // Use the loan_product name as the account name
        $account->type = 'Income';
        $account->subtype = 'Interest Income';
        $account->org_id = $loan_product->org_id; // Set the organization ID for the account
        $account->loan_product_id = $loan_product->id;
        $account->save();
        return redirect()->route('settings.loan-products.index')->with('success', 'Loan Product created successfully.');
    }

    public function edit(LoanProduct $loan_product)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        return view('organization.loan-products.edit', compact('loan_product', 'user', 'organization'));
    }

    public function update(Request $request, LoanProduct $loan_product)
    {
       // Validate the input data
       $validatedData = $request->validate([
        'name' => 'required|max:255',
        'interest_method' => 'required|in:Declining_balance,Flat',
        'interest_rate' => 'required|numeric',
        'payment_frequency' => 'required|in:Daily,Weekly,Monthly,Quarterly,Annually',
        'penalty_rate' => 'required|numeric',
        'grace_period' => 'required|integer',
        'charge_interest_grace_period' => 'required|boolean',
        'arrears_maturity_period' => 'required|integer',
        'max_loan_period' => 'required|integer',
    ]);

        // Update the savings product with the validated data
        $loan_product->update($validatedData);
        return redirect()->route('settings.loan-products.index')->with('success', 'Loan Product updated successfully.');
    }
}
