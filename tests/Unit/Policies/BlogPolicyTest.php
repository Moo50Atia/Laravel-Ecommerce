<?php

namespace Tests\Unit\Policies;

use App\Models\Blog;
use App\Models\User;
use App\Policies\BlogPolicy;
use Mockery;
use PHPUnit\Framework\TestCase;

class BlogPolicyTest extends TestCase
{
    protected BlogPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new BlogPolicy();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_view_any_returns_true_for_authorized_roles()
    {
        $admin = Mockery::mock(User::class);
        $admin->role = 'admin';

        $vendor = Mockery::mock(User::class);
        $vendor->role = 'vendor';

        $superAdmin = Mockery::mock(User::class);
        $superAdmin->role = 'super_admin';

        $user = Mockery::mock(User::class);
        $user->role = 'user';

        $this->assertTrue($this->policy->viewAny($admin));
        $this->assertTrue($this->policy->viewAny($vendor));
        $this->assertTrue($this->policy->viewAny($superAdmin));
        $this->assertFalse($this->policy->viewAny($user));
    }

    public function test_create_returns_true_for_authorized_roles()
    {
        $admin = Mockery::mock(User::class);
        $admin->role = 'admin';

        $vendor = Mockery::mock(User::class);
        $vendor->role = 'vendor';

        $user = Mockery::mock(User::class);
        $user->role = 'user';

        $this->assertTrue($this->policy->create($admin));
        $this->assertTrue($this->policy->create($vendor));
        $this->assertFalse($this->policy->create($user));
    }

    // Note: view, update, delete use Eloquent scopes (static calls) 
    // and are better suited for Feature tests with RefreshDatabase.
    // They are included in the Priority 1 (Phase 3/4) Feature tests.
}
