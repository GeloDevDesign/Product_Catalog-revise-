<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true), 
            'sell_price' => fake()->randomFloat(2, 10, 1000),
            'is_active' => fake()->boolean()
        ];
    }

    public function configure()
    {
        // $categories = [1,2,3];

        return $this->afterCreating(function (Product $product) {
            $categories = Category::inRandomOrder()
                ->take(rand(1, 3))
                ->pluck('id');

            $product->categories()->attach($categories);
        });
    }
}
