<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;
    protected $fillable = [
        'loan_product_id',
        'client_id',
        'org_id',
        'loan_officer_id',
        'approval_officer_id',
        'disbursement_officer_id',
        'branch_id',
        'application_amount',
        'purpose',
        'application_date',
        'application_period',
        'appraisal_amount',
        'appraisal_period',
        'appraisal_date',
        'appraisal_comment',
        'file_link1',
        'file_link2',
        'file_link3',
        'file_link4',
        'file_link5',
        'file_link6',
        'file_link7',
        'file_link8',
        'approved_amount',
        'approved_period',
        'approved_date',
        'approved_interest_rate',
        'approved_comment',
        'defer_comment',
        'disbursement_date',       
        'voucher_number', 
        'paid_principal',
        'paid_interest',
        'paid_penalties',     
        'status',
    ];

    // Define relationships here if necessary
    public function loanProduct()
    {
        return $this->belongsTo(LoanProduct::class, 'loan_product_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }
}
