<?php

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('User Model', function () {
    it('can create a user with factory', function () {
        $user = User::factory()->create();
        
        expect($user)->toBeInstanceOf(User::class)
            ->and($user->role)->toBe('user')
            ->and($user->status)->toBe('active');
    });
    
    it('can create a user with address', function () {
        $user = User::factory()->create();
        $address = UserAddress::factory()->create([
            'user_id' => $user->id,
            'city' => 'Test City',
            'state' => 'Test State',
            'country' => 'Test Country',
        ]);
        
        expect($user->addresses)->toBeInstanceOf(UserAddress::class)
            ->and($user->addresses->city)->toBe('Test City');
    });
    
    it('can create users with different roles', function () {
        $user = User::factory()->create(['role' => 'user']);
        $vendor = User::factory()->create(['role' => 'vendor']);
        $admin = User::factory()->create(['role' => 'admin']);
        $superAdmin = User::factory()->create(['role' => 'superadmin']);
        
        expect($user->role)->toBe('user')
            ->and($vendor->role)->toBe('vendor')
            ->and($admin->role)->toBe('admin')
            ->and($superAdmin->role)->toBe('superadmin');
    });
});

describe('User Relationships', function () {
    it('can have orders', function () {
        $user = User::factory()->create();
        $order = \App\Models\Order::factory()->create(['user_id' => $user->id]);
        
        expect($user->orders)->toHaveCount(1)
            ->and($user->orders->first()->id)->toBe($order->id);
    });
    
    it('can have wishlists', function () {
        $user = User::factory()->create();
        $product = \App\Models\Product::factory()->create();
        $wishlist = \App\Models\Wishlist::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
        
        expect($user->wishlists)->toHaveCount(1)
            ->and($user->wishlists->first()->product_id)->toBe($product->id);
    });
    
    it('can have product reviews', function () {
        $user = User::factory()->create();
        $product = \App\Models\Product::factory()->create();
        $review = \App\Models\ProductReview::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 5,
        ]);
        
        expect($user->productReviews)->toHaveCount(1)
            ->and($user->productReviews->first()->rating)->toBe(5);
    });
    
    it('can be a vendor', function () {
        $user = User::factory()->create(['role' => 'vendor']);
        $vendor = \App\Models\Vendor::factory()->create(['user_id' => $user->id]);
        
        expect($user->vendor)->toBeInstanceOf(\App\Models\Vendor::class)
            ->and($user->vendor->id)->toBe($vendor->id);
    });
});

describe('ForAdmin Scope', function () {
    it('allows superadmin to see all users', function () {
        // Create users in different cities
        $user1 = User::factory()->create();
        $address1 = UserAddress::factory()->create([
            'user_id' => $user1->id,
            'city' => 'City A',
        ]);
        
        $user2 = User::factory()->create();
        $address2 = UserAddress::factory()->create([
            'user_id' => $user2->id,
            'city' => 'City B',
        ]);
        
        // Create superadmin
        $superAdmin = User::factory()->create(['role' => 'superadmin']);
        
        // Apply ForAdmin scope
        $users = User::query()->ForAdmin($superAdmin)->get();
        
        // Superadmin should see all users
        expect($users)->toHaveCount(3); // 2 users + 1 superadmin
    });
    
    it('restricts admin to see only users in their city', function () {
        // Create users in different cities
        $user1 = User::factory()->create();
        $address1 = UserAddress::factory()->create([
            'user_id' => $user1->id,
            'city' => 'City A',
        ]);
        
        $user2 = User::factory()->create();
        $address2 = UserAddress::factory()->create([
            'user_id' => $user2->id,
            'city' => 'City B',
        ]);
        
        // Create admin in City A
        $admin = User::factory()->create(['role' => 'admin']);
        $adminAddress = UserAddress::factory()->create([
            'user_id' => $admin->id,
            'city' => 'City A',
        ]);
        
        // Apply ForAdmin scope
        $users = User::query()->ForAdmin($admin)->get();
        
        // Admin should only see users in City A
        expect($users)->toHaveCount(2) // admin + user1
            ->and($users->pluck('id'))->toContain($user1->id)
            ->and($users->pluck('id'))->not->toContain($user2->id);
    });
});