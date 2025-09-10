<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Wishlist;
use App\Models\UserAddress;


class MohammedSeeder extends Seeder
{
    public function run(): void
    {
        // 1️⃣ إنشاء اليوزر أو تحديثه لو موجود
        $user = User::updateOrCreate(
            ['email' => 'mohammed50atia@gmail.com'],
            [
                'name' => 'mohammed atia',
                'password' => Hash::make('123456789'),
                'phone' => '01068584731',
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        // 2️⃣ إنشاء الـ Vendor مربوط باليوزر
        $vendor = Vendor::updateOrCreate(
            ['user_id' => $user->id],
            [
                'store_name' => 'Store of Mohammed',
                'email' => $user->email,
                'phone' => '01012345678',
                'slug' => Str::slug('Store of Mohammed-' . $user->id),
                'description' => "This is Mohammed's official store.",
                'commission_rate' => 10.00,
                'is_approved' => true,
                'rating' => 0.00,
            ]
        );

        // 3️⃣ إنشاء 5 منتجات مرتبطة بمخزنك
        $products = Product::factory()->count(5)->create([
                "vendor_id" => $vendor->id,
            ]);

            // إضافة 3 Variants لكل منتج
            foreach ($products as $product) {
                $product->variants()->createMany(
                    \App\Models\ProductVariant::factory()->count(3)->make()->toArray()
                );
            }

        // 4️⃣ عملاء عشوائيين بيشتروا من منتجاتك
        $customerIds = User::where("id", "!=", $user->id)->pluck("id")->toArray();

        if (empty($customerIds)) {
            $customerIds = User::factory(5)->create()->pluck("id")->toArray();
        }

        foreach ($products as $product) {
            $order = Order::factory()->create([
                "user_id"   => $customerIds[array_rand($customerIds)],
                        ]);

            OrderItem::factory(rand(1, 3))->create([
                "order_id"   => $order->id,
                "product_id" => $product->id,
                "variant_id" => $product->variants()->inRandomOrder()->value("id"),
                "vendor_id" => $product->vendor->id, // استخدام Vendor المنتج
            ]);
        }

        // 5️⃣ أنت بتشتري منتجات من Vendors عشوائيين
        $otherVendorProducts = Product::where("vendor_id", "!=", $vendor->id)->get();

        // لو مفيش Vendors تانيين، نعمل شوية بيانات تجريبية
        if ($otherVendorProducts->count() < 10) {
            $extraVendors = Vendor::factory(3)->create()->each(function ($v) {
                Product::factory(5)->create(["vendor_id" => $v->id]);
            });
            $otherVendorProducts = Product::where("vendor_id", "!=", $vendor->id)->get();
        }

        // إنشاء طلبات ليك شخصيًا
        foreach ($otherVendorProducts->random(min(10, $otherVendorProducts->count())) as $product) {
            $order = Order::factory()->create([
                "user_id"   => $user->id, // أنت المشتري
            ]);

            OrderItem::factory(rand(1, 2))->create([
                "order_id"   => $order->id,
                "product_id" => $product->id,
                "variant_id" => $product->variants()->inRandomOrder()->value("id"),
                "vendor_id" => $product->vendor->id, // استخدام Vendor المنتج
            ]);
            
        }

        // 6️⃣ Wishlist فيها 20 عنصر حقيقي
        $allProductIds = Product::pluck("id")->toArray();

        if (count($allProductIds) >= 20) {
            $wishlistProducts = collect($allProductIds)->random(20);
        } else {
            $wishlistProducts = collect($allProductIds)
                ->concat($allProductIds)
                ->take(20);
        }

        foreach ($wishlistProducts as $productId) {
            Wishlist::updateOrCreate(
                [
                    "user_id" => $user->id,
                    "product_id" => $productId
                ],
                []
            );
        }
          UserAddress::updateOrCreate(
            ["user_id" => $user->id],
            [
                "address_line1" => "123 Main Street",
                "address_line2" => "Apartment 4B",
                "city"          => "Cairo",
                "state"         => "Cairo Governorate",
                "country"       => "Egypt",
                "postal_code"   => "12345",
            ]
        );
        
    }
    
}
