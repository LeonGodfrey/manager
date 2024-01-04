<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanProduct extends Model
{
    use HasFactory;
    protected $table = 'loan_products';

    protected $fillable = [
        'org_id',
        'name',
        'interest_method',
        'interest_rate',
        'payment_frequency',
        'penalty_rate',
        'grace_period',
        'charge_interest_grace_period',
        'arrears_maturity_period',
        'max_loan_period',
        'status',
    ];
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }
}
