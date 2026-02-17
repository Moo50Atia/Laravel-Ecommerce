<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_loads_successfully()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('public.index');
        $response->assertViewHasAll(['topRatedProducts', 'describeCoupon', 'numofproducts']);
    }

    public function test_user_dashboard_displays_stats()
    {
        $user = User::factory()->create(['role' => 'user']);
        Order::create(['user_id' => $user->id, 'status' => 'pending']);
        Wishlist::create(['user_id' => $user->id, 'product_id' => 1]); // Assuming dummy product ID exists or use factory

        $response = $this->actingAs($user)->get(route('user.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHasAll(['recentOrders', 'wishlistCount']);
    }

    public function test_order_tracking_page_scoped_to_user()
    {
        $user = User::factory()->create(['role' => 'user']);
        $order = Order::create(['user_id' => $user->id, 'status' => 'processing']);

        $response = $this->actingAs($user)->get(route('user.order.track', $order->id));

        $response->assertStatus(200);
        $response->assertViewIs('user.order-tracking');

        // Test unauthorized access
        $otherUser = User::factory()->create();
        $response = $this->actingAs($otherUser)->get(route('user.order.track', $order->id));
        $response->assertStatus(404);
    }
}
