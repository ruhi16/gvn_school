<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamRoom extends Model
{
    protected $fillable = [
        'exam_name_id', 'exam_type_id', 'exam_part_id', 'shreny_id', 'section_id',
        'room_id', 'no_of_students_per_bench', 'roll_no_range_start', 'roll_no_range_end',
        'order_id', 'school_id', 'session_id', 'is_active', 'is_finalized', 'remarks',
    ];

    protected $casts = ['is_active' => 'boolean', 'is_finalized' => 'boolean'];

    public function room(): BelongsTo { return $this->belongsTo(Room::class); }
    public function shreny(): BelongsTo { return $this->belongsTo(Shreny::class); }
    public function section(): BelongsTo { return $this->belongsTo(Section::class); }
}
