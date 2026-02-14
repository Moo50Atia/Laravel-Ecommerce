<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CouponRepositoryInterface extends RepositoryInterface
{
    /**
     * Get coupons with products
     */
    public function getWithProducts(): Collection;

    /**
     * Get active coupons
     */
    public function getActive(): Collection;

    /**
     * Get expired coupons
     */
    public function getExpired(): Collection;

    /**
     * Get coupons by type
     */
    public function getByType(string $type): Collection;

    /**
     * Get coupons by status
     */
    public function getByStatus(string $status): Collection;

    /**
     * Get coupons by code
     */
    public function getByCode(string $code): ?object;

    /**
     * Search coupons
     */
    public function search(string $query): Collection;

    /**
     * Get coupon statistics
     */
    public function getStatistics(): array;

    /**
     * Get coupons for admin with filtering
     */
    public function getForAdmin(array $filters = []): LengthAwarePaginator;

    /**
     * Get valid coupons for date
     */
    public function getValidForDate(string $date): Collection;

    /**
     * Get coupons by minimum amount
     */
    public function getByMinimumAmount(float $amount): Collection;

    /**
     * Get coupons by usage limit
     */
    public function getByUsageLimit(int $limit): Collection;

    /**
     * Get recent active coupons
     */
    public function getRecentActive(int $limit = 5): Collection;
}
