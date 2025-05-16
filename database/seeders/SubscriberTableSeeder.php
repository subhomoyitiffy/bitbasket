<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subscriber;

class SubscriberTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Subscriber::insert([
            [
                'email'=> 'a@test.com',
                'status'    => 1
            ],
            [
                'email'=> 'b@test.com',
                'status'    => 1
            ],
            [
                'email'=> 'c@test.com',
                'status'    => 1
            ],
            [
                'email'=> 'd@test.com',
                'status'    => 1
            ],
            [
                'email'=> 'e@test.com',
                'status'    => 1
            ]
        ]);
    }
}
