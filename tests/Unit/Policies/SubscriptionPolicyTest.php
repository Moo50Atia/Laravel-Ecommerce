<?php

namespace Tests\Unit\Policies;

use App\Models\Subscription;
use App\Models\User;
use App\Policies\SubscriptionPolicy;
use Mockery;
use PHPUnit\Framework\TestCase;

class SubscriptionPolicyTest extends TestCase
{
    protected SubscriptionPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new SubscriptionPolicy();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_view_any_returns_true_for_admin()
    {
        $admin = Mockery::mock(User::class);
        $admin->role = 'admin';

        $user = Mockery::mock(User::class);
        $user->role = 'user';

        $this->assertTrue($this->policy->viewAny($admin));
        $this->assertFalse($this->policy->viewAny($user));
    }

    public function test_view_returns_true_for_admin_or_owner()
    {
        $admin = Mockery::mock(User::class);
        $admin->role = 'admin';

        $owner = Mockery::mock(User::class);
        $owner->role = 'user';
        $owner->id = 1;

        $other = Mockery::mock(User::class);
        $other->role = 'user';
        $other->id = 2;

        $subscription = Mockery::mock(Subscription::class);
        $subscription->user_id = 1;

        $this->assertTrue($this->policy->view($admin, $subscription));
        $this->assertTrue($this->policy->view($owner, $subscription));
        $this->assertFalse($this->policy->view($other, $subscription));
    }

    public function test_create_returns_true_for_everyone()
    {
        $user = Mockery::mock(User::class);
        $this->assertTrue($this->policy->create($user));
    }

    public function test_update_returns_true_only_for_admin()
    {
        $admin = Mockery::mock(User::class);
        $admin->role = 'admin';

        $user = Mockery::mock(User::class);
        $user->role = 'user';

        $subscription = Mockery::mock(Subscription::class);

        $this->assertTrue($this->policy->update($admin, $subscription));
        $this->assertFalse($this->policy->update($user, $subscription));
    }

    public function test_delete_returns_true_only_for_admin()
    {
        $admin = Mockery::mock(User::class);
        $admin->role = 'admin';

        $user = Mockery::mock(User::class);
        $user->role = 'user';

        $subscription = Mockery::mock(Subscription::class);

        $this->assertTrue($this->policy->delete($admin, $subscription));
        $this->assertFalse($this->policy->delete($user, $subscription));
    }
}
