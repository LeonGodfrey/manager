<?php
namespace App\Imports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\ToModel;

class ClientsImport implements 
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    WithChunkReading,
    SkipsOnError,
    WithBatchInserts
    {
    
    use Importable, SkipsFailures, SkipsErrors;

   
    public function model(array $row)
    {
        return new Client([
                'org_id' => auth()->user()->organization->id,
                'branch_id' => $row['branch_id'],
                'client_number' => $row['client_number'],         
                'surname' => $row['surname'],
                'given_name' => $row['given_name'],
                'dob' => $row['dob'],
                'gender' => $row['gender'],
                'phone' => str_replace(" ", "",$row['phone']),
                'email_address' => $row['email_address'],
                'street_address' => $row['street_address'],
                'city' => $row['city'],
                'district' => $row['district'],            
                'village' => $row['village'],
                'kin_name' => $row['kin_name'],
                'kin_phone' => str_replace(" ", "",$row['kin_phone']),
                'registration_date' => $row['registration_date'],
            ]);
        
    }

    public function rules(): array
    {
        return [
            
            '*.branch_id' => ['required', 'exists:branches,id'],
            '*.client_number' => ['required', 'unique:clients,client_number'],
            '*.surname' => ['required', 'max:255'],
            '*.given_name' => ['required', 'max:255'],
            '*.dob' => ['required', 'date'],
            '*.gender' => ['required', 'in:Male,Female'],
            '*.phone' => ['required', 'max:14'],          
            '*.email_address' => ['nullable', 'email', 'unique:clients,email_address'],
            '*.street_address' => ['nullable', 'max:255'],
            '*.city' => ['required', 'max:255'],
            '*.district' => ['required', 'max:255'],         
            '*.village' => ['nullable', 'max:255'],
            '*.kin_name' => ['required', 'max:255'],     
            '*.kin_phone' => ['nullable', 'max:14'],    
            '*.registration_date' => ['required', 'date'],
        ];
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
