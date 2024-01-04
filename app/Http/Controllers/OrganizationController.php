<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\Account;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends Controller
{
    //
    public function index()
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        return view('organization.details', compact('organization', 'user'));
    }

    public function update(Request $request, $id)
    {
        // Step 1: Validate the Request Data
        $validator = Validator::make($request->all(), [
            'org_name' => 'required|max:255',
            'org_country' => 'required|max:255',
            'currency_code' => 'required|max:3',
            'incorporation_date' => 'required|date',
            'business_reg_no' => 'required|max:20',
            'manager_name' => 'required|max:255',
            'manager_contact' => 'required|max:20',
            'org_logo' => 'image|mimes:jpeg,png,jpg|max:2048' // Adjust the file types and size as needed
            // Add validation rules for other fields
        ]);

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Step 2: Update the Organization Information
        $organization = Organization::find($id);
        if (!$organization) {
            // Handle the case where the organization record is not found
            return redirect()->back()->with('error', 'Organization not found.');
        }
        //handle logo upload
        if ($request->hasFile('org_logo')) {

            // Delete the old logo file
            if ($organization->org_logo) {
                Storage::delete('public/' . $organization->org_logo);
            }
            $logoPath = $request->file('org_logo')->store('logos', 'public');
            $organization->org_logo = $logoPath;
        }

        $organization->org_name = $request->input('org_name');
        $organization->org_country = $request->input('org_country');
        $organization->currency_code = $request->input('currency_code');
        $organization->incorporation_date = $request->input('incorporation_date');
        $organization->business_reg_no = $request->input('business_reg_no');
        $organization->manager_name = $request->input('manager_name');
        $organization->manager_contact = $request->input('manager_contact');
        // $organization->org_logo = $request->input('org_logo');
        // Update other fields as needed

        $organization->save();

        // Step 3: Redirect back with a success message
        Session::flash('success', 'Organization information updated successfully.');

        return redirect()->route('organization.details');
    }

    //-------------branches-------------------
    //all branches
    public function branches()
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);

        // Retrieve all branches belonging to the organization
        $branches = Branch::where('org_id', $user->org_id)->get();

        return view('organization.branches.index', compact('branches', 'user', 'organization'));
    }

    //create branch form
    public function createBranch()
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);

        return view('organization.branches.create', compact('organization', 'user'));
    }

    public function storeBranch(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'org_id' => 'required',
            'branch_name' => 'required|max:255',
            'branch_phone' => 'required|max:13',
            'branch_email' => 'required|email',
            'branch_prefix' => 'required|max:3',
            'branch_street_address' => 'required|max:255',
            'branch_city' => 'required|max:255',
            'branch_district' => 'required|max:255',
            'branch_postcode' => 'required|max:255',
            'status' => 'required|max:255'
        ]);

        // Create a new branch
        $branch = new Branch();
        $branch->fill($validatedData);
        $branch->save();

        // Create an Asset account with the subtype "Vault Account"
        $account = new Account();
        $account->name = $branch->branch_name; // Use the branch name as the account name
        $account->type = 'Asset';
        $account->subtype = 'Vault Account';
        $account->org_id = $branch->org_id; // Set the organization ID for the account
        $account->branch_id = $branch->id;
        $account->save();
        return redirect()->route('settings.branches.index')->with('success', 'Branch created successfully.');
    }


    public function editBranch(Branch $branch)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        return view('organization.branches.edit', compact('branch', 'organization', 'user'));
    }

    public function updateBranch(Request $request, Branch $branch)
    {
        // Validate the input data
        $validatedData = $request->validate([

            'branch_name' => 'required|max:255',
            'branch_phone' => 'required|max:13',
            'branch_email' =>  ['required', 'email'], // Use the 'email' rule for email validation
            'branch_prefix' => 'required|max:3',
            'branch_street_address' => 'required|max:255',
            'branch_city' => 'required|max:255',
            'branch_district' => 'required|max:255',
            'branch_postcode' => 'required|max:255'
            // Add validation rules for other fields here
        ]);

        // Update the branch with the validated data
        $branch->update($validatedData);

        return redirect()->route('settings.branches.index')->with('success', 'Branch updated successfully.');
    }

    //------------------users------------------------
    public function users()
    {

        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        // Retrieve all branches belonging to the organization
        $branches = Branch::where('org_id', $user->org_id)->get();
        $users = User::where('org_id', $user->org_id)->where('status', 'enabled')->get();
        return view('organization.users.index', compact('users', 'user', 'branches', 'organization'));
    }

    public function disabledUsers()
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);

        // Retrieve all branches belonging to the organization
        $branches = Branch::where('org_id', $user->org_id)->get();

        // Retrieve disabled users associated with the organization
        $users = User::where('org_id', $user->org_id)
            ->where('status', 'disabled')
            ->get();

        return view('organization.users.disabled', compact('users', 'user', 'branches', 'organization'));
    }



    public function createUser()
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        // Retrieve all branches belonging to the organization
        $branches = Branch::where('org_id', $user->org_id)->get();
        return view('organization.users.create', compact('organization', 'branches', 'user'));
    }

    public function storeUser(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'org_id' => 'required',
            'branch_id' => 'required',
            'name' => 'required|max:255',
            'user_name' => ['required', 'max:255', Rule::unique('users', 'user_name')],
            'user_phone' => 'required|max:13',
            'email' =>  ['required', 'email', Rule::unique('users', 'email')], // Use the 'email' rule for email validation
            'password' => 'required|confirmed|min:8'
        ]);

        // Hash Password
        $validatedData['password'] = bcrypt($validatedData['password']);
        // Create a new branch
        $user = new User();
        $user->fill($validatedData);
        $user->save();

        // Check if the "Create Cash Account" checkbox is checked
        if ($request->has('cash_account')) {
            // Create an Asset account with the name "user_name-name"
            $account = new Account();
            $account->name = $user->user_name . '-' . $user->name; // Combine user_name and name
            $account->type = 'Asset';
            $account->subtype = 'User Cash Account';
            $account->org_id = $user->org_id; // Set the organization ID for the account
            $account->user_id = $user->id;
            $account->save();

            // Update the user's 'has_cash_account' field to 1
            $user->has_cash_account = 1;
            $user->save();
            
        }
        return redirect()->route('settings.users.index')->with('success', 'user created successfully.');
    }

    public function editUser(User $nowuser)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        // Retrieve all branches belonging to the organization
        $branches = Branch::where('org_id', $user->org_id)->get();
        //dd($nowuser);
        return view('organization.users.edit', compact('user', 'branches', 'nowuser', 'organization'));
    }

    public function updateUser(Request $request, User $nowuser)
    {
        // Define the validation rules array
        $rules = [
            'branch_id' => 'required',
            'name' => 'required|max:255',
            'user_name' => ['required', 'max:255'],
            'user_phone' => 'required|max:13',
            'email' => ['required', 'email'], // Use the 'email' rule for email validation
        ];

        // Check if the 'password' field is filled (not empty)
        if ($request->filled('password')) {
            $rules['password'] = 'confirmed|min:8';
        }

        // Validate the input data
        $validatedData = $request->validate($rules);

        // Update the user with the validated data
        $nowuser->update($validatedData);

        // Check if the "Create Cash Account" checkbox is checked
        if ($request->has('cash_account')) {
            // Create an Asset account with the name "user_name-name"
            $account = new Account();
            $account->name = $nowuser->user_name . '-' . $nowuser->name; // Combine user_name and name
            $account->type = 'Asset';
            $account->subtype = 'User Cash Account';
            $account->org_id = $nowuser->org_id; // Set the organization ID for the account
            $account->user_id = $nowuser->id;
            $account->save();

            // Update the user's 'has_cash_account' field to 1
            $nowuser->has_cash_account = 1;
            $nowuser->save();
        }

        return redirect()->route('settings.users.index')->with('success', 'User updated successfully.');
    }


    public function disableUser(User $nowuser)
    {
        // Disable the user (update the status to 'disabled' or set a flag)
        $nowuser->status = 'disabled';
        $nowuser->save();

        return redirect()->route('settings.users.index')->with('success', 'User disabled successfully.');
    }

    public function enableUser(User $nowuser)
    {
        // Disable the user (update the status to 'disabled' or set a flag)
        $nowuser->status = 'enabled';
        $nowuser->save();

        return redirect()->route('settings.users.index')->with('success', 'User Activated successfully.');
    }
}
