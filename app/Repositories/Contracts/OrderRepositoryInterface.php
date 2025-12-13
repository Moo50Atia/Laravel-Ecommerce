<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface extends RepositoryInterface
{
    /**
     * Get orders for admin with filtering
     */
    public function getForAdmin(User $user, array $filters = []): LengthAwarePaginator;

    /**
     * Get orders by user
     */
    public function getByUser(int $userId): Collection;

    /**
     * Get orders by vendor
     */
    public function getByVendor(int $vendorId): Collection;

    /**
     * Get orders by status
     */
    public function getByStatus(string $status): Collection;

    /**
     * Get orders by payment status
     */
    public function getByPaymentStatus(string $paymentStatus): Collection;

    /**
     * Get orders by date range
     */
    public function getByDateRange(string $startDate, string $endDate): Collection;

    /**
     * Get order statistics for admin
     */
    public function getAdminStatistics(User $user): array;

    /**
     * Get order statistics by vendor
     */
    public function getVendorStatistics(int $vendorId): array;

    /**
     * Get recent orders
     */
    public function getRecent(int $limit = 10): Collection;

    /**
     * Get orders with items
     */
    public function getWithItems(): Collection;

    /**
     * Get orders with products
     */
    public function getWithProducts(): Collection;

    /**
     * Get orders by payment method
     */
    public function getByPaymentMethod(string $paymentMethod): Collection;

    /**
     * Get total sales amount
     */
    public function getTotalSales(User $user = null): float;

    /**
     * Get orders count by status
     */
    public function getCountByStatus(string $status, User $user = null): int;

    /**
     * Get orders for dashboard
     */
    public function getDashboardData(User $user = null): array;

    /**
     * Update order status
     */
    public function updateStatus(int $orderId, string $status): bool;

    /**
     * Update payment status
     */
    public function updatePaymentStatus(int $orderId, string $paymentStatus): bool;

    /**
     * Get statuses for admin
     */
    public function getStatusesForAdmin(User $user): Collection;

    /**
     * Get payment methods for admin
     */
    public function getPaymentMethodsForAdmin(User $user): Collection;

    /**
     * Get payment statuses for admin
     */
    public function getPaymentStatusesForAdmin(User $user): Collection;
    public function getTotalRevenue(User $user = null): float;
}
