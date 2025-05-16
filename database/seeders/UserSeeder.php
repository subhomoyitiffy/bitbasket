<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'role_id'=> 1,
                'parent_id'=> 0,
                'name' => 'Admin User',
                'email' => 'admin@bitbasket.com',
                'password' => Hash::make('password'),
                'country_code' => '+91',
                'phone' => '7894561237',
                'status'    => 1
            ],
            [
                'role_id'=> 2,
                'parent_id'=> 0,
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'country_code' => '+91',
                'phone' => '8981374269',
                'status'    => 1
            ]
        ]);
    }
}
