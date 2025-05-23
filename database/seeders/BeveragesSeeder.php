<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\MenuCategory;

class BeveragesSeeder extends Seeder
{
    public function run(): void
    {
        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        $now = Carbon::now();

        // Create "Cocktails & Beverages" category if it doesn't exist
        $category = MenuCategory::firstOrCreate(
            ['name' => 'Cocktails & Beverages'],
            [
                'description' => 'Signature cocktails and Jamaican beverages',
                'created_at' => $now,
                'updated_at' => $now
            ]
        );
        $categoryId = $category->id;

        // Define signature cocktails
        $cocktails = [
            [
                'name' => 'Rum Punch',
                'description' => 'Appleton rum, pineapple juice, orange juice, grenadine',
                'price' => 9.50,
                'is_chef_special' => true
            ],
            [
                'name' => 'Jamaican Mule',
                'description' => 'Spiced rum, ginger beer, lime juice',
                'price' => 9.00,
                'is_chef_special' => true
            ],
            [
                'name' => 'Reggae Rum Runner',
                'description' => 'Rum blend, banana liqueur, blackberry liqueur, grenadine',
                'price' => 10.00,
                'is_chef_special' => true
            ],
            [
                'name' => 'Blue Lagoon',
                'description' => 'Vodka, blue curaçao, lemonade',
                'price' => 8.50,
                'is_chef_special' => false
            ],
            [
                'name' => 'Bob Marley',
                'description' => 'Grenadine, crème de menthe, banana liqueur layered',
                'price' => 10.25,
                'is_chef_special' => true
            ],
            [
                'name' => 'Tropical Storm',
                'description' => 'Coconut rum, mango puree, lime juice',
                'price' => 9.25,
                'is_chef_special' => false
            ],
            [
                'name' => 'Island Breeze',
                'description' => 'Vodka, cranberry juice, pineapple juice',
                'price' => 8.75,
                'is_chef_special' => false
            ],
            [
                'name' => 'Guava Mojito',
                'description' => 'White rum, guava puree, mint, lime',
                'price' => 9.75,
                'is_chef_special' => true
            ],
            [
                'name' => 'Sunset Bliss',
                'description' => 'Rum, passion fruit, orange juice, grenadine',
                'price' => 9.60,
                'is_chef_special' => false
            ],
            [
                'name' => 'Dark & Stormy',
                'description' => 'Dark rum, ginger beer, lime',
                'price' => 9.00,
                'is_chef_special' => false
            ],
        ];

        // Add Jamaican beers
        $beers = [
            [
                'name' => 'Red Stripe',
                'description' => 'Iconic Jamaican lager beer',
                'price' => 4.50,
                'is_chef_special' => false
            ],
            [
                'name' => 'Dragon Stout',
                'description' => 'Strong Jamaican stout',
                'price' => 5.00,
                'is_chef_special' => false
            ],
            [
                'name' => 'Red Stripe Lemon Paradise',
                'description' => 'Refreshing lemon-infused lager',
                'price' => 4.75,
                'is_chef_special' => false
            ],
        ];

        // Merge and insert
        $items = array_merge($cocktails, $beers);

        foreach ($items as $item) {
            DB::table('menu_items')->insert([
                'category_id' => $categoryId,
                'name' => $item['name'],
                'description' => $item['description'],
                'price' => $item['price'],
                'short_label' => null,
                'side_note' => null,
                'image_path' => null,
                'is_featured' => false,
                'is_chef_special' => $item['is_chef_special'],
                'is_available' => true,
                'is_visible' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
} 