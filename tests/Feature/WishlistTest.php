<?php

use App\Models\User;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Wishlist Model', function () {
    it('can create a wishlist with factory', function () {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $wishlist = Wishlist::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
        
        expect($wishlist)->toBeInstanceOf(Wishlist::class)
            ->and($wishlist->user_id)->toBe($user->id)
            ->and($wishlist->product_id)->toBe($product->id);
    });
});

describe('Wishlist Relationships', function () {
    it('belongs to a user', function () {
        $user = User::factory()->create();
        $wishlist = Wishlist::factory()->create(['user_id' => $user->id]);
        
        expect($wishlist->user)->toBeInstanceOf(User::class)
            ->and($wishlist->user->id)->toBe($user->id);
    });
    
    it('belongs to a product', function () {
        $product = Product::factory()->create();
        $wishlist = Wishlist::factory()->create(['product_id' => $product->id]);
        
        expect($wishlist->product)->toBeInstanceOf(Product::class)
            ->and($wishlist->product->id)->toBe($product->id);
    });
});