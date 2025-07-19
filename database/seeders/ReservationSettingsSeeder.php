<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ReservationSetting;

class ReservationSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $days = [
            'monday' => ['13:00:00', '21:30:00'],
            'tuesday' => ['13:00:00', '21:30:00'],
            'wednesday' => ['13:00:00', '22:30:00'],
            'thursday' => ['13:00:00', '22:30:00'],
            'friday' => ['16:30:00', '23:00:00'],
            'saturday' => ['13:00:00', '23:00:00'],
            'sunday' => ['13:00:00', '20:30:00'],
        ];

        foreach ($days as $day => $times) {
            ReservationSetting::create([
                'day_of_week' => $day,
                'opening_time' => $times[0],
                'closing_time' => $times[1],
                'max_capacity_per_hour' => 20,
                'is_open' => true,
            ]);
        }
    }
}
