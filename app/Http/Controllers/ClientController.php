<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Loan;
use App\Models\Branch;
use App\Models\Client;
use App\Models\Transaction;
use App\Models\Organization;
use App\Models\SavingsAccount;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);

        $search = $request->input('search');
        $searchWords = explode(' ', $search); // Get the search input

        $query = Client::where('org_id', $user->org_id);

        if ($user->branch->branch_name !== 'Head Office') {
            // User is not in the 'Head Office' branch, so return clients for the specific branch
            $query->where('branch_id', $user->branch_id);
        }

        if (!empty($search)) {
            // If search input is not empty, apply the search filter
            $query->where(function ($query) use ($searchWords) {
                foreach ($searchWords as $word) {
                    $query->orWhere('client_number', 'like', "%$word%")
                        ->orWhere('surname', 'like', "%$word%")
                        ->orWhere('given_name', 'like', "%$word%");
                }
            });
        }

        // Sort clients by updated_at in descending order
        $clients = $query->orderBy('updated_at', 'desc')->paginate(20);

        return view('clients.index', compact('clients', 'user', 'organization'));
    }

    public function create()
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        // Retrieve all branches belonging to the organization
        $branches = Branch::where('org_id', $user->org_id)->get();
        return view('clients.create', compact('user', 'organization', 'branches'));
    }

    public function client(Client $client)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        //get age
        $dob = Carbon::parse($client->dob);
        $age = $dob->age;
        //loan
        $loans = Loan::where('client_id', $client->id)
            ->whereIn('status',[ 'pending_appraisal', 'pending_approval', 'approved', 'disbursed', 'deferred'])
            ->get();

        // client fees
        $transactions = Transaction::where('org_id', $user->org_id)
            ->where('type', 'Other Income')
            ->where('client_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);


        $details = [];
        foreach ($transactions as $transaction) {
            $transactionDetails = TransactionDetail::where('transaction_id', $transaction->id)->get();
            $details[$transaction->id] = $transactionDetails;
            }

        $savings_accounts = SavingsAccount::where('client_id', $client->id)
        ->where('status', 'active')        
        ->orderBy('created_at', 'desc')
        ->get();

        return view('clients.client', compact('client', 'user', 'organization', 'age', 'loans', 'transactions', 'details', 'savings_accounts'));
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'org_id' => 'required|exists:organizations,id',
            'branch_id' => 'required|exists:branches,id',
            'client_number' => 'required|unique:clients,client_number',
            'ipps_number' => 'nullable',
            'surname' => 'required|max:255',
            'given_name' => 'required|max:255',
            'gender' => 'required|in:Male,Female', // Adjust the gender values as needed
            'dob' => 'required|date',
            'registration_date' => 'required|date', //added
            'phone' => 'required|max:13',
            'alt_phone' => 'nullable',
            'email_address' => 'nullable|email',
            'street_address' => 'nullable|max:255',
            'city' => 'required|max:255',
            'district' => 'required|max:255',
            'county' => 'nullable',
            'sub_county' => 'nullable',
            'parish' => 'nullable',
            'village' => 'nullable',
            'home_district' => 'nullable',
            'home_village' => 'nullable',
            'kin_name' => 'required|max:255',
            'kin_phone' => 'nullable',
            'father_name' => 'nullable',
            'father_phone' => 'nullable',
            'mother_name' => 'nullable',
            'mother_phone' => 'nullable',
            'id_photo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'id_number' => 'nullable|max:14|max:14',
            'profile_photo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'signature' => 'image|mimes:jpeg,png,jpg|max:2048',
            'other_file' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);
        //handle file id_photo
        if ($request->hasFile('id_photo')) {
            $validatedData['id_photo'] = $request->file('id_photo')->store('clients', 'public');
            
        }
        //handle file profile_photo
        if ($request->hasFile('profile_photo')) {
            $validatedData['profile_photo'] = $request->file('profile_photo')->store('clients', 'public');
        }
        //handle file signature
        if ($request->hasFile('signature')) {
            $validatedData['signature'] = $request->file('signature')->store('clients', 'public');
        }
        //handle file other_file
        if ($request->hasFile('other_file')) {
            $validatedData['other_file'] = $request->file('other_file')->store('clients', 'public');
        }

        $client = new Client();
        $client->fill($validatedData);
        $client->save();

        return redirect()->route('clients.index')->with('success', 'Client Added successfully.');
    }

    public function edit(Client $client)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        // Retrieve all branches belonging to the organization
        $branches = Branch::where('org_id', $user->org_id)->get();
        return view('clients.edit', compact('client', 'user', 'organization', 'branches'));
    }

    public function detail(Client $client)
    {
        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);
        // Retrieve all branches belonging to the organization
        $branches = Branch::where('org_id', $user->org_id)->get();
        return view('clients.detail', compact('client', 'user', 'organization', 'branches'));
    }

    public function update(Request $request, Client $client)
    {
        $validatedData = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'client_number' => 'required|unique:clients,client_number,' . $client->id, //exclude user id from the check
            'ipps_number' => 'nullable',
            'surname' => 'required|max:255',
            'given_name' => 'required|max:255',
            'gender' => 'required|in:Male,Female', // Adjust the gender values as needed
            'dob' => 'required|date',
            'registration_date' => 'required|date', //added
            'phone' => 'required|max:13',
            'alt_phone' => 'nullable',
            'email_address' => 'nullable|email',
            'street_address' => 'nullable|max:255',
            'city' => 'required|max:255',
            'district' => 'required|max:255',
            'county' => 'nullable',
            'sub_county' => 'nullable',
            'parish' => 'nullable',
            'village' => 'nullable',
            'home_district' => 'nullable',
            'home_village' => 'nullable',
            'kin_name' => 'required|max:255',
            'kin_phone' => 'nullable',
            'father_name' => 'nullable',
            'father_phone' => 'nullable',
            'mother_name' => 'nullable',
            'mother_phone' => 'nullable',
            'id_photo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'id_number' => 'nullable',
            'profile_photo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'signature' => 'image|mimes:jpeg,png,jpg|max:2048',
            'other_file' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        //handle file id_photo
        if ($request->hasFile('id_photo')) {
            // Delete the old id_photo file
            if ($client->id_photo) {
                Storage::delete('public/' . $client->id_photo);
            }
            $validatedData['id_photo'] = $request->file('id_photo')->store('clients', 'public');
        }
        //handle file profile_photo
        if ($request->hasFile('profile_photo')) {
            // Delete the old profile_photo file
            if ($client->profile_photo) {
                Storage::delete('public/' . $client->profile_photo);
            }
            $validatedData['profile_photo'] = $request->file('profile_photo')->store('clients', 'public');
        }
        //handle file signature
        if ($request->hasFile('signature')) {
            // Delete the old signature file
            if ($client->signature) {
                Storage::delete('public/' . $client->signature);
            }
            $validatedData['signature'] = $request->file('signature')->store('clients', 'public');
        }
        //handle file other_file
        if ($request->hasFile('other_file')) {
            // Delete the old other_file file
            if ($client->other_file) {
                Storage::delete('public/' . $client->other_file);
            }
            $validatedData['other_file'] = $request->file('other_file')->store('clients', 'public');
        }
        // Update the client with the validated data
        $client->update($validatedData);

        // Retrieve the authenticated user
        $user = Auth::user();
        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);

        $dob = Carbon::parse($client->dob);
        $age = $dob->age;
        return redirect()->route('clients.client', compact('client', 'user', 'organization', 'age'))->with('success', 'Client Information updated successfully.');
    }

    public function destroy(Client $client)
    {
        // Delete the client
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }
}
