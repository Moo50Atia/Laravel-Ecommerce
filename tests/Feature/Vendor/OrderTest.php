<?php

namespace Tests\Feature\Vendor;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
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

    public function test_vendor_can_view_dashboard_stats()
    {
        Order::create(['user_id' => User::factory()->create()->id, 'status' => 'delivered', 'vendor_id' => $this->vendor->id]);

        $response = $this->actingAs($this->vendorUser)->get(route('vendor.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHasAll(['all_products', 'all_orders', 'current_orders', 'canceld_oders']);
    }

    public function test_vendor_order_index_filters_by_vendor()
    {
        $otherVendor = Vendor::factory()->create(['user_id' => User::factory()->create(['role' => 'vendor'])->id]);

        $myOrder = Order::create(['user_id' => 1, 'vendor_id' => $this->vendor->id, 'status' => 'pending']);
        $otherOrder = Order::create(['user_id' => 1, 'vendor_id' => $otherVendor->id, 'status' => 'pending']);

        $response = $this->actingAs($this->vendorUser)->get(route('vendor.orders.index'));

        $response->assertStatus(200);
        $response->assertSee($myOrder->id);
        $response->assertDontSee($otherOrder->id);
    }
}
