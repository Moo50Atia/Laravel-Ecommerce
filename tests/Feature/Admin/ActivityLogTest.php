<?php

namespace Tests\Feature\Admin;

use App\Models\ActivityLog;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_activity_logs()
    {
        ActivityLog::create([
            'user_id' => $this->admin->id,
            'action' => 'Logged in',
            'ip_address' => '127.0.0.1'
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.activity-logs.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.activity-logs.index');
        $response->assertSee('Logged in');
    }

    public function test_admin_can_view_inventory_logs()
    {
        $vendorUser = User::factory()->create(['role' => 'vendor']);
        $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);
        $product = Product::factory()->create(['vendor_id' => $vendor->id]);

        InventoryMovement::create([
            'product_id' => $product->id,
            'quantity' => 10,
            'type' => 'in',
            'user_id' => $this->admin->id
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.inventory.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.inventory.index');
        $response->assertSee('Stock added'); // Based on helper/view logic
    }
}
