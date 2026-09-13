<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamMarksEntry extends Model
{
    protected $fillable = [
        'name', 'description', 'shreny_id', 'section_id', 'student_cr_id',
        'exam_name_id', 'exam_type_id', 'exam_part_id', 'subject_id',
        'obtained_marks', 'order_id', 'school_id', 'session_id', 'is_active',
        'is_finalized', 'is_issued', 'remarks',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_finalized' => 'boolean',
        'is_issued' => 'boolean',
    ];
}
