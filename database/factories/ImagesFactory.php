<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImagesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
 public function definition()
    {
        // اختيار عشوائي للموديل المرتبط (Product, User, إلخ)
        $imageableTypes = [
            \App\Models\Product::class,
            \App\Models\User::class,
            // ممكن تزود موديلات تانية هنا
        ];

        $imageableType = $this->faker->randomElement($imageableTypes);
        $imageable = $imageableType::inRandomOrder()->first() ?? $imageableType::factory()->create();

        return [
            'url' => $this->faker->imageUrl(640, 480, 'products', true),
            'imageable_id' => $imageable->id,
            'imageable_type' => $imageableType,
            'type' => $this->faker->randomElement(['card', 'detail']),
        ];
    }
}
