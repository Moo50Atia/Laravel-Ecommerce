<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper(Str::random(8)),
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'max_uses' => 100,
            'valid_from' => now(),
            'valid_to' => now()->addMonth(),
            'min_order_amount' => 50,
            'is_active' => true,
            "discription" => fake()->sentence(6, true),
            'product_id' => Product::inRandomOrder()->first()?->id, // ✅ أضف دي
        ];
    }
}
