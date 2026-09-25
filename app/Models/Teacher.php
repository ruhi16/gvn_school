<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 


class Teacher extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'desccription', 'email', 'mobile', 'dob', 'gender', 'prof_img_ref', 'high_qual', 'high_qual_subject', 'high_qual_certificate_img_ref', 'prof_qual', 'prof_qual_subject', 'prof_qual_certificate_img_ref', 'vill', 'post_office', 'police_station', 'district', 'block', 'pincode', 'order_id', 'school_id', 'session_id', 'is_active', 'remarks'];

    protected $casts = ['is_active' => 'boolean'];
}
