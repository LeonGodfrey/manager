<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\SavingsProduct;
use Illuminate\Support\Facades\Auth;

class SavingsProductController extends Controller
{
    public function index()
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        $savings_products = SavingsProduct::where('org_id', $user->org_id)->get(); // Use get() to execute the query
        return view('organization.savings-products.index', compact('savings_products', 'user', 'organization'));
    }

    public function create()
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        return view('organization.savings-products.create', compact('user', 'organization'));
    }

    public function store(Request $request)
{
//    dd($request);
    $validatedData = $request->validate([
        'name' => 'required|max:255',
        'org_id' => 'required',
        'saving_product_type' => 'required',
        'opening_balance' => 'required|numeric',
        'min_balance' => 'required|numeric',
        'deposit_fee' => 'required|numeric',
        'withdrawal_fee' => 'required|numeric',
        'monthly_fee' => 'required|numeric',
        'interest_rate' => 'required|numeric',
        'interest_frequency' => 'nullable|in:Daily,Weekly,Monthly,Quarterly,Annually',
    ]);

    $savings_product = new SavingsProduct();
    $savings_product->fill($validatedData);
    $savings_product->save();
    
        // Create an Liability account with the subtype "Savings Product"
        $account = new Account();
        $account->name = $savings_product->name; // Use the savings_product name as the account name
        $account->type = 'Liability';
        $account->subtype = 'Savings Product';
        $account->org_id = $savings_product->org_id; // Set the organization ID for the account
        $account->savings_product_id = $savings_product->id;
        $account->save();

    return redirect()->route('settings.savings-products.index')->with('success', 'Savings Product created successfully.');
}

    public function edit(SavingsProduct $savings_product)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        return view('organization.savings-products.edit', compact('savings_product', 'user', 'organization'));
    }

    public function update(Request $request, SavingsProduct $savings_product)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'saving_product_type' => 'required',
            'opening_balance' => 'required|numeric',
            'min_balance' => 'required|numeric',
            'deposit_fee' => 'required|numeric',
            'withdrawal_fee' => 'required|numeric',
            'monthly_fee' => 'required|numeric',
            'interest_rate' => 'required|numeric',
            'interest_frequency' => 'nullable|in:Daily,Weekly,Monthly,Quarterly,Annually',
        ]);
        // Update the savings product with the validated data
        $savings_product->update($validatedData);
        return redirect()->route('settings.savings-products.index')->with('success', 'Savings Product updated successfully.');
    }
}
