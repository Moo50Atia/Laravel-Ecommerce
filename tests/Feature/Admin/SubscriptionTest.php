<?php

namespace Tests\Feature\Admin;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_list_subscriptions()
    {
        $user = User::factory()->create();
        $plan = Plan::factory()->create();
        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addDays(30)
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.subscriptions.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.subscriptions.index');
    }

    public function test_admin_can_update_subscription_status()
    {
        $user = User::factory()->create();
        $plan = Plan::factory()->create();
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addDays(30)
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.subscriptions.update', $subscription->id), [
                'status' => 'expired',
                'end_date' => now()->subDay()->toDateString()
            ]);

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'status' => 'expired'
        ]);
    }

    public function test_admin_can_delete_subscription()
    {
        $user = User::factory()->create();
        $plan = Plan::factory()->create();
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'active'
        ]);

        $this->actingAs($this->admin)->delete(route('admin.subscriptions.destroy', $subscription->id));

        $this->assertDatabaseMissing('subscriptions', ['id' => $subscription->id]);
    }
}
