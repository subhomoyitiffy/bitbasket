<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
    */
    public function run(): void
    {
        Student::insert([
            [
                'institute_id'=> '1',
                'first_name' => 'Mr',
                'last_name' => 'Shyam',
                'work_email' => 'shyam@gmail.com',
                'phone'    => '0000000000',
                'status'    => 1,
            ],
            [
                'institute_id'=> '2',
                'first_name' => 'Mr',
                'last_name' => 'Arnold',
                'work_email' => 'arnold@gmail.com',
                'phone'    => '0000000000',
                'status'    => 1,
            ],
            [
                'institute_id'=> '3',
                'first_name' => 'Mr',
                'last_name' => 'Amitabh',
                'work_email' => 'amitabh@gmail.com',
                'phone'    => '0000000000',
                'status'    => 1,
            ],
            [
                'institute_id'=> '2',
                'first_name' => 'Mr',
                'last_name' => 'Kunal',
                'work_email' => 'kunal@gmail.com',
                'phone'    => '0000000000',
                'status'    => 1,
            ],
            [
                'institute_id'=> '3',
                'first_name' => 'Mr',
                'last_name' => 'Subham',
                'work_email' => 'subham@gmail.com',
                'phone'    => '0000000000',
                'status'    => 1,
            ],
            [
                'institute_id'=> '4',
                'first_name' => 'Mr',
                'last_name' => 'Danyal',
                'work_email' => 'danyal@gmail.com',
                'phone'    => '0000000000',
                'status'    => 1,
            ]
        ]);
    }
}
