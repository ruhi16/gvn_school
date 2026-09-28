<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoGallery extends Model
{
    protected $fillable = [
        'name', 'description', 'category_id', 'category_name', 'category_slug',
        'category_description', 'image_path', 'image_caption', 'image_alt_text',
        'is_featured', 'order_id', 'school_id', 'session_id', 'is_active', 'remarks',
        'is_editable', 'is_deleted', 'is_finalized',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'is_editable' => 'boolean',
        'is_deleted' => 'boolean',
        'is_finalized' => 'boolean',
    ];
}
