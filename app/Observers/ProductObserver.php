<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\DashboardService;

class ProductObserver
{
    /**
     * When a product is created, invalidate dashboard cache.
     */
    public function created(Product $product): void
    {
        DashboardService::clearCache();
    }

    /**
     * When a product is updated, invalidate dashboard cache.
     */
    public function updated(Product $product): void
    {
        DashboardService::clearCache();
    }

    /**
     * When a product is deleted, invalidate dashboard cache.
     */
    public function deleted(Product $product): void
    {
        DashboardService::clearCache();
    }
}
