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
        $products = Product::pluck('id')->toArray();

        foreach ($users as $user) {
            ProductReview::factory(2)->create([
                'user_id' => $user->id,
                'product_id' => fake()->randomElement($products),
            ]);
        }
    }
}
