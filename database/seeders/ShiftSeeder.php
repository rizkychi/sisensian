<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shifts = [
            [
                'name' => 'Shift Normal',
                'time_in' => '08:00',
                'time_out' => '16:00',
                'is_fixed' => true,
            ],
            [
                'name' => 'Shift Normal Jum\'at',
                'time_in' => '08:00',
                'time_out' => '14:00',
                'is_fixed' => true,
            ],
            [
                'name' => 'Shift Pagi',
                'time_in' => '08:00',
                'time_out' => '16:00',
                'is_fixed' => false,
            ],
            [
                'name' => 'Shift Sore',
                'time_in' => '08:00',
                'time_out' => '16:00',
                'is_fixed' => false,
            ],
            [
                'name' => 'Shift Malam',
                'time_in' => '08:00',
                'time_out' => '16:00',
                'is_fixed' => false,
            ],
        ];

        foreach ($shifts as $shift) {
            Shift::create($shift);
        }
    }
}
