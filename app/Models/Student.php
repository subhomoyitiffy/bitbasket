<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    /**
     * Get the details associated with the user.
    */
    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class, 'institute_id');
    }
}
