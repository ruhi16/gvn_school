<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamDateSchedule extends Model
{
    protected $fillable = [
        'name',
        'description',
        'exam_name_id',
        'exam_type_id',
        'exam_part_id',
        'exam_mode_id',
        'shreny_id',
        'section_id',
        'subject_id',
        'exam_date',
        'exam_half_id',
        'order_id',
        'school_id',
        'session_id',
        'is_active',
        'remarks',
        'is_finalized',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'is_active' => 'boolean',
        'is_finalized' => 'boolean',
    ];

    public function examName()
    {
        return $this->belongsTo(ExamName::class);
    }

    public function examType()
    {
        return $this->belongsTo(ExamType::class);
    }

    public function examPart()
    {
        return $this->belongsTo(ExamPart::class);
    }

    public function examMode()
    {
        return $this->belongsTo(ExamMode::class);
    }

    public function shreny()
    {
        return $this->belongsTo(Shreny::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function examHalf()
    {
        return $this->belongsTo(ExamHalf::class);
    }
}
