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
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->word();
        return [
            'name' => $name,
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10, 500),
            'is_active' => true,
            'is_featured' => fake()->boolean(20),
            'weight' => fake()->randomFloat(2, 0.5, 5),
            'dimensions' => json_encode(['width' => 10, 'height' => 20, 'depth' => 5]),
            'short_description' => fake()->sentence(),
            'category_id' => Category::inRandomOrder()->first()->id,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            // صورة الـ card
            $product->image()->create([
                'url' => fake()->imageUrl(640, 480, 'products', true),
                'type' => 'card',
            ]);

            // صور الـ detail
            $product->images()->createMany([
                ['url' => fake()->imageUrl(800, 600, 'products', true), 'type' => 'detail'],
                ['url' => fake()->imageUrl(800, 600, 'products', true), 'type' => 'detail'],
            ]);
        });
    }
}
