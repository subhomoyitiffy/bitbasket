<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{

    protected $fillable = [
        'subscription_id',
        'user_id',
        'coupon_id',
        'coupon_discount',
        'coupon_code',
        'payable_amount',
        'stripe_subscription_id',
        'subscription_start',
        'subscription_end',
        'comment',
        'is_active'
    ];

}
