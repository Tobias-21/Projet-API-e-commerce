<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product_Variants>
 */
class Product_VariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'attributes' => json_encode(['size' => fake()->randomElement(['S', 'M', 'L']), 'color' => fake()->colorName()]),
            'price' => fake()->randomFloat(2, 10, 200),
            'stock' => fake()->numberBetween(0, 100),
            'sku' => strtoupper(fake()->bothify('??###')),
        ];
    }
}
