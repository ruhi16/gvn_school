<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamShrenyPartFmPm extends Model
{
    protected $table = 'exam_shreny_part_fm_pms';

    protected $fillable = [
        'name',
        'description',
        'shreny_id',
        'exam_name_id',
        'exam_type_id',
        'exam_part_id',
        'exam_mode_id',
        'subject_id',
        'full_marks',
        'pass_marks',
        'time_alloted',
        'order_id',
        'school_id',
        'session_id',
        'is_active',
        'remarks',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
