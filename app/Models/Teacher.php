<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 


class Teacher extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'desc', 'email', 'mobile', 'high_qual', 'high_qual_subject', 'prof_qual', 'prof_qual_subject', 'vill', 'post_office', 'police_station', 'district', 'block', 'pincode', 'order_id', 'school_id', 'session_id', 'is_active', 'remarks'];

    protected $casts = ['is_active' => 'boolean'];
}
