<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicProductTest extends TestCase
{
    use RefreshDatabase;

    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $vendorUser = User::factory()->create(['role' => 'vendor']);
        $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);
        $this->product = Product::factory()->create(['vendor_id' => $vendor->id, 'is_active' => true]);
    }

    public function test_public_can_view_product_list()
    {
        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee($this->product->name);
    }

    public function test_public_can_view_product_details()
    {
        $response = $this->get(route('products.show', $this->product->id));

        $response->assertStatus(200);
        $response->assertSee($this->product->name);
    }

    public function test_authenticated_user_can_add_to_wishlist()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('products.wishlist.add', $this->product->id));

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'product_id' => $this->product->id
        ]);
    }

    public function test_user_can_submit_product_review()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('products.review.store', $this->product->id), [
                'rating' => 5,
                'comment' => 'Excellent product!'
            ]);

        $this->assertDatabaseHas('product_reviews', [
            'product_id' => $this->product->id,
            'user_id' => $user->id,
            'rating' => 5
        ]);
    }

    public function test_search_functionality()
    {
        Product::factory()->create(['name' => 'Searchable Table', 'is_active' => true]);

        $response = $this->get(route('products.search', ['search' => 'Table']));

        $response->assertStatus(200);
        $response->assertSee('Searchable Table');
    }
}
