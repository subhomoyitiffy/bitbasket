<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'duration',
        'price',
        'status',
        'stripe_price_id',
        'no_of_users',
        'no_of_teachers'
    ];
}
