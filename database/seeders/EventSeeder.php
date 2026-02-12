<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Event::where('show_on_homepage', true)->exists()) {
            return;
        }

        Event::create([
            'title_primary' => 'Christmas',
            'title_secondary' => 'Dinner',
            'title_suffix' => 'at Hardball!',
            'description' => 'Celebrate the festive season with a Caribbean twist! Join us for an unforgettable Christmas dinner experience with authentic flavors and warm hospitality.',
            'image_path' => '/img/event/christmas-dinner.png',
            'features' => [
                ['title' => 'Festive Menu', 'description' => 'Special Christmas dishes with Caribbean flair'],
                ['title' => 'Warm Atmosphere', 'description' => 'Cozy festive setting perfect for family gatherings'],
            ],
            'cta_text' => 'Reserve Your Spot',
            'cta_link' => null,
            'status' => 'published',
            'show_on_homepage' => true,
            'sort_order' => 0,
        ]);
    }
}
