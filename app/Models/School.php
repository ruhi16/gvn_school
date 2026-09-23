<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    protected $fillable = [
        'name',
        'dise_code',
        'udise_code',
        'school_type',
        'vill',
        'post_office',
        'police_station',
        'district',
        'block',
        'pincode',
        'is_active',
        'remarks',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function notices(): HasMany
    {
        return $this->hasMany(Notice::class);
    }
}
