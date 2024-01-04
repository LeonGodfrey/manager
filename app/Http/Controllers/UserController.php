<?php

namespace App\Http\Controllers;


use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    
    //Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('user_name', 'password');
    
        if (Auth::attempt($credentials)) {
            // Check if the user belongs to the selected organization
            $user = Auth::user();
            
                // Check if the user is enabled
                if ($user->status === 'enabled') {
                    // User is authenticated, belongs to the selected organization, and is enabled
                    return redirect()->route('dashboard');
                } else {
                    Auth::logout(); // Log the user out if they are not enabled
                }
           
        }
    
        return redirect()->route('login')->with('error', 'Invalid credentials');
    }
    

    //dashboard
    public function dashboard()
    {
        $user = Auth::user();
        $organization = Organization::find($user->org_id);

        return view('dashboard', compact('organization', 'user'));
    }

    //logout
    public function logout(Request $request)
    {
        Auth::logout();

        // Clear the user's session
        $request->session()->invalidate();

        return redirect()->route('login');
    }

    // Display the Edit Profile page
    public function editProfile()
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Get the organization associated with the user
        $organization = Organization::find($user->org_id);

        return view('user.edit-profile', compact('organization', 'user'));
    }

    // Update the user's password
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Validate the request data
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        // Check if the old password is correct
        if (!Hash::check($request->input('old_password'), $user->password)) {
            return redirect()->route('user.edit-profile')->with('error', 'Old password is incorrect.');
        }

        // Update the user's password
        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return redirect()->route('user.edit-profile')->with('success', 'Password updated successfully.');
    }
}
