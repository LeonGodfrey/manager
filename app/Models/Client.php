<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'org_id',
        'branch_id',
        'client_number',
        'ipps_number',
        'surname',
        'given_name',
        'gender',
        'dob',
        'registration_date',
        'phone',
        'alt_phone',
        'email_address',
        'street_address',
        'city',
        'district',
        'county',
        'sub_county',
        'parish',
        'village',
        'home_district',
        'home_village',
        'kin_name',
        'kin_phone',
        'father_name',
        'father_phone',
        'mother_name',
        'mother_phone',
        'id_photo',
        'id_number',
        'profile_photo',
        'signature',
        'other_file',
        'status',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
