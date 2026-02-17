<?php

namespace Tests\Feature\Vendor;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected User $vendorUser;
    protected Vendor $vendor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vendorUser = User::factory()->create(['role' => 'vendor']);
        $this->vendor = Vendor::factory()->create(['user_id' => $this->vendorUser->id]);
    }

    public function test_vendor_can_view_settings_page()
    {
        $response = $this->actingAs($this->vendorUser)->get(route('vendor.settings'));

        $response->assertStatus(200);
        $response->assertViewIs('vendor.settings');
    }

    public function test_vendor_can_update_store_settings()
    {
        $response = $this->actingAs($this->vendorUser)
            ->post(route('vendor.settings.update'), [
                'store_name' => 'Updated Store Name',
                'description' => 'Updated description',
                'store_email' => 'store@example.com',
                'store_phone' => '123456789'
            ]);

        $response->assertRedirect(route('vendor.settings'));
        $this->assertDatabaseHas('vendors', [
            'id' => $this->vendor->id,
            'store_name' => 'Updated Store Name'
        ]);
    }
}
