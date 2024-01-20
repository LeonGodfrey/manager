<?php

namespace App\Exports;

use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientsImportFailures implements FromCollection, WithHeadings
{
    use Exportable;

    protected $failures;

    public function __construct($failures)
    {
        $this->failures = $failures;
    }

    public function collection()
    {
       
        $rows = [];

        foreach ($this->failures as $failure) {
            $rowData = $failure->values();

            // Append errors at the end of the row
            $rowData[] = implode(', ', $failure->errors());
            $rows[] = $rowData;
        }

        return collect([$rows]);
    }
    
    public function headings(): array
    {
        return [
            'branch_id',
            'client_number',
            'surname',
            'given_name',
            'dob',
            'gender',
            'phone',
            'email_address',
            'street_address',
            'city',
            'district',
            'village',
            'kin_name',
            'kin_phone',
            'registration_date',
            'errors', // Add errors as the last column
        ];
    }

   
}
