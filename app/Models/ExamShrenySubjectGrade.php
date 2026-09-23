<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamShrenySubjectGrade extends Model
{
    protected $table = 'exam_shreny_subject_grades';

    protected $fillable = [
        'name',
        'description',
        'exam_name_id',
        'shreny_id',
        'subject_type_id',
        'exam_grade_id',
        'order_id',
        'school_id',
        'session_id',
        'is_active',
        'remarks',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
