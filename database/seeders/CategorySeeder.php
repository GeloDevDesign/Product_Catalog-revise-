<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Database\Factories\CategoryFactory;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Kitchen',
            'Motorcycle',
            'Kids',
            'Books',
            'Toys',
            'Utensils',
            'Computer',
            'Shoes',
            'Women',
            'Men'
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate([
                'name' => $category
            ]);
        }
    }
}
