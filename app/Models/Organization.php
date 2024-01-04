<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [        
        'org_name',
        'org_country',
        'currency_code',
        'incorporation_date',
        'business_reg_no', 
        'manager_name',
        'manager_contact',  
        'org_log'
    ];
    }