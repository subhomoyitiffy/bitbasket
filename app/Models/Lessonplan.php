<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lessonplan extends Model
{
    use SoftDeletes;

    /**
     * Get the details associated with the user.
    */
    public function students(): BelongsToMany
    {
        return $this->BelongsToMany(Student::class, 'lessonplan_students',  'lessonplan_id', 'student_id');
    }

    /**
     * Get the details associated with the user.
    */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

}
