<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HandleOrdersTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Product $product;
    protected ProductVariant $variant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'user']);

        $vendorUser = User::factory()->create(['role' => 'vendor']);
        $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);

        $this->product = Product::factory()->create([
            'vendor_id' => $vendor->id,
            'price' => 100,
            'is_active' => true
        ]);

        $this->variant = ProductVariant::factory()->create([
            'product_id' => $this->product->id,
            'price_modifier' => 20,
            'stock' => 10
        ]);
    }

    public function test_user_can_add_product_to_cart()
    {
        $response = $this->actingAs($this->user)
            ->post(route('user.cart.post'), [
                'product_id' => $this->product->id,
                'variant_id' => $this->variant->id
            ]);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('done', 'Your Product added to cart');

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'status' => 'pending'
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $this->product->id,
            'variant_id' => $this->variant->id,
            'price' => 120 // 100 base + 20 modifier
        ]);
    }

    public function test_user_can_view_cart()
    {
        $order = Order::create(['user_id' => $this->user->id, 'status' => 'pending']);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'variant_id' => $this->variant->id,
            'vendor_id' => $this->product->vendor_id,
            'quantity' => 2,
            'price' => 120
        ]);

        $response = $this->actingAs($this->user)->get(route('user.cart'));

        $response->assertStatus(200);
        $response->assertViewIs('user.cart');
        $response->assertViewHas('total', 240);
    }

    public function test_cart_redirects_if_empty()
    {
        $response = $this->actingAs($this->user)->get(route('user.cart'));

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('NoProduct');
    }

    public function test_user_can_update_cart_quantities()
    {
        $order = Order::create(['user_id' => $this->user->id, 'status' => 'pending']);
        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'variant_id' => $this->variant->id,
            'vendor_id' => $this->product->vendor_id,
            'quantity' => 1,
            'price' => 120
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('user.cart.update', $order->id), [
                'items' => [
                    $item->id => ['quantity' => 3, 'price' => 120]
                ]
            ]);

        $response->assertRedirect(route('user.checkout', $order->id));

        $this->assertDatabaseHas('order_items', [
            'id' => $item->id,
            'quantity' => 3
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'grand_total' => 360
        ]);
    }

    public function test_user_can_complete_checkout()
    {
        $order = Order::create(['user_id' => $this->user->id, 'status' => 'pending']);

        $response = $this->actingAs($this->user)
            ->post(route('user.checkout.post', $order->id), [
                'name' => 'John Doe',
                'address' => '123 Street',
                'phone' => '123456789',
                'email' => 'john@example.com',
                'shipping_address' => 'Shipping Address',
                'same_as_shipping' => true,
                'payment_method' => 'cod'
            ]);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'processing',
            'name' => 'John Doe'
        ]);
    }

    public function test_user_cannot_checkout_foreign_order()
    {
        $otherUser = User::factory()->create();
        $order = Order::create(['user_id' => $otherUser->id, 'status' => 'pending']);

        $response = $this->actingAs($this->user)
            ->post(route('user.checkout.post', $order->id), [
                'name' => 'Hacker'
            ]);

        $response->assertStatus(403); // Policy block
    }
}
