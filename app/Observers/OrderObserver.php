<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;

class OrderObserver
{
    /**
     * When an order is created, log the initial status and invalidate dashboard cache.
     */
    public function created(Order $order): void
    {
        DashboardService::clearCache();

        // Log the initial status
        OrderStatusHistory::create([
            'order_id'    => $order->id,
            'from_status' => null,
            'to_status'   => $order->status ?? 'pending',
            'changed_by'  => Auth::id() ?? 1,
            'notes'       => 'Order created',
        ]);
    }

    /**
     * When an order is updated, log status changes and invalidate dashboard cache.
     */
    public function updated(Order $order): void
    {
        DashboardService::clearCache();

        // If status changed, log in order_status_history
        if ($order->isDirty('status')) {
            OrderStatusHistory::create([
                'order_id'    => $order->id,
                'from_status' => $order->getOriginal('status'),
                'to_status'   => $order->status,
                'changed_by'  => Auth::id() ?? 1,
                'notes'       => null,
            ]);
        }
    }

    /**
     * When an order is deleted, invalidate dashboard cache.
     */
    public function deleted(Order $order): void
    {
        DashboardService::clearCache();
    }
}
