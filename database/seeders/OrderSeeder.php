<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::with("vendor")->get();
        $products = Product::all();
        $vendors =Vendor::all();

        // Check if we have products to work with
        if ($products->isEmpty()) {
            throw new \Exception('No products found. Please run ProductSeeder first.');
        }

        foreach ($users as $user) {
            if ($user->vendor) {
                // Create 20 orders for each vendor
                for ($i = 0; $i < 20; $i++) {
                    // Create the order first
                    $order = Order::factory()->create([
                        "user_id" => $user->id,
                        "grand_total" => 0, // Will be calculated after adding items
                    ]);

                    // Create 1-5 order items for each order
                    $numberOfItems = fake()->numberBetween(1, 5);
                    $totalAmount = 0;

                    for ($j = 0; $j < $numberOfItems; $j++) {
                        // Get a random product
                        $product = $products->random();
                        
                        // Get a random variant if the product has variants, otherwise null
                        $variantId = null;
                        if ($product->variants && $product->variants->count() > 0) {
                            $variantId = $product->variants->random()->id;
                        }
                        
                        // Create order item
                        $orderItem = OrderItem::factory()->create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'variant_id' => $variantId,
                            "vendor_id" => $vendors->random()->id, // Use a random vendor's ID
                            'quantity' => fake()->numberBetween(1, 5),
                            'price' => fake()->randomFloat(2, 10, 200), // Consistent with factory
                        ]);

                        // Add to total amount
                        $totalAmount += $orderItem->price * $orderItem->quantity;
                    }

                    // Calculate discount and shipping
                    $discountAmount = fake()->randomFloat(2, 0, $totalAmount * 0.2); // 0-20% discount
                    $shippingAmount = fake()->randomFloat(2, 0, 50); // 0-50 shipping
                    $grandTotal = $totalAmount - $discountAmount + $shippingAmount;

                    // Update the order with calculated amounts
                    $order->update([
                        'total_amount' => $totalAmount,
                        'discount_amount' => $discountAmount,
                        'shipping_amount' => $shippingAmount,
                        'grand_total' => $grandTotal,
                    ]);
                }
            }
        }
    }
}

