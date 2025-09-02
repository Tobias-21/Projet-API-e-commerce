<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
            'title' => fake()->word(),
             'user_id' => \App\Models\User::factory(),
             'description' => fake()->sentence(),
             'base_price' => fake()->randomFloat(2, 5, 500),
             'category_id' => \App\Models\Category::factory(),
             'sku' => strtoupper(fake()->bothify('??###')),
             'status' => fake()->randomElement(['actif', 'inactif']),
        ];
    }
}
