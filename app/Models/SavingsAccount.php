<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingsAccount extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'savings_product_id', 'branch_id', 'account_name', 'balance', 'status', 'opened_at', 'last_transacted_at'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function savingsProduct()
    {
        return $this->belongsTo(SavingsProduct::class, 'savings_product_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
