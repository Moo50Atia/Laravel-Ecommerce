<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductVariantTest extends TestCase
{
    use RefreshDatabase;

    protected User $vendorUser;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vendorUser = User::factory()->create(['role' => 'vendor']);
        $vendor = Vendor::factory()->create(['user_id' => $this->vendorUser->id]);
        $this->product = Product::factory()->create(['vendor_id' => $vendor->id]);
    }

    public function test_vendor_can_batch_store_variants()
    {
        $response = $this->actingAs($this->vendorUser)
            ->post(route('vendor.variant.store'), [
                'product_id' => $this->product->id,
                'variants_json' => json_encode([
                    ['option_name' => 'Size', 'option_value' => 'Large', 'price_modifier' => 10, 'stock' => 100],
                    ['option_name' => 'Size', 'option_value' => 'Small', 'price_modifier' => 5, 'stock' => 50]
                ])
            ]);

        $response->assertRedirect(route('products.show', $this->product->id));
        $this->assertDatabaseHas('product_variants', ['product_id' => $this->product->id, 'option_value' => 'Large']);
        $this->assertDatabaseHas('product_variants', ['product_id' => $this->product->id, 'option_value' => 'Small']);
    }

    public function test_vendor_can_batch_update_variants()
    {
        $variant = ProductVariant::factory()->create(['product_id' => $this->product->id]);

        $response = $this->actingAs($this->vendorUser)
            ->put(route('vendor.variant.update', $this->variant->id ?? 1), [ // ID technically ignored in batch loop but route requires it
                'variants' => [
                    ['id' => $variant->id, 'option_name' => 'Color', 'option_value' => 'Red', 'price_modifier' => 0, 'stock' => 10]
                ]
            ]);

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'option_value' => 'Red'
        ]);
    }
}
