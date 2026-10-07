<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShrenyTeachers extends Model
{
    protected $fillable = [
        'shreny_id', 'teacher_id', 'order_id', 'teacher_type', 'school_id', 'session_id',
        'is_active', 'remarks', 'is_editable', 'is_deleted', 'is_finalized',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_editable' => 'boolean',
        'is_deleted' => 'boolean',
        'is_finalized' => 'boolean',
    ];

    public function shreny()
    {
        return $this->belongsTo(Shreny::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
