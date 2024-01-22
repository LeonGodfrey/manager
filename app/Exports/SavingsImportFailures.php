<?php

namespace App\Exports;

use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SavingsImportFailures implements FromCollection, WithHeadings
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
            'client_name',
            'savings_product_id',
            'account_name',
            'balance',
            'opened_at',
            'last_transacted_at',        
            'errors', // Add errors as the last column
        ];
    }

   
}
