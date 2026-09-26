<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamHalf extends Model
{
    protected $fillable = [
        'name',
        'description',
        'exam_name_id',
        'exam_type_id',
        'exam_part_id',
        'start_time',
        'end_time',
        'active_exam_days',
        'order_id',
        'school_id',
        'session_id',
        'is_active',
        'remarks',
    ];

    protected $casts = [
        'active_exam_days' => 'array',
        'is_active' => 'boolean',
    ];
}
