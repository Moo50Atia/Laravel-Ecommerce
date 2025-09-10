<?php

use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use App\Models\UserAddress;
use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Product Model', function () {
    it('can create a product with factory', function () {
        $vendor = Vendor::factory()->create();
        $product = Product::factory()->create([
            'vendor_id' => $vendor->id,
            'name' => 'Test Product',
            'price' => 99.99,
        ]);
        
        expect($product)->toBeInstanceOf(Product::class)
            ->and($product->name)->toBe('Test Product')
            ->and($product->price)->toBe(99.99);
    });
    
    it('can have variants', function () {
        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'name' => 'Test Variant',
            'stock' => 10,
        ]);
        
        expect($product->variants)->toHaveCount(1)
            ->and($product->variants->first()->name)->toBe('Test Variant');
    });
    
    it('calculates total stock from variants', function () {
        $product = Product::factory()->create();
        
        // Create multiple variants with different stock levels
        ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 5,
        ]);
        
        ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 10,
        ]);
        
        expect($product->getTotalStockAttribute())->toBe(15);
    });
});

describe('Product Relationships', function () {
    it('belongs to a vendor', function () {
        $vendor = Vendor::factory()->create();
        $product = Product::factory()->create(['vendor_id' => $vendor->id]);
        
        expect($product->vendor)->toBeInstanceOf(Vendor::class)
            ->and($product->vendor->id)->toBe($vendor->id);
    });
    
    it('can have product reviews', function () {
        $product = Product::factory()->create();
        $user = User::factory()->create();
        $review = \App\Models\ProductReview::factory()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'rating' => 4,
        ]);
        
        expect($product->productReviews)->toHaveCount(1)
            ->and($product->productReviews->first()->rating)->toBe(4);
    });
    
    it('can have wishlists', function () {
        $product = Product::factory()->create();
        $user = User::factory()->create();
        $wishlist = \App\Models\Wishlist::factory()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);
        
        expect($product->wishlists)->toHaveCount(1)
            ->and($product->wishlists->first()->user_id)->toBe($user->id);
    });
    
    it('can have orders through order items', function () {
        $product = Product::factory()->create();
        $order = \App\Models\Order::factory()->create();
        
        // Create order item linking product and order
        \App\Models\OrderItem::factory()->create([
            'product_id' => $product->id,
            'order_id' => $order->id,
            'quantity' => 2,
            'price' => $product->price,
        ]);
        
        expect($product->orders)->toHaveCount(1)
            ->and($product->orders->first()->id)->toBe($order->id);
    });
});

describe('Product Accessors', function () {
    it('gets vendor name', function () {
        $user = User::factory()->create(['name' => 'Vendor User']);
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create(['vendor_id' => $vendor->id]);
        
        expect($product->getVendorNameAttribute())->toBe('Vendor User');
    });
    
    it('returns "Unknown Vendor" when vendor not found', function () {
        $product = Product::factory()->create(['vendor_id' => null]);
        
        expect($product->getVendorNameAttribute())->toBe('Unknown Vendor');
    });
});

describe('Product ForAdmin Scope', function () {
    it('allows superadmin to see all products', function () {
        // Create vendors in different cities
        $user1 = User::factory()->create();
        $address1 = UserAddress::factory()->create([
            'user_id' => $user1->id,
            'city' => 'City A',
        ]);
        $vendor1 = Vendor::factory()->create(['user_id' => $user1->id]);
        $product1 = Product::factory()->create(['vendor_id' => $vendor1->id]);
        
        $user2 = User::factory()->create();
        $address2 = UserAddress::factory()->create([
            'user_id' => $user2->id,
            'city' => 'City B',
        ]);
        $vendor2 = Vendor::factory()->create(['user_id' => $user2->id]);
        $product2 = Product::factory()->create(['vendor_id' => $vendor2->id]);
        
        // Create superadmin
        $superAdmin = User::factory()->create(['role' => 'superadmin']);
        
        // Apply ForAdmin scope
        $products = Product::query()->ForAdmin($superAdmin)->get();
        
        // Superadmin should see all products
        expect($products)->toHaveCount(2);
    });
    
    it('restricts admin to see only products from vendors in their city', function () {
        // Create vendors in different cities
        $user1 = User::factory()->create();
        $address1 = UserAddress::factory()->create([
            'user_id' => $user1->id,
            'city' => 'City A',
        ]);
        $vendor1 = Vendor::factory()->create(['user_id' => $user1->id]);
        $product1 = Product::factory()->create(['vendor_id' => $vendor1->id]);
        
        $user2 = User::factory()->create();
        $address2 = UserAddress::factory()->create([
            'user_id' => $user2->id,
            'city' => 'City B',
        ]);
        $vendor2 = Vendor::factory()->create(['user_id' => $user2->id]);
        $product2 = Product::factory()->create(['vendor_id' => $vendor2->id]);
        
        // Create admin in City A
        $admin = User::factory()->create(['role' => 'admin']);
        $adminAddress = UserAddress::factory()->create([
            'user_id' => $admin->id,
            'city' => 'City A',
        ]);
        
        // Apply ForAdmin scope
        $products = Product::query()->ForAdmin($admin)->get();
        
        // Admin should only see products from vendors in City A
        expect($products)->toHaveCount(1)
            ->and($products->first()->id)->toBe($product1->id);
    });
});