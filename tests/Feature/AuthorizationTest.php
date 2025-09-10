<?php

use App\Models\User;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Order;
use App\Models\Wishlist;
use App\Models\ProductReview;
use App\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('User Role Permissions', function () {
    it('allows users to register and login', function () {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'phone' => '1234567890',
        ]);
        
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    });
    
    it('allows users to browse products', function () {
        $user = User::factory()->create(['role' => 'user']);
        $product = Product::factory()->create(['status' => 'active']);
        
        $response = $this->actingAs($user)->get('/products');
        $response->assertStatus(200);
        $response->assertSee($product->name);
    });
    
    it('allows users to add products to wishlist', function () {
        $user = User::factory()->create(['role' => 'user']);
        $product = Product::factory()->create(['status' => 'active']);
        
        $response = $this->actingAs($user)->post('/wishlist/add', [
            'product_id' => $product->id,
        ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    });
    
    it('allows users to place orders', function () {
        $user = User::factory()->create(['role' => 'user']);
        $product = Product::factory()->create(['status' => 'active', 'price' => 100]);
        
        // Add to cart first
        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
        
        // Place order
        $response = $this->actingAs($user)->post('/checkout', [
            'payment_method' => 'cod',
            'shipping_address' => 'Test Address',
            'billing_address' => 'Test Address',
        ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'status' => 'pending',
        ]);
    });
    
    it('allows users to write product reviews', function () {
        $user = User::factory()->create(['role' => 'user']);
        $product = Product::factory()->create(['status' => 'active']);
        
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
    
    it('prevents users from creating products', function () {
        $user = User::factory()->create(['role' => 'user']);
        
        $response = $this->actingAs($user)->post('/admin/products', [
            'name' => 'Test Product',
            'price' => 99.99,
        ]);
        
        $response->assertStatus(403);
    });
});

describe('Vendor Role Permissions', function () {
    it('allows vendors to create products', function () {
        $user = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        
        $response = $this->actingAs($user)->post('/vendor/products', [
            'name' => 'Test Product',
            'price' => 99.99,
            'description' => 'Test description',
        ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('products', [
            'vendor_id' => $vendor->id,
            'name' => 'Test Product',
        ]);
    });
    
    it('allows vendors to update their products', function () {
        $user = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create(['vendor_id' => $vendor->id]);
        
        $response = $this->actingAs($user)->put("/vendor/products/{$product->id}", [
            'name' => 'Updated Product',
            'price' => 149.99,
        ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
        ]);
    });
    
    it('allows vendors to delete their products', function () {
        $user = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create(['vendor_id' => $vendor->id]);
        
        $response = $this->actingAs($user)->delete("/vendor/products/{$product->id}");
        
        $response->assertStatus(200);
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    });
    
    it('prevents vendors from modifying other vendors products', function () {
        $user1 = User::factory()->create();
        $vendor1 = Vendor::factory()->create(['user_id' => $user1->id]);
        
        $user2 = User::factory()->create();
        $vendor2 = Vendor::factory()->create(['user_id' => $user2->id]);
        $product = Product::factory()->create(['vendor_id' => $vendor2->id]);
        
        $response = $this->actingAs($user1)->put("/vendor/products/{$product->id}", [
            'name' => 'Unauthorized Update',
        ]);
        
        $response->assertStatus(403);
    });
    
    it('prevents vendors from placing orders as buyers', function () {
        $user = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create(['status' => 'active']);
        
        // Try to place an order as a vendor
        $response = $this->actingAs($user)->post('/checkout', [
            'payment_method' => 'cod',
            'shipping_address' => 'Test Address',
            'billing_address' => 'Test Address',
        ]);
        
        $response->assertStatus(403);
    });
});

describe('Admin Role Permissions', function () {
    it('allows admins to see vendors in their city', function () {
        // Create admin in City A
        $admin = User::factory()->create(['role' => 'admin']);
        $adminAddress = UserAddress::factory()->create([
            'user_id' => $admin->id,
            'city' => 'City A',
        ]);
        
        // Create vendor in City A
        $user1 = User::factory()->create();
        $address1 = UserAddress::factory()->create([
            'user_id' => $user1->id,
            'city' => 'City A',
        ]);
        $vendor1 = Vendor::factory()->create(['user_id' => $user1->id]);
        
        // Create vendor in City B
        $user2 = User::factory()->create();
        $address2 = UserAddress::factory()->create([
            'user_id' => $user2->id,
            'city' => 'City B',
        ]);
        $vendor2 = Vendor::factory()->create(['user_id' => $user2->id]);
        
        $response = $this->actingAs($admin)->get('/admin/vendors');
        
        $response->assertStatus(200);
        $response->assertSee($vendor1->store_name);
        $response->assertDontSee($vendor2->store_name);
    });
    
    it('allows admins to see products in their city', function () {
        // Create admin in City A
        $admin = User::factory()->create(['role' => 'admin']);
        $adminAddress = UserAddress::factory()->create([
            'user_id' => $admin->id,
            'city' => 'City A',
        ]);
        
        // Create vendor in City A with product
        $user1 = User::factory()->create();
        $address1 = UserAddress::factory()->create([
            'user_id' => $user1->id,
            'city' => 'City A',
        ]);
        $vendor1 = Vendor::factory()->create(['user_id' => $user1->id]);
        $product1 = Product::factory()->create([
            'vendor_id' => $vendor1->id,
            'name' => 'City A Product',
        ]);
        
        // Create vendor in City B with product
        $user2 = User::factory()->create();
        $address2 = UserAddress::factory()->create([
            'user_id' => $user2->id,
            'city' => 'City B',
        ]);
        $vendor2 = Vendor::factory()->create(['user_id' => $user2->id]);
        $product2 = Product::factory()->create([
            'vendor_id' => $vendor2->id,
            'name' => 'City B Product',
        ]);
        
        $response = $this->actingAs($admin)->get('/admin/products');
        
        $response->assertStatus(200);
        $response->assertSee('City A Product');
        $response->assertDontSee('City B Product');
    });
});

describe('Super Admin Role Permissions', function () {
    it('allows super admins to see all vendors', function () {
        // Create super admin
        $superAdmin = User::factory()->create(['role' => 'superadmin']);
        
        // Create vendors in different cities
        $user1 = User::factory()->create();
        $address1 = UserAddress::factory()->create([
            'user_id' => $user1->id,
            'city' => 'City A',
        ]);
        $vendor1 = Vendor::factory()->create([
            'user_id' => $user1->id,
            'store_name' => 'Store A',
        ]);
        
        $user2 = User::factory()->create();
        $address2 = UserAddress::factory()->create([
            'user_id' => $user2->id,
            'city' => 'City B',
        ]);
        $vendor2 = Vendor::factory()->create([
            'user_id' => $user2->id,
            'store_name' => 'Store B',
        ]);
        
        $response = $this->actingAs($superAdmin)->get('/admin/vendors');
        
        $response->assertStatus(200);
        $response->assertSee('Store A');
        $response->assertSee('Store B');
    });
    
    it('allows super admins to see all products', function () {
        // Create super admin
        $superAdmin = User::factory()->create(['role' => 'superadmin']);
        
        // Create vendors in different cities with products
        $user1 = User::factory()->create();
        $address1 = UserAddress::factory()->create([
            'user_id' => $user1->id,
            'city' => 'City A',
        ]);
        $vendor1 = Vendor::factory()->create(['user_id' => $user1->id]);
        $product1 = Product::factory()->create([
            'vendor_id' => $vendor1->id,
            'name' => 'City A Product',
        ]);
        
        $user2 = User::factory()->create();
        $address2 = UserAddress::factory()->create([
            'user_id' => $user2->id,
            'city' => 'City B',
        ]);
        $vendor2 = Vendor::factory()->create(['user_id' => $user2->id]);
        $product2 = Product::factory()->create([
            'vendor_id' => $vendor2->id,
            'name' => 'City B Product',
        ]);
        
        $response = $this->actingAs($superAdmin)->get('/admin/products');
        
        $response->assertStatus(200);
        $response->assertSee('City A Product');
        $response->assertSee('City B Product');
    });
});