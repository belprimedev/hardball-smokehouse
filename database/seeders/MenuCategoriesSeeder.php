<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MenuCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Clear the table
        DB::table('menu_categories')->delete();
        
        $now = Carbon::now();

        DB::table('menu_categories')->insert([
            ['name' => 'Starters', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Burger and Kids Meals', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Curry Dishes', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Wings', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pasta Dishes', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Jerk Dishes', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Serves with rice and peas', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Meals', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sides', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Wraps', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Platters', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Mains with Rice and Peas', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lobster', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Dessert', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
