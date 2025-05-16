<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserDetails;

class UserDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserDetails::insert([
            [
                'user_id'=> '2',
                'country'=> 'UAE',
                'first_name'=> 'Test',
                'last_name'=> 'User',
                'email'=> 'test@example.com',
                'country_code'=> '229',
                'phone'=> '9876543210',
                'city_id'=> '5',
                'emarati'=> 'ttrt rtre',
                'business_license'=> 'g3463463463456',
                'tax_registration_number'=> 'h74554754754',
                'company_type'=> 'LLC',
                'employer_identification_no'=> 'g645645645',
                'stripe_cust_id'=> 'h767457547v546456547b68n567856',
                'created_at'=> '2025-02-05 21:44:04.000',
                'updated_at'=> '2025-02-05 21:44:04.000',
            ]
        ]);
    }
}
