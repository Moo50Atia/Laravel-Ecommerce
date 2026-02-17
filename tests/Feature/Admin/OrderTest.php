<?php

namespace Tests\Feature\Admin;

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

    protected User $admin;
    protected Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);

        $user = User::factory()->create(['role' => 'user']);
        $vendorUser = User::factory()->create(['role' => 'vendor']);
        $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);

        $product = Product::factory()->create(['vendor_id' => $vendor->id, 'price' => 50]);

        $this->order = Order::create([
            'user_id' => $user->id,
            'status' => 'processing',
            'grand_total' => 100
        ]);

        OrderItem::create([
            'order_id' => $this->order->id,
            'product_id' => $product->id,
            'vendor_id' => $vendor->id,
            'quantity' => 2,
            'price' => 50
        ]);
    }

    public function test_admin_can_list_orders()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.orders.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.orders.index');
        $response->assertSee($this->order->id);
    }

    public function test_admin_can_show_order()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.orders.show', $this->order->id));

        $response->assertStatus(200);
        $response->assertViewHas('order');
    }

    public function test_admin_can_update_order_status()
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.orders.update', $this->order->id), [
                'status' => 'delivered'
            ]);

        $response->assertRedirect(route('admin.orders.show', $this->order->id));
        $this->assertDatabaseHas('orders', [
            'id' => $this->order->id,
            'status' => 'delivered'
        ]);
    }

    public function test_admin_can_delete_pending_order()
    {
        $this->order->update(['status' => 'pending']);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.orders.destroy', $this->order->id));

        $response->assertRedirect(route('admin.orders.index'));
        $this->assertDatabaseMissing('orders', ['id' => $this->order->id]);
    }

    public function test_admin_cannot_delete_shipped_order()
    {
        $this->order->update(['status' => 'shipped']);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.orders.destroy', $this->order->id));

        $response->assertSessionHas('error', 'لا يمكن حذف طلب قد تم شحنه أو تسليمه.');
        $this->assertDatabaseHas('orders', ['id' => $this->order->id]);
    }
}
