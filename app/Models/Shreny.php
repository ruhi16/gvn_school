<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shreny extends Model
{
    protected $fillable = ['name', 'desc', 'order_id', 'school_id', 'session_id', 'is_active', 'remarks'];

    protected $casts = ['is_active' => 'boolean'];
}
