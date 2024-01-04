<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\Account;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuperAdminController extends Controller
{
    //list all orgs
    public function index()
    {
       
        $organizations = Organization::all(); // Retrieve all organizations from the database
        return view('superadmin.index', compact('organizations'));
    }

    //Add new org
    public function create()
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        return view('superadmin.create-org', compact('organization','user'));
    }

    //store new org
    public function store(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'org_name' => ['required', 'max:255', 'unique:organizations,org_name'],
            'org_country' => 'required|max:255',
            'currency_code' => 'required|max:3',
            'incorporation_date' => 'required|date',
            'business_reg_no' => 'required|max:20',
            'manager_name' => 'required|max:255',
            'manager_contact' => 'required|max:20',
            'org_logo' => 'image|mimes:jpeg,png,jpg|max:2048'
            // Add validation rules for other fields here
        ]);
        //handle file upload
        if ($request->hasFile('org_logo')) {
            $validatedData['org_logo'] = $request->file('org_logo')->store('logos', 'public');
        }

        // Create a new organization
        $organization = new Organization();
        $organization->fill($validatedData);

        // If validation fails, redirect back to the form with the previously entered data
        if (!$validatedData) {
            return redirect()->back()->withInput();
        }
        //dd($validatedData);//debug
        $organization->save();

        return redirect()->route('super-admin.index')->with('success', 'Organization created successfully.');
    }

    //edit org
    public function edit(Organization $organization)
    {
        return view('superadmin.edit-org', compact('organization'));
    }

    //update org
    public function update(Request $request, Organization $organization)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'org_name' => ['required', 'max:255'],
            'org_country' => 'required|max:255',
            'currency_code' => 'required|max:3',
            'incorporation_date' => 'required|date',
            'business_reg_no' => 'required|max:20',
            'manager_name' => 'required|max:255',
            'manager_contact' => 'required|max:20',
            'org_logo' => 'image|mimes:jpeg,png,jpg|max:2048'
            // Add validation rules for other fields here
        ]);

        // Handle file upload
        if ($request->hasFile('org_logo')) {
            // Delete the old logo file
            if ($organization->org_logo) {
                Storage::delete('public/' . $organization->org_logo);
            }

            // Store the new logo file
            $validatedData['org_logo'] = $request->file('org_logo')->store('logos', 'public');
        }
        // Assign the validated data to the organization model
        $organization->org_logo = $validatedData['org_logo'];

        // Update the organization with the validated data
        $organization->update($validatedData);

        return redirect()->route('super-admin.index')->with('success', 'Organization updated successfully.');
    }

    //-------------branches-------------------
    //all branches
    public function branches()
    {
        $branches = Branch::all();
        return view('superadmin.branches.index', compact('branches'));
    }
    //create branch form
    public function createBranch()
    {
        $organizations = Organization::all(); // Fetch available organizations
        return view('superadmin.branches.create', compact('organizations'));
    }

    public function storeBranch(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'org_id' => 'required',
            'branch_name' => 'required|max:255',
            'branch_phone' => 'required|max:13',
            'branch_email' => 'required|email', // Use the 'email' rule for email validation
            'branch_prefix' => 'required|max:3',
            'branch_street_address' => 'required|max:255',
            'branch_city' => 'required|max:255',
            'branch_district' => 'required|max:255',
            'branch_postcode' => 'required|max:255',
            'status' => 'required|max:255'
            // Add validation rules for other fields here
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
        return redirect()->route('super-admin.branches.index')->with('success', 'Branch created successfully.');
    }


    public function editBranch(Branch $branch)
    {
        return view('superadmin.branches.edit', compact('branch'));
    }

    public function updateBranch(Request $request, Branch $branch)
    {
        // Validate the input data
        $validatedData = $request->validate([
            //'org_id' => 'required',
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

        return redirect()->route('super-admin.branches.index')->with('success', 'Branch updated successfully.');
    }

    //------------------users------------------------
    public function users()
    {
        $users = User::all();
        return view('superadmin.users.index', compact('users'));
    }

    public function createUser()
    {
        $organizations = Organization::all(); // Fetch available organizations
        $branches = Branch::all(); // Fetch available branches
        return view('superadmin.users.create', compact('organizations', 'branches'));
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
    return redirect()->route('super-admin.users.index')->with('success', 'user created successfully.');
    }

    public function editUser(User $user)
    {
        $branches = Branch::where('org_id', $user->org_id)->get(); // Filter branches by organization
        return view('superadmin.users.edit', compact('user', 'branches'));
    }

    public function updateUser(Request $request, User $user)
    {
         // Validate the input data
         $validatedData = $request->validate([            
            'branch_id' => 'required',
            'name' => 'required|max:255',
            'user_name' => ['required', 'max:255'],
            'user_phone' => 'required|max:13',
            'email' =>  ['required', 'email'], // Use the 'email' rule for email validation
            'password' => 'required|confirmed|min:8'
        ]);

        // Update the user with the validated data
        $user->update($validatedData);

        return redirect()->route('super-admin.users.index')->with('success', 'User updated successfully.');
    }
}
