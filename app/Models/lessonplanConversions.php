<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class lessonplanConversions extends Model
{
    protected $fillable = [
        'student_id',
        'lessonplan_id',
        'user_id',
        'is_group',
        'message'
    ];
}
