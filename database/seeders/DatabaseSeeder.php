<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

$this->call([
    UserSeeder::class,
    UserAddressSeeder::class,
    CategorySeeder::class,
    VendorSeeder::class,
    ProductSeeder::class,
    ProductVariantSeeder::class,
    OrderSeeder::class, // This already creates OrderItems
    // OrderItemSeeder::class, // Removed to avoid duplication
    CouponSeeder::class,
    BlogSeeder::class,
    SubscriptionSeeder::class,
    WishlistSeeder::class,
    ProductReviewSeeder::class,
    CouponProductSeeder::class,
    BlogReviewSeeder::class,
    imagesSeeder::class,
    MohammedSeeder::class,
]);

    }
}
