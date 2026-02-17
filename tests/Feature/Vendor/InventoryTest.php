<?php

namespace Tests\Feature\Vendor;

use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    protected User $vendorUser;
    protected Vendor $vendor;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vendorUser = User::factory()->create(['role' => 'vendor']);
        $this->vendor = Vendor::factory()->create(['user_id' => $this->vendorUser->id]);

        $this->product = Product::factory()->create(['vendor_id' => $this->vendor->id]);
    }

    public function test_vendor_can_list_own_inventory_movements()
    {
        InventoryMovement::create([
            'product_id' => $this->product->id,
            'quantity' => 10,
            'type' => 'in',
            'user_id' => $this->vendorUser->id
        ]);

        $response = $this->actingAs($this->vendorUser)->get(route('vendor.inventory.index'));

        $response->assertStatus(200);
        $response->assertViewIs('vendor.inventory.index');
        $response->assertSee('Stock added');
    }

    public function test_vendor_can_add_stock_to_own_product()
    {
        $response = $this->actingAs($this->vendorUser)
            ->post(route('vendor.inventory.store'), [
                'product_id' => $this->product->id,
                'quantity' => 5,
                'type' => 'in',
                'notes' => 'New shipment'
            ]);

        $response->assertRedirect(route('vendor.inventory.index'));
        $this->assertDatabaseHas('inventory_movements', [
            'product_id' => $this->product->id,
            'quantity' => 5
        ]);
    }

    public function test_vendor_cannot_add_stock_to_foreign_product()
    {
        $otherVendorUser = User::factory()->create(['role' => 'vendor']);
        $otherVendor = Vendor::factory()->create(['user_id' => $otherVendorUser->id]);
        $otherProduct = Product::factory()->create(['vendor_id' => $otherVendor->id]);

        $response = $this->actingAs($this->vendorUser)
            ->post(route('vendor.inventory.store'), [
                'product_id' => $otherProduct->id,
                'quantity' => 5,
                'type' => 'in'
            ]);

        $response->assertStatus(403);
    }
}
