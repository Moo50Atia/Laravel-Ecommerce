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
        $products = Product::pluck('id')->toArray();

        foreach ($users as $user) {
            Wishlist::factory(3)->create([
                'user_id' => $user->id,
                'product_id' => fake()->randomElement($products),
            ]);
        }
    }
}
