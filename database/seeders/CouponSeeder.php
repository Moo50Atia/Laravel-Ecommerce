<?php
namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::pluck('id')->toArray();

        Coupon::factory(10)->create()->each(function ($coupon) use ($products) {
            $randomProductId = fake()->randomElement($products);
            $coupon->product_id = $randomProductId;
            $coupon->save();
        });
    }
}

