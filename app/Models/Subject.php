<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name', 'short_name', 'desc', 'order_id', 'school_id', 'session_id', 'is_active', 'remarks'];

    protected $casts = ['is_active' => 'boolean'];
}
