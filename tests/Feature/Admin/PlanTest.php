<?php

namespace Tests\Feature\Admin;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_create_plan()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.plans.store'), [
                'name' => 'Pro Vendor',
                'description' => 'Perfect for growth',
                'price' => 49.99,
                'duration_days' => 30,
                'type' => 'vendor',
                'is_active' => true,
                'features' => ['Unlimited products', 'Priority support']
            ]);

        $response->assertRedirect(route('admin.plans.index'));
        $this->assertDatabaseHas('plans', ['name' => 'Pro Vendor']);
    }

    public function test_admin_can_update_plan()
    {
        $plan = Plan::create([
            'name' => 'Initial Plan',
            'type' => 'user',
            'price' => 10,
            'duration_days' => 30,
            'is_active' => true
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.plans.update', $plan->id), [
                'name' => 'Updated Plan',
                'price' => 15,
                'duration_days' => 30,
                'is_active' => true
            ]);

        $this->assertDatabaseHas('plans', ['id' => $plan->id, 'name' => 'Updated Plan', 'price' => 15]);
    }

    public function test_admin_can_delete_plan()
    {
        $plan = Plan::create([
            'name' => 'Delete Me',
            'type' => 'user',
            'price' => 0,
            'duration_days' => 1,
            'is_active' => true
        ]);

        $this->actingAs($this->admin)->delete(route('admin.plans.destroy', $plan->id));

        $this->assertDatabaseMissing('plans', ['id' => $plan->id]);
    }
}
