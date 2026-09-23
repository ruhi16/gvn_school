<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notice extends Model
{
    protected $fillable = [
        'title',
        'description',
        'upload_dt',
        'active_dt',
        'expiry_dt',
        'uploaded_by',
        'notice_img_ref',
        'notice_pdf_ref',
        'is_finalized',
        'is_issued',
        'order_id',
        'school_id',
        'session_id',
        'is_active',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'upload_dt' => 'date',
            'active_dt' => 'date',
            'expiry_dt' => 'date',
            'is_finalized' => 'boolean',
            'is_issued' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
