<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name', 'description', 'floor', 'room_type', 'room_status', 'room_condition',
        'no_of_benches', 'no_of_students_per_bench', 'no_of_students_total',
        'school_id', 'session_id', 'is_active', 'remarks',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
