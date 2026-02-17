<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_dashboard_loads_with_statistics()
    {
        // Seed some data
        User::factory()->count(5)->create(['role' => 'user']);
        $vendorUser = User::factory()->create(['role' => 'vendor']);
        $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);
        Product::factory()->count(3)->create(['vendor_id' => $vendor->id]);
        Order::create(['user_id' => User::first()->id, 'status' => 'delivered', 'grand_total' => 1000]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHasAll([
            'totalUsers',
            'totalVendors',
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'monthlyRevenue',
            'recentOrders',
            'topVendors'
        ]);
    }
}
