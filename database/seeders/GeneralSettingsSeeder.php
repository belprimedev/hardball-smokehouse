<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GeneralSetting;

class GeneralSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GeneralSetting::create([
            'business_name' => 'Hardball Smokehouse',
            'business_email' => 'info@hardballsmokehouse.com.uk',
            'contact_number' => '07398 951462',
            'address' => '24 Lloyds Ave, Ipswich IP1 3HD, United Kingdom',
            'operation_hours' => "Monday: 1:00 PM - 9:30 PM\nTuesday: 1:00 PM - 9:30 PM\nWednesday: 1:00 PM - 10:30 PM\nThursday: 1:00 PM - 10:30 PM\nFriday: 4:30 PM - 11:00 PM\nSaturday: 1:00 PM - 11:00 PM\nSunday: 1:00 PM - 8:30 PM",
            'website' => 'https://hardballsmokehouse.com.uk',
            'description' => 'Authentic Southern BBQ and craft cocktails in the heart of Ipswich. Experience the finest smoked meats, fresh ingredients, and warm hospitality in our cozy restaurant.',
        ]);
    }
}
