<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendorTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_create_vendor_and_user_as_transaction()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.vendors.store'), [
                'name' => 'Vendor One', // User name
                'email' => 'vendor1@example.com',
                'password' => 'password123',
                'store_name' => 'My Store',
                'description' => 'Store description',
                'commission_rate' => 10.0
            ]);

        $response->assertRedirect(route('admin.vendors.index'));
        $this->assertDatabaseHas('users', ['email' => 'vendor1@example.com', 'role' => 'vendor']);
        $this->assertDatabaseHas('vendors', ['store_name' => 'My Store']);
    }

    public function test_admin_can_approve_vendor()
    {
        $vendorUser = User::factory()->create(['role' => 'vendor', 'status' => 'pending']);
        $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.vendors.approve', $vendor->id));

        $this->assertEquals('active', $vendorUser->fresh()->status);
    }

    public function test_admin_can_suspend_vendor()
    {
        $vendorUser = User::factory()->create(['role' => 'vendor', 'status' => 'active']);
        $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.vendors.suspend', $vendor->id));

        $this->assertEquals('suspended', $vendorUser->fresh()->status);
    }

    public function test_admin_can_delete_vendor_and_user()
    {
        $vendorUser = User::factory()->create(['role' => 'vendor']);
        $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);

        $this->actingAs($this->admin)->delete(route('admin.vendors.destroy', $vendor->id));

        $this->assertDatabaseMissing('vendors', ['id' => $vendor->id]);
        $this->assertDatabaseMissing('users', ['id' => $vendorUser->id]);
    }
}
