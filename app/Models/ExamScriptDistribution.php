<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamScriptDistribution extends Model
{
    protected $fillable = [
        'name', 'description', 'exam_name_id', 'exam_type_id', 'exam_part_id',
        'shreny_id', 'section_id', 'subject_id', 'teacher_id', 'allotted_date',
        'submited_date', 'is_finalized', 'is_issued', 'order_id', 'school_id',
        'session_id', 'is_active', 'remarks',
    ];

    protected $casts = [
        'allotted_date' => 'date',
        'submited_date' => 'date',
        'is_finalized' => 'boolean',
        'is_issued' => 'boolean',
        'is_active' => 'boolean',
    ];
}
