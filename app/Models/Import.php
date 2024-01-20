<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory;
    protected $fillable = [
        'org_id',
        'type',
        'description',
        'import_file',
        'status',
        'message',
        'error_file',
       
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }
}
