<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\State;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        State::insert([
            [
                'name' => 'Abu Zabi',
                'country_id' => 229
            ],
            [
                'name' => 'Ajman',
                'country_id' => 229
            ],
            [
                'name' => 'Dubai',
                'country_id' => 229
            ],
            [
                'name' => 'Ras al-Khaymah',
                'country_id' => 229
            ],
            [
                'name' => 'Sharjah',
                'country_id' => 229
            ],
            [
                'name' => 'Sharjha',
                'country_id' => 229
            ],
            [
                'name' => 'Umm al Qaywayn',
                'country_id' => 229
            ],
            [
                'name' => 'al-Fujayrah',
                'country_id' => 229
            ],
            [
                'name' => 'ash-Shariqah',
                'country_id' => 229
            ]
        ]);
    }
}
