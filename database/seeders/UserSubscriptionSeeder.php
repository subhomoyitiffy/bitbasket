<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserSubscription;

class UserSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserSubscription::insert([
            [
                'subscription_id'=> '1',
                'user_id'=> '2',
                'coupon_id'=> 0,
                'coupon_discount'=> '0.00',
                'coupon_code'=> '',
                'payable_amount'=> '100',
                'stripe_subscription_id'=> 'g5654654654v54654',
                'subscription_start'=> '2025-02-01',
                'subscription_end'=> '2026-01-31',
                'comment'=> '',
                'is_active'=> 1,
                'created_at'=> '2025-02-05 21:44:04.000',
                'updated_at'=> '2025-02-05 21:44:04.000',
            ]
        ]);
    }
}
