<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'number',
        'balance',
        'type',
        'subtype',
        'status',
        'org_id',
        'user_id',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
