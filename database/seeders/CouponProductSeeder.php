<?php
namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponProductSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = Coupon::all();
        $products = Product::pluck('id')->toArray();

        foreach ($coupons as $coupon) {
            // هنربط كل كوبون بـ 3 منتجات عشوائية
            $selectedProducts = fake()->randomElements($products, 3);

            foreach ($selectedProducts as $productId) {
                DB::table('coupon_products')->insert([
                    'coupon_id' => $coupon->id,
                    'product_id' => $productId,
                ]);
            }
        }
    }
}
