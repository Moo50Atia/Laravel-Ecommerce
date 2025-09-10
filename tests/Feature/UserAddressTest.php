<?php

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('UserAddress Model', function () {
    it('can create a user address with factory', function () {
        $user = User::factory()->create();
        $address = UserAddress::factory()->create([
            'user_id' => $user->id,
            'address_line1' => '123 Test St',
            'city' => 'Test City',
            'state' => 'Test State',
            'postal_code' => '12345',
            'country' => 'Test Country',
            'is_default' => true,
        ]);
        
        expect($address)->toBeInstanceOf(UserAddress::class)
            ->and($address->address_line1)->toBe('123 Test St')
            ->and($address->city)->toBe('Test City')
            ->and($address->is_default)->toBeTrue();
    });
});

describe('UserAddress Relationships', function () {
    it('belongs to a user', function () {
        $user = User::factory()->create();
        $address = UserAddress::factory()->create(['user_id' => $user->id]);
        
        expect($address->user)->toBeInstanceOf(User::class)
            ->and($address->user->id)->toBe($user->id);
    });
    
    it('allows a user to have multiple addresses', function () {
        $user = User::factory()->create();
        $address1 = UserAddress::factory()->create([
            'user_id' => $user->id,
            'city' => 'City A',
        ]);
        $address2 = UserAddress::factory()->create([
            'user_id' => $user->id,
            'city' => 'City B',
        ]);
        
        expect($user->addresses)->toHaveCount(2);
    });
});

describe('UserAddress City Restrictions', function () {
    it('is used for city-based filtering in ForAdmin scope', function () {
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
        
        // Apply ForAdmin scope to User model
        $users = User::query()->ForAdmin($admin)->get();
        
        // Admin should only see users in City A
        expect($users)->toContain($user1)
            ->and($users)->not->toContain($user2);
    });
});