<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionArchive extends Model
{
    protected $fillable = [
        'name', 'description', 'exam_name_id', 'exam_type_id', 'exam_part_id',
        'shreny_id', 'section_id', 'subject_id', 'question_paper_pdf_ref',
        'question_paper_img_ref', 'question_paper_text_ref', 'order_id', 'school_id',
        'session_id', 'is_active', 'remarks', 'is_editable', 'is_deleted', 'is_finalized',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_editable' => 'boolean',
        'is_deleted' => 'boolean',
        'is_finalized' => 'boolean',
    ];

    public function shreny(): BelongsTo
    {
        return $this->belongsTo(Shreny::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function examName(): BelongsTo
    {
        return $this->belongsTo(ExamName::class);
    }

    public function examType(): BelongsTo
    {
        return $this->belongsTo(ExamType::class);
    }

    public function examPart(): BelongsTo
    {
        return $this->belongsTo(ExamPart::class);
    }
}
