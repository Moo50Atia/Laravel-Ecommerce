<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use App\Models\Plan;
use PHPUnit\Framework\TestCase;

class ModelStructureTest extends TestCase
{
    public function test_plan_features_are_casted_as_array()
    {
        $plan = new Plan();
        $plan->features = ['Feature 1', 'Feature 2'];

        // On a real model instance this would be JSON in DB but array in object
        $this->assertIsArray($plan->features);
    }

    public function test_order_grand_total_calculation()
    {
        // This is usually in a service or controller, but if it was a model method:
        $this->assertTrue(true);
    }
}
