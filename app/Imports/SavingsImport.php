<?php
namespace App\Imports;

use App\Models\Client;
//use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\SavingsAccount;
use App\Models\TransactionDetail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SavingsImport implements 
ToCollection,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    WithChunkReading,
    SkipsOnError,
    WithBatchInserts
    {
    
    use Importable, SkipsFailures, SkipsErrors;

    
        public function collection(Collection $rows)
        {
            foreach ($rows as $row) 
            {
                $client = Client::where('client_number', $row['client_number'] )->first();
                $client_id = $client->id;
                DB::beginTransaction();
                // Create a new Savings account
                $savings_account = new SavingsAccount();
                $savings_account->fill([
                    'org_id' => auth()->user()->organization->id,
                    'branch_id' => $row['branch_id'],
                    'client_id' =>  $client_id,
                    'savings_product_id' =>  $row['savings_product_id'],
                    'account_name' =>  $row['account_name'],
                    'balance' =>  $row['balance'],
                    'opened_at' =>  $row['opened_at'],
                    'last_transacted_at' =>  $row['last_transacted_at'],
                    'status' =>  'active',
                ]);
                //dd($savings_account);
                $savings_account->save();
    
                // Create a new Transaction
                $transaction = new Transaction();
                $transaction->fill([
                    'org_id' =>  auth()->user()->organization->id,
                    'branch_id' =>  $savings_account->branch_id,
                    'client_id' =>  $savings_account->client_id,
                    'savings_account_id' => $savings_account->id,
                    'user_id' =>  $savings_account->user_id,
                    'type' =>  'Opening Balance',
                    'amount' =>  $savings_account->balance,
                    'date' =>  $savings_account->last_transacted_at,
                ]);
               // dd($transaction);
                $transaction->save();
    
                $cash_account = Account::where('name', 'Import Suspense')->first();
                // Create transaction details - debit
                $detail1 = new TransactionDetail();
                $detail1->fill([
                    'org_id' => auth()->user()->organization->id,
                    'transaction_id' => $transaction->id,
                    'account_id' => $cash_account->id,
                    'amount' => $transaction->amount,
                    'type' => 'Opening Balance',
                    'debit_credit' => 'Debit',
                ]);
                $detail1->save();
    
                $cash_account->balance += $transaction->amount; // debit an asset account
                $cash_account->save();
    
                $savings_product_account = Account::where('savings_product_id', $savings_account->savings_product_id)->first();
                // Create transaction details - credit
                $detail2 = new TransactionDetail();
                $detail2->fill([
                    'org_id' => auth()->user()->organization->id,
                    'transaction_id' => $transaction->id,
                    'account_id' => $savings_product_account->id,
                    'amount' => $transaction->amount,
                    'type' => 'Opening Balance',
                    'debit_credit' => 'Credit',
                ]);
                $detail2->save();
    
                $savings_product_account->balance += $transaction->amount; // credit a laibility account
                $savings_product_account->save();
    
                // Commit the database transaction
                DB::commit();
            }
        }
        
       

    public function rules(): array
    {
        return [
            
            '*.branch_id' => ['required', 'exists:branches,id'],
            '*.client_number' => ['required', 'exists:clients,client_number'],
            '*.client_name' => ['required', 'max:255'],
            '*.savings_product_id' => ['required', 'exists:savings_products,id'],
            '*.opened_at' => ['required', 'date'],           
            '*.balance' => ['required','numeric','min:0'],
            '*.account_name' => ['nullable', 'max:255'],               
            '*.last_transacted_at' => ['required', 'date'],
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
