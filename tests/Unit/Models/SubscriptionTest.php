<?php

namespace Tests\Unit\Models;

use App\Models\Subscription;
use PHPUnit\Framework\TestCase;
use Carbon\Carbon;

class SubscriptionTest extends TestCase
{
    public function test_is_active_returns_true_for_active_within_date()
    {
        $subscription = new Subscription([
            'status' => 'active',
            'end_date' => Carbon::now()->addDays(5)
        ]);

        $this->assertTrue($subscription->isActive());
    }

    public function test_is_active_returns_false_for_canceled()
    {
        $subscription = new Subscription([
            'status' => 'canceled',
            'end_date' => Carbon::now()->addDays(5)
        ]);

        $this->assertFalse($subscription->isActive());
    }

    public function test_is_active_returns_false_for_expired_date()
    {
        $subscription = new Subscription([
            'status' => 'active',
            'end_date' => Carbon::now()->subDay()
        ]);

        $this->assertFalse($subscription->isActive());
    }
}
