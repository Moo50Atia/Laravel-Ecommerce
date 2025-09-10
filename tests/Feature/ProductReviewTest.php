<?php

use App\Models\User;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('ProductReview Model', function () {
    it('can create a product review with factory', function () {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $review = ProductReview::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 4,
            'comment' => 'Great product!',
        ]);
        
        expect($review)->toBeInstanceOf(ProductReview::class)
            ->and($review->rating)->toBe(4)
            ->and($review->comment)->toBe('Great product!');
    });
});

describe('ProductReview Relationships', function () {
    it('belongs to a user', function () {
        $user = User::factory()->create();
        $review = ProductReview::factory()->create(['user_id' => $user->id]);
        
        expect($review->user)->toBeInstanceOf(User::class)
            ->and($review->user->id)->toBe($user->id);
    });
    
    it('belongs to a product', function () {
        $product = Product::factory()->create();
        $review = ProductReview::factory()->create(['product_id' => $product->id]);
        
        expect($review->product)->toBeInstanceOf(Product::class)
            ->and($review->product->id)->toBe($product->id);
    });
});

describe('ProductReview Permissions', function () {
    it('allows users to create reviews for products they purchased', function () {
        $user = User::factory()->create(['role' => 'user']);
        $product = Product::factory()->create(['status' => 'active']);
        
        // Simulate a completed order with this product
        $order = \App\Models\Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
        ]);
        
        \App\Models\OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);
        
        $response = $this->actingAs($user)->post('/product/review', [
            'product_id' => $product->id,
            'rating' => 4,
            'comment' => 'Great product!',
        ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('product_reviews', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 4,
        ]);
    });
    
    it('prevents vendors from reviewing their own products', function () {
        $user = User::factory()->create();
        $vendor = \App\Models\Vendor::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create(['vendor_id' => $vendor->id]);
        
        $response = $this->actingAs($user)->post('/product/review', [
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'My own product is great!',
        ]);
        
        $response->assertStatus(403);
    });
});