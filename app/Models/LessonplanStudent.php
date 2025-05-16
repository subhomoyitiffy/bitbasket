<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonplanStudent extends Model
{
    protected $fillable = [
        'student_id',
        'lessonplan_id'
    ];
}
