<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vendor>
 */
class VendorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {$name = fake()->company();
        return [
            'store_name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 1000),
            'description' => fake()->paragraph(),
            'commission_rate' => 5,
            'is_approved' => fake()->boolean(50),
            'rating' => fake()->randomFloat(2, 1, 5),
        ];
    }
}
