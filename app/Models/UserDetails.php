<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDetails extends Model
{
    //

    protected $fillable = [
        'user_id',
        'country',
        'first_name',
        'last_name',
        'email',
        'country_code',
        'phone',
        'city_id',
        'emarati',
        'business_license',
        'tax_registration_number',
        'company_type',
        'employer_identification_no'
    ];

    /**
     * Get the details associated with the user.
    */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'city_id');
    }

}
