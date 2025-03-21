<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Module::insert([
            [
                'name' => 'Dashboard'
            ],
            [
                'name' => 'Access & Permission - Modules'
            ],
            [
                'name' => 'Access & Permission - Roles'
            ],
            [
                'name' => 'Access & Permission - Admin Users'
            ],
            [
                'name' => 'FAQ - Category'
            ],
            [
                'name' => 'FAQ - List'
            ],
            [
                'name' => 'Membership Plans'
            ],
            [
                'name' => 'Members'
            ],
            [
                'name' => 'Members Membership Plan'
            ],
            [
                'name' => 'Members Membership History'
            ],
            [
                'name' => 'Lesson Plans'
            ],
            [
                'name' => 'View Chat History'
            ],
            [
                'name' => 'Subscribed Users'
            ],
            [
                'name' => 'Notifications'
            ],
            [
                'name' => 'Account Settings'
            ],
            [
                'name' => 'Email Logs'
            ],
            [
                'name' => 'Login Logs'
            ],
            [
                'name' => 'Member Users'
            ]
        ]);
    }
}
