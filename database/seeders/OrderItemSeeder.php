<?php
namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        // Only create order items for orders that don't already have them
        $orders = Order::whereDoesntHave('items')->get();
        $products = Product::all();

        // Check if we have products to work with
        if ($products->isEmpty()) {
            throw new \Exception('No products found. Please run ProductSeeder first.');
        }

        foreach ($orders as $order) {
            $itemsCount = rand(1, 5); // Random number of products per order

            for ($i = 0; $i < $itemsCount; $i++) {
                $product = $products->random();
                
                // Get a random variant if the product has variants, otherwise null
                $variantId = null;
                if ($product->variants && $product->variants->count() > 0) {
                    $variantId = $product->variants->random()->id;
                }

                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'variant_id' => $variantId,
                ]);
            }
        }
    }
}
    