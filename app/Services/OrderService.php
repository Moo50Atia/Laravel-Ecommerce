<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderStatusHistory;

/**
 * Business logic for order operations — moved out of controllers.
 */
class OrderService
{
    /**
     * Calculate grand total for an order.
     */
    public function calculateGrandTotal(Order $order): float
    {
        $total = $order->total_amount;
        $discount = $order->discount_amount ?? 0;
        $shipping = $order->shipping_amount ?? 0;

        return round($total - $discount + $shipping, 2);
    }

    /**
     * Check if an order can be deleted based on its status.
     */
    public function canDelete(Order $order): bool
    {
        return in_array($order->status, ['pending', 'cancelled']);
    }

    /**
     * Check if a status transition is valid.
     */
    public function isValidTransition(string $from, string $to): bool
    {
        $allowed = [
            'pending'    => ['processing', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped'    => ['delivered'],
            'delivered'  => ['completed'],
        ];

        return in_array($to, $allowed[$from] ?? []);
    }
}
