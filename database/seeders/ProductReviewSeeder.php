<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Database\Seeder;

class ProductReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $productIds = Product::pluck('id')->toArray();

        if (empty($productIds)) {
            return;
        }

        foreach ($users as $user) {
            // Pick 2 unique random products for each user (no duplicate reviews)
            $count = min(2, count($productIds));
            $selectedProducts = collect($productIds)->random($count)->all();

            foreach ($selectedProducts as $productId) {
                ProductReview::factory()->create([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                ]);
            }
        }
    }
}
