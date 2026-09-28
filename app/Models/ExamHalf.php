<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamHalf extends Model
{
    protected $fillable = [
        'name',
        'description',
        'start_time',
        'end_time',
        'active_exam_days',
        'order_id',
        'school_id',
        'session_id',
        'is_active',
        'remarks',
        'is_editable',
        'is_deleted',
        'is_finalized',
    ];

    protected $casts = [
        'active_exam_days' => 'array',
        'is_active' => 'boolean',
        'is_editable' => 'boolean',
        'is_deleted' => 'boolean',
        'is_finalized' => 'boolean',
    ];
}
