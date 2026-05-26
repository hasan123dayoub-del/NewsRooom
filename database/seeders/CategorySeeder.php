<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'News from the Top',
                'description' => 'All updates and decisions issued directly from the executive management and CEO.'
            ],
            [
                'name' => 'Engineering & Development Updates',
                'description' => 'Updates on software systems, internal architecture, and Backend projects for 2026.'
            ],
            [
                'name' => 'Employee Events & Celebrations',
                'description' => 'Coverage of internal activities, trips, and group achievements at TechNova.'
            ]
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name'], '-', 'ar'),
                'description' => $category['description']
            ]);
        }
    }
}
