<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $fillable = [
        'org_id',
        'branch_name',
        'branch_phone',
        'branch_email',
        'branch_prefix',
        'branch_street_address',
        'branch_city',
        'branch_district',
        'branch_postcode',
        'status'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }
}
