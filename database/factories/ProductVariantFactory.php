<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             'option_name' => 'Size',
            'option_value' => fake()->randomElement(['S', 'M', 'L', 'XL']),
            'price_modifier' => fake()->randomFloat(2, 0, 20),
              'stock' => fake()->numberBetween(0, 50),
        ];
    }
}
