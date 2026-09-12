<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentCr extends Model
{
    protected $fillable = [
        'studentdb_id',
        'curr_shreny_id',
        'curr_section_id',
        'curr_roll_no',
        'school_id',
        'session_id',
        'order_id',
        'is_promoted',
        'is_active',
        'remarks',
    ];

    protected $casts = [
        'is_promoted' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentDb::class, 'studentdb_id');
    }

    public function shreny(): BelongsTo
    {
        return $this->belongsTo(Shreny::class, 'curr_shreny_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'curr_section_id');
    }
}
