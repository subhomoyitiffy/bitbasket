<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Package::insert([
            [
                'name'=> 'BASIC',
                'description' => 'Basic package',
                'duration' => 12,
                'price' => 100.00,
                'status'    => 1
            ],
            [
                'name'=> 'GOLD',
                'description' => 'Gold package',
                'duration' => 18,
                'price' => 150.00,
                'status'    => 1
            ],
            [
                'name'=> 'PLATINUM',
                'description' => 'Platinum package',
                'duration' => 24,
                'price' => 200.00,
                'status'    => 1
            ]
        ]);
    }
}
