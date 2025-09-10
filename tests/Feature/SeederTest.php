<?php

use App\Models\User;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Database Seeders', function () {
    it('MohammedSeeder creates consistent data with working relationships', function () {
        // Run the seeder
        $this->artisan('db:seed', ['--class' => 'MohammedSeeder']);
        
        // Check that essential models were created
        expect(User::count())->toBeGreaterThan(0);
        expect(Vendor::count())->toBeGreaterThan(0);
        expect(Product::count())->toBeGreaterThan(0);
        
        // Check relationships
        $vendor = Vendor::first();
        expect($vendor->user)->toBeInstanceOf(User::class);
        
        $product = Product::first();
        expect($product->vendor)->toBeInstanceOf(Vendor::class);
    });
    
    it('OrderSeeder creates orders with valid relationships', function () {
        // First seed users, vendors and products
        $this->artisan('db:seed', ['--class' => 'MohammedSeeder']);
        
        // Then run the order seeder
        $this->artisan('db:seed', ['--class' => 'OrderSeeder']);
        
        // Check that orders were created
        expect(Order::count())->toBeGreaterThan(0);
        
        // Check relationships
        $order = Order::first();
        expect($order->user)->toBeInstanceOf(User::class);
        expect($order->items)->not->toBeEmpty();
        
        // Check order items have valid products
        $orderItem = $order->items->first();
        expect($orderItem->product)->toBeInstanceOf(Product::class);
        
        // Check calculations
        $calculatedTotal = $order->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        
        // Allow for small floating point differences
        expect(abs($calculatedTotal - $order->total_amount))->toBeLessThan(0.01);
    });
});