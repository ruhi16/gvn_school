<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherSubjects extends Model
{
    protected $fillable = [
        'teacher_id', 'subject_id', 'order_id', 'subject_type', 'session_id', 'school_id',
        'is_active', 'remarks', 'is_editable', 'is_deleted', 'is_finalized',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_editable' => 'boolean',
        'is_deleted' => 'boolean',
        'is_finalized' => 'boolean',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
