<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Vendor;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'status' => fake()->randomElement(['pending', 'processing', 'shipped', 'delivered', 'canceled', 'refunded']),
            'total_amount' => 0, // Will be calculated from order items
            'discount_amount' => 0, // Will be calculated
            'shipping_amount' => 0, // Will be calculated
            'grand_total' => 0, // Will be calculated from order items
            'payment_method' => fake()->randomElement(['credit_card', 'cod', 'bank_transfer']),
            'payment_status' => fake()->randomElement(['paid', 'unpaid', 'failed']),
            'shipping_address' => [
                'street' => fake()->streetAddress(),
                'city' => fake()->city(),
                'state' => fake()->state(),
                'postal_code' => fake()->postcode(),
                'country' => fake()->country(),
            ],
            'billing_address' => [
                'street' => fake()->streetAddress(),
                'city' => fake()->city(),
                'state' => fake()->state(),
                'postal_code' => fake()->postcode(),
                'country' => fake()->country(),
            ],
            'notes' => fake()->optional(0.3)->sentence(), // 30% chance of having notes
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => function (array $attributes) {
                return $attributes['created_at'];
            },
        ];
    }
}
