<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $productIds = Product::pluck('id')->toArray();

        if (empty($productIds)) {
            return;
        }

        foreach ($users as $user) {
            // Pick 3 unique random products for each user (no duplicates)
            $count = min(3, count($productIds));
            $selectedProducts = collect($productIds)->random($count)->all();

            foreach ($selectedProducts as $productId) {
                Wishlist::create([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                ]);
            }
        }
    }
}
