<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuCategory;

class MenuCategoryDisplayImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing categories with sample display images
        $categories = MenuCategory::all();
        
        foreach ($categories as $category) {
            // Set sample display images based on category name
            $displayImage = null;
            
            switch (strtolower($category->name)) {
                case 'starters':
                case 'appetizers':
                    $displayImage = 'food/burger.png';
                    break;
                case 'jerk dishes':
                case 'jerk':
                    $displayImage = 'food/portrait5.JPG';
                    break;
                case 'curry dishes':
                case 'curry':
                    $displayImage = 'food/fritters.jpg';
                    break;
                case 'meals':
                case 'main dishes':
                    $displayImage = 'food/portrait5.JPG';
                    break;
                default:
                    $displayImage = 'food/burger.png';
                    break;
            }
            
            $category->update(['display_image' => $displayImage]);
        }
    }
}
