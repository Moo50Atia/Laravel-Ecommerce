<?php

namespace Tests\Unit\Policies;

use App\Models\Order;
use App\Models\User;
use App\Models\Vendor;
use App\Policies\OrderPolicy;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mockery;
use PHPUnit\Framework\TestCase;

class OrderPolicyTest extends TestCase
{
    protected OrderPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new OrderPolicy();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_view_any_returns_true()
    {
        $user = Mockery::mock(User::class);
        $this->assertTrue($this->policy->viewAny($user));
    }

    public function test_view_returns_true_for_admin()
    {
        $user = Mockery::mock(User::class);
        $user->role = 'admin';
        $order = Mockery::mock(Order::class);

        $this->assertTrue($this->policy->view($user, $order));
    }

    public function test_view_returns_true_for_order_owner()
    {
        $user = Mockery::mock(User::class);
        $user->role = 'user';
        $user->id = 1;

        $order = Mockery::mock(Order::class);
        $order->user_id = 1;

        $this->assertTrue($this->policy->view($user, $order));
    }

    public function test_view_returns_false_for_another_user()
    {
        $user = Mockery::mock(User::class);
        $user->role = 'user';
        $user->id = 1;

        $order = Mockery::mock(Order::class);
        $order->user_id = 2;

        $this->assertFalse($this->policy->view($user, $order));
    }

    public function test_view_returns_true_for_vendor_if_owns_product()
    {
        $vendor = Mockery::mock(Vendor::class);
        $vendor->id = 10;

        $user = Mockery::mock(User::class);
        $user->role = 'vendor';
        $user->vendor = $vendor;

        $order = Mockery::mock(Order::class);
        $itemsRelation = Mockery::mock(HasMany::class);

        $order->shouldReceive('items')->andReturn($itemsRelation);
        $itemsRelation->shouldReceive('where')->with('vendor_id', 10)->andReturnSelf();
        $itemsRelation->shouldReceive('exists')->andReturn(true);

        $this->assertTrue($this->policy->view($user, $order));
    }

    public function test_update_returns_true_for_admin()
    {
        $user = Mockery::mock(User::class);
        $user->role = 'admin';
        $order = Mockery::mock(Order::class);

        $this->assertTrue($this->policy->update($user, $order));
    }

    public function test_update_returns_false_for_user()
    {
        $user = Mockery::mock(User::class);
        $user->role = 'user';
        $order = Mockery::mock(Order::class);

        $this->assertFalse($this->policy->update($user, $order));
    }

    public function test_delete_returns_true_only_for_admin()
    {
        $admin = Mockery::mock(User::class);
        $admin->role = 'admin';

        $user = Mockery::mock(User::class);
        $user->role = 'user';

        $order = Mockery::mock(Order::class);

        $this->assertTrue($this->policy->delete($admin, $order));
        $this->assertFalse($this->policy->delete($user, $order));
    }
}
