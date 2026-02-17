<?php

namespace Tests\Feature\Admin;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_list_coupons()
    {
        $coupon = Coupon::create([
            'code' => 'SAVE50',
            'discount_type' => 'percentage',
            'discount_value' => 50,
            'is_active' => true,
            'starts_at' => now(),
            'expires_at' => now()->addDays(7)
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.coupons.index'));

        $response->assertStatus(200);
        $response->assertSee('SAVE50');
    }

    public function test_admin_can_create_coupon()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.coupons.store'), [
                'code' => 'WELCOME10',
                'discount_type' => 'fixed',
                'discount_value' => 10,
                'is_active' => true,
                'starts_at' => now()->toDateString(),
                'expires_at' => now()->addDays(30)->toDateString()
            ]);

        $response->assertRedirect(route('admin.coupons.index'));
        $this->assertDatabaseHas('coupons', ['code' => 'WELCOME10']);
    }

    public function test_coupon_requires_valid_discount_value()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.coupons.store'), [
                'code' => 'BAD',
                'discount_type' => 'percentage',
                'discount_value' => 150 // > 100
            ]);

        $response->assertSessionHasErrors('discount_value');
    }

    public function test_admin_can_delete_coupon()
    {
        $coupon = Coupon::create([
            'code' => 'DELETE_ME',
            'discount_type' => 'fixed',
            'discount_value' => 5,
            'is_active' => true
        ]);

        $this->actingAs($this->admin)->delete(route('admin.coupons.destroy', $coupon->id));

        $this->assertDatabaseMissing('coupons', ['id' => $coupon->id]);
    }
}
