<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class imagesSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            if (!$product->image) {
                $product->image()->create([
                    'url' => fake()->imageUrl(640, 480, 'products', true),
                    'type' => 'card',
                ]);
            }

            if ($product->images()->count() == 0) {
                $product->images()->createMany([
                    ['url' => fake()->imageUrl(800, 600, 'products', true), 'type' => 'detail'],
                    ['url' => fake()->imageUrl(800, 600, 'products', true), 'type' => 'detail'],
                ]);
            }
        }
    }
}
