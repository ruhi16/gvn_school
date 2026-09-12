<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentDb extends Model
{
    protected $fillable = [
        'name',
        'dp_img_ref',
        'gender',
        'fname',
        'mname',
        'dob',
        'dob_cert_img_ref',
        'aadhaar_id',
        'aadhaar_img_ref',
        'pen_id',
        'apper_id',
        'village',
        'post_office',
        'police_station',
        'district',
        'block',
        'pincode',
        'state',
        'nationality',
        'mobile_1',
        'mobile_2',
        'email',
        'adm_shreny_id',
        'adm_section_id',
        'order_id',
        'school_id',
        'session_id',
        'is_active',
        'remarks',
    ];

    protected $casts = [
        'dob' => 'date',
        'is_active' => 'boolean',
    ];

    public function classRecords(): HasMany
    {
        return $this->hasMany(StudentCr::class, 'studentdb_id');
    }
}
