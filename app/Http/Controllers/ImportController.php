<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Import;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Imports\ClientsImport;
use App\Models\SavingsProduct;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientsImportFailures;


class ImportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $organization = Organization::find($user->org_id);
        $imports = Import::where('org_id', $user->org_id)->orderBy('created_at', 'desc')->get();
        return view('organization.imports.index', compact('organization', 'user', 'imports'));
    }

    
    public function client_create()
    {
        $user = Auth::user();
        $organization = Organization::find($user->org_id);
        $branches = Branch::where('org_id', $user->org_id)->get();
        return view('organization.imports.clients', compact('organization', 'branches', 'user'));
    }


    public function clients_template()
    {
        $filePath = public_path('templates/clients.csv');

        return response()->download($filePath, 'clients.csv');
    }

   
    public function client_store(Request $request)
    {
        $request->validate([
            'upload_file' => 'required|mimes:csv,txt',
            'description' => 'nullable|string'
        ]);

        $file = $request->file('upload_file')->store('public/imports/clients');

        // Create a new import record
        $importDetails = new Import([
            'org_id' => auth()->user()->organization->id,
            'type' => 'clients', // Adjust as needed
            'description' => $request->description,
            'import_file' => $file, //link to the uploadedfile
            'status' => 'processing',
        ]);

        $importDetails->save();

        // Process the import
        $importStatus = 'processed';
        $importMessage = 'Data imported successfully.';

        try {
            $fileimport = new ClientsImport;
            $fileimport->import($file);//import file

        } catch (\Exception $e) {
            $importStatus = 'failed';
            // $importMessage = 'Error during import: ' . $e->getMessage();
            $importMessage = 'Failed, please try again!';
            $errorFile = '';
        }

        // Handle failed rows
        $failures = $fileimport->failures();

        if ($failures->isNotEmpty()) {

            $errorFile = "public/imports/error_files/clients_error_" . time() . ".csv";
            Excel::store(new ClientsImportFailures($failures), $errorFile);

            $importMessage = 'Check Errors file';
        }

        // Update the import record with the status and message
        $importDetails->update([
            'status' => $importStatus,
            'message' => $importMessage,
            'error_file' => $errorFile,
        ]);

        // Return success message with import status and link to download error file
        return redirect()->route('settings.data-imports.index')->with('success', $importMessage);
    }

    public function savings_accounts_create()
    {
        $user = Auth::user();
        $organization = Organization::find($user->org_id);
        $branches = Branch::where('org_id', $user->org_id)->get();
        $savings_products = SavingsProduct::where('org_id', $user->org_id)->get();
        return view('organization.imports.savings-accounts', compact('organization', 'branches', 'user', 'savings_products'));
    }
        
    public function savings_accounts_template()
    {
        $filePath = public_path('templates/savings_accounts.csv');
        return response()->download($filePath, 'savings_accounts.csv');
    }

   
    public function savings_accounts_store(Request $request)
    {
        $request->validate([
            'upload_file' => 'required|mimes:csv,txt',
            'description' => 'nullable|string'
        ]);

        $file = $request->file('upload_file')->store('public/imports/savingsAccounts');

        // Create a new import record
        $importDetails = new Import([
            'org_id' => auth()->user()->organization->id,
            'type' => 'Savings Accounts', // Adjust as needed
            'description' => $request->description,
            'import_file' => $file, //link to the uploadedfile
            'status' => 'processing',
        ]);

        $importDetails->save();

        // Process the import
        $importStatus = 'processed';
        $importMessage = 'Data imported successfully.';

        try {
            $fileimport = new ClientsImport;
            $fileimport->import($file);//import file

        } catch (\Exception $e) {
            $importStatus = 'failed';
            // $importMessage = 'Error during import: ' . $e->getMessage();
            $importMessage = 'Failed, please try again!';
            $errorFile = '';
        }

        // Handle failed rows
        $failures = $fileimport->failures();

        if ($failures->isNotEmpty()) {

            $errorFile = "public/imports/error_files/savings_accounts_error_" . time() . ".csv";
            Excel::store(new ClientsImportFailures($failures), $errorFile);

            $importMessage = 'Check Errors file';
        }

        // Update the import record with the status and message
        $importDetails->update([
            'status' => $importStatus,
            'message' => $importMessage,
            'error_file' => $errorFile,
        ]);

        // Return success message with import status and link to download error file
        return redirect()->route('settings.data-imports.index')->with('success', $importMessage);
    }


   
}
