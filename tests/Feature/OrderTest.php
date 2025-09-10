<?php

use App\Models\Order;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Order Model', function () {
    it('can create an order with factory', function () {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'order_number' => 'ORD-12345',
            'total_amount' => 199.99,
        ]);
        
        expect($order)->toBeInstanceOf(Order::class)
            ->and($order->order_number)->toBe('ORD-12345')
            ->and($order->total_amount)->toBe(199.99);
    });
    
    it('can have order items', function () {
        $order = Order::factory()->create();
        $product = Product::factory()->create(['price' => 50]);
        
        $orderItem = OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => $product->price,
        ]);
        
        expect($order->items)->toHaveCount(1)
            ->and($order->items->first()->quantity)->toBe(2);
    });
});

describe('Order Relationships', function () {
    it('belongs to a user', function () {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);
        
        expect($order->user)->toBeInstanceOf(User::class)
            ->and($order->user->id)->toBe($user->id);
    });
    
    it('has products through order items', function () {
        $order = Order::factory()->create();
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();
        
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product1->id,
        ]);
        
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product2->id,
        ]);
        
        expect($order->products)->toHaveCount(2);
    });
    
    it('has vendors through products', function () {
        $order = Order::factory()->create();
        $vendor = Vendor::factory()->create();
        $product = Product::factory()->create(['vendor_id' => $vendor->id]);
        
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);
        
        expect($order->vendor)->toBeInstanceOf(Vendor::class)
            ->and($order->vendor->id)->toBe($vendor->id);
    });
});

describe('Order Calculations', function () {
    it('calculates grand total correctly', function () {
        $order = Order::factory()->create([
            'total_amount' => 100,
            'discount_amount' => 10,
            'shipping_amount' => 5,
        ]);
        
        // Assuming grand_total = total_amount - discount_amount + shipping_amount
        expect($order->grand_total)->toBe(95);
    });
});

describe('Order ForAdmin Scope', function () {
    it('allows superadmin to see all orders', function () {
        // Create users in different cities
        $user1 = User::factory()->create();
        $address1 = UserAddress::factory()->create([
            'user_id' => $user1->id,
            'city' => 'City A',
        ]);
        $order1 = Order::factory()->create(['user_id' => $user1->id]);
        
        $user2 = User::factory()->create();
        $address2 = UserAddress::factory()->create([
            'user_id' => $user2->id,
            'city' => 'City B',
        ]);
        $order2 = Order::factory()->create(['user_id' => $user2->id]);
        
        // Create superadmin
        $superAdmin = User::factory()->create(['role' => 'superadmin']);
        
        // Apply ForAdmin scope
        $orders = Order::query()->ForAdmin($superAdmin)->get();
        
        // Superadmin should see all orders
        expect($orders)->toHaveCount(2);
    });
    
    it('restricts admin to see only orders from users in their city', function () {
        // Create users in different cities
        $user1 = User::factory()->create();
        $address1 = UserAddress::factory()->create([
            'user_id' => $user1->id,
            'city' => 'City A',
        ]);
        $order1 = Order::factory()->create(['user_id' => $user1->id]);
        
        $user2 = User::factory()->create();
        $address2 = UserAddress::factory()->create([
            'user_id' => $user2->id,
            'city' => 'City B',
        ]);
        $order2 = Order::factory()->create(['user_id' => $user2->id]);
        
        // Create admin in City A
        $admin = User::factory()->create(['role' => 'admin']);
        $adminAddress = UserAddress::factory()->create([
            'user_id' => $admin->id,
            'city' => 'City A',
        ]);
        
        // Apply ForAdmin scope
        $orders = Order::query()->ForAdmin($admin)->get();
        
        // Admin should only see orders from users in City A
        expect($orders)->toHaveCount(1)
            ->and($orders->first()->id)->toBe($order1->id);
    });
});