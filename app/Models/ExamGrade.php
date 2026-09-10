<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamGrade extends Model
{
    protected $fillable = ['name', 'description', 'order_id', 'school_id', 'is_active', 'remarks'];

    protected $casts = ['is_active' => 'boolean'];
}
