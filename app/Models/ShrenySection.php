<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShrenySection extends Model
{
    protected $fillable = ['shreny_id', 'section_id', 'order_id', 'school_id', 'session_id', 'is_active', 'remarks'];

    protected $casts = ['is_active' => 'boolean'];
}
