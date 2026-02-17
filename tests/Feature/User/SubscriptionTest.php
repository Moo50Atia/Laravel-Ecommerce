<?php

namespace Tests\Feature\User;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Plan $plan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'user']);
        $this->plan = Plan::create([
            'name' => 'Premium User',
            'type' => 'user',
            'price' => 19.99,
            'duration_days' => 30,
            'is_active' => true,
            'features' => ['No Ads', 'Early Access']
        ]);
    }

    public function test_user_can_view_subscription_page()
    {
        $response = $this->actingAs($this->user)->get(route('user.subscription.index'));

        $response->assertStatus(200);
        $response->assertViewIs('user.subscription.show');
        $response->assertSee('Premium User');
    }

    public function test_user_can_subscribe_to_a_plan()
    {
        $response = $this->actingAs($this->user)
            ->post(route('user.subscription.subscribe'), [
                'plan_id' => $this->plan->id
            ]);

        $response->assertRedirect(route('user.subscription.index'));
        $response->assertSessionHas('success', 'Subscribed successfully!');

        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $this->user->id,
            'plan_id' => $this->plan->id,
            'status' => 'active'
        ]);
    }

    public function test_user_can_cancel_subscription()
    {
        Subscription::create([
            'user_id' => $this->user->id,
            'plan_id' => $this->plan->id,
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addDays(30)
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('user.subscription.cancel'));

        $response->assertRedirect(route('user.subscription.index'));
        $response->assertSessionHas('success', 'Subscription canceled.');

        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $this->user->id,
            'status' => 'canceled'
        ]);
    }
}
