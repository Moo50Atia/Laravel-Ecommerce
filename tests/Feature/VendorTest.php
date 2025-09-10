<?php

use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Vendor Model', function () {
    it('can create a vendor with factory', function () {
        $user = User::factory()->create();
        $vendor = Vendor::factory()->create([
            'user_id' => $user->id,
            'store_name' => 'Test Store',
            'commission_rate' => 10.5,
        ]);
        
        expect($vendor)->toBeInstanceOf(Vendor::class)
            ->and($vendor->store_name)->toBe('Test Store')
            ->and($vendor->commission_rate)->toBe(10.5);
    });
});

describe('Vendor Relationships', function () {
    it('belongs to a user', function () {
        $user = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        
        expect($vendor->user)->toBeInstanceOf(User::class)
            ->and($vendor->user->id)->toBe($user->id);
    });
    
    it('has many products', function () {
        $vendor = Vendor::factory()->create();
        $product1 = Product::factory()->create(['vendor_id' => $vendor->id]);
        $product2 = Product::factory()->create(['vendor_id' => $vendor->id]);
        
        expect($vendor->products)->toHaveCount(2);
    });
});

describe('Vendor ForAdmin Scope', function () {
    it('allows superadmin to see all vendors', function () {
        // Create users in different cities
        $user1 = User::factory()->create();
        $address1 = UserAddress::factory()->create([
            'user_id' => $user1->id,
            'city' => 'City A',
        ]);
        $vendor1 = Vendor::factory()->create(['user_id' => $user1->id]);
        
        $user2 = User::factory()->create();
        $address2 = UserAddress::factory()->create([
            'user_id' => $user2->id,
            'city' => 'City B',
        ]);
        $vendor2 = Vendor::factory()->create(['user_id' => $user2->id]);
        
        // Create superadmin
        $superAdmin = User::factory()->create(['role' => 'superadmin']);
        
        // Apply ForAdmin scope
        $vendors = Vendor::query()->ForAdmin($superAdmin)->get();
        
        // Superadmin should see all vendors
        expect($vendors)->toHaveCount(2);
    });
    
    it('restricts admin to see only vendors in their city', function () {
        // Create users in different cities
        $user1 = User::factory()->create();
        $address1 = UserAddress::factory()->create([
            'user_id' => $user1->id,
            'city' => 'City A',
        ]);
        $vendor1 = Vendor::factory()->create(['user_id' => $user1->id]);
        
        $user2 = User::factory()->create();
        $address2 = UserAddress::factory()->create([
            'user_id' => $user2->id,
            'city' => 'City B',
        ]);
        $vendor2 = Vendor::factory()->create(['user_id' => $user2->id]);
        
        // Create admin in City A
        $admin = User::factory()->create(['role' => 'admin']);
        $adminAddress = UserAddress::factory()->create([
            'user_id' => $admin->id,
            'city' => 'City A',
        ]);
        
        // Apply ForAdmin scope
        $vendors = Vendor::query()->ForAdmin($admin)->get();
        
        // Admin should only see vendors in City A
        expect($vendors)->toHaveCount(1)
            ->and($vendors->first()->id)->toBe($vendor1->id);
    });
});