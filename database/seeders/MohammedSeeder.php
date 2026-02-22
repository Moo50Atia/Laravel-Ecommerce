<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Wishlist;
use App\Models\UserAddress;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class MohammedSeeder extends Seeder
{
    public function run(): void
    {
        $city = 'Cairo';
        $password = '123456789';

        // Ensure we have at least one category
        $category = Category::first() ?? Category::factory()->create();

        // ─── 1. Super Admin ─────────────────────────────────────
        // Bypasses all restrictions.
        $superAdmin = User::updateOrCreate(
            ['email' => 'mohammed50atia@gmail.com'],
            [
                'name' => 'Super Admin Atia',
                'password' => Hash::make($password),
                'phone' => '01068584731',
                'role' => 'superadmin',
                'status' => 'active',
            ]
        );

        UserAddress::updateOrCreate(
            ['user_id' => $superAdmin->id],
            [
                'address_line1' => 'Super Admin Street',
                'city' => $city,
                'state' => 'Cairo',
                'country' => 'Egypt',
                'postal_code' => '11111',
            ]
        );

        // ─── 2. Local Admin (Cairo Scope) ──────────────────────
        // Can only see data from Cairo.
        $admin = User::updateOrCreate(
            ['email' => 'admin@ecommerce.test'],
            [
                'name' => 'Cairo Admin',
                'password' => Hash::make($password),
                'phone' => '01000000001',
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        UserAddress::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'address_line1' => 'Admin Office',
                'city' => $city,
                'state' => 'Cairo',
                'country' => 'Egypt',
                'postal_code' => '22222',
            ]
        );

        // ─── 3. Vendor (Cairo Based) ──────────────────────────
        $vendorUser = User::updateOrCreate(
            ['email' => 'vendor@ecommerce.test'],
            [
                'name' => 'Cairo Merchant',
                'password' => Hash::make($password),
                'phone' => '01000000002',
                'role' => 'vendor',
                'status' => 'active',
            ]
        );

        UserAddress::updateOrCreate(
            ['user_id' => $vendorUser->id],
            [
                'address_line1' => 'Merchant Shop',
                'city' => $city,
                'state' => 'Cairo',
                'country' => 'Egypt',
                'postal_code' => '33333',
            ]
        );

        $vendor = Vendor::updateOrCreate(
            ['user_id' => $vendorUser->id],
            [
                'store_name' => 'Cairo Electronics',
                'email' => $vendorUser->email,
                'phone' => $vendorUser->phone,
                'slug' => Str::slug('Cairo Electronics-' . $vendorUser->id),
                'description' => 'The best electronics in Cairo.',
                'commission_rate' => 8.00,
                'is_approved' => true,
                'rating' => 4.5,
            ]
        );

        // Create 8 products for this vendor
        $vendorProducts = Product::factory()->count(8)->create([
            'vendor_id' => $vendor->id,
            'category_id' => $category->id,
        ]);

        foreach ($vendorProducts as $product) {
            ProductVariant::factory()->count(3)->create([
                'product_id' => $product->id,
            ]);
        }

        // ─── 4. Normal User (Cairo Based) ─────────────────────
        $user = User::updateOrCreate(
            ['email' => 'user@ecommerce.test'],
            [
                'name' => 'Cairo Customer',
                'password' => Hash::make($password),
                'phone' => '01000000003',
                'role' => 'user',
                'status' => 'active',
            ]
        );

        UserAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'address_line1' => 'Customer Home',
                'city' => $city,
                'state' => 'Cairo',
                'country' => 'Egypt',
                'postal_code' => '44444',
            ]
        );

        // User buys 5 orders from the Cairo Vendor
        for ($i = 0; $i < 5; $i++) {
            $order = Order::factory()->create([
                'user_id' => $user->id,
                'status' => fake()->randomElement(['pending', 'processing', 'shipped']),
            ]);

            $itemsCount = rand(1, 3);
            $totalAmount = 0;

            $selectedProducts = $vendorProducts->random($itemsCount);
            foreach ($selectedProducts as $prod) {
                $variant = $prod->variants->random();
                $qty = rand(1, 2);
                $price = $prod->price + $variant->price_modifier;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $prod->id,
                    'variant_id' => $variant->id,
                    'vendor_id' => $vendor->id,
                    'quantity' => $qty,
                    'price' => $price,
                ]);

                $totalAmount += $price * $qty;
            }

            $discount = $totalAmount * (rand(0, 10) / 100);
            $shipping = 50.00;
            $order->update([
                'total_amount' => $totalAmount,
                'discount_amount' => $discount,
                'shipping_amount' => $shipping,
                'grand_total' => $totalAmount - $discount + $shipping,
            ]);
        }

        // User Wishlist
        foreach ($vendorProducts->random(min(10, $vendorProducts->count())) as $p) {
            Wishlist::updateOrCreate(
                ['user_id' => $user->id, 'product_id' => $p->id],
                []
            );
        }

        // ─── 5. Extra Data for Noise (Cairo Focused) ──────────
        // Add a few more Cairo customers to populate the Admin dashboard
        User::factory(3)->create(['role' => 'user'])->each(function ($u) use ($city, $vendor, $vendorProducts) {
            UserAddress::factory()->create(['user_id' => $u->id, 'city' => $city]);

            // Each places 1-2 orders
            for ($i = 0; $i < rand(1, 2); $i++) {
                $order = Order::factory()->create(['user_id' => $u->id]);
                $p = $vendorProducts->random();
                $v = $p->variants->random();
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $p->id,
                    'variant_id' => $v->id,
                    'vendor_id' => $vendor->id,
                    'quantity' => 1,
                    'price' => $p->price + $v->price_modifier,
                ]);
                // Simplified total for noise data
                $total = $p->price + $v->price_modifier;
                $order->update(['total_amount' => $total, 'grand_total' => $total + 50]);
            }
        });
    }
}
