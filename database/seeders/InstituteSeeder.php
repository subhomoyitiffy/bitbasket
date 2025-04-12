<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Institute;

class InstituteSeeder extends Seeder
{
    /**
     * Run the database seeds.
    */
    public function run(): void
    {
        Institute::insert([
            [
                'name'=> 'Amity University Dubai',
            ],
            [
                'name'=> 'Al Ain University',
            ],
            [
                'name'=> 'United Arab Emirates University',
            ],
            [
                'name'=> 'University of Texas',
            ],
            [
                'name'=> 'Abu Dhabi University',
            ]
        ]);
    }
}
