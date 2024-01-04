<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingsProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'org_id',
        'saving_product_type',
        'name',
        'opening_balance',
        'min_balance',
        'deposit_fee',
        'withdrawal_fee',
        'monthly_fee',
        'interest_rate',
        'penalty_rate',
        'interest_frequency',
    ];

    // Define a relationship between SavingsProduct and Organization
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }
}