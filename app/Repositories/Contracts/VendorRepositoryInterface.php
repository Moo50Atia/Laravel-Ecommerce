<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface VendorRepositoryInterface extends RepositoryInterface
{
    /**
     * Get vendors with user information
     */
    public function getWithUser(): Collection;

    /**
     * Get vendor list for dropdowns
     */
    public function getVendorList(): Collection;

    /**
     * Get vendors with products
     */
    public function getWithProducts(): Collection;

    /**
     * Get vendors with orders
     */
    public function getWithOrders(): Collection;

    /**
     * Get vendors by rating range
     */
    public function getByRatingRange(float $minRating, float $maxRating): Collection;

    /**
     * Get top rated vendors
     */
    public function getTopRated(int $limit = 10): Collection;

    /**
     * Get vendors by commission rate
     */
    public function getByCommissionRate(float $minRate, float $maxRate): Collection;

    /**
     * Get vendors with images
     */
    public function getWithImages(): Collection;

    /**
     * Search vendors by store name or description
     */
    public function search(string $query): Collection;

    /**
     * Get vendor statistics
     */
    public function getStatistics(): array;

    /**
     * Get vendors for admin with filtering
     */
    public function getForAdmin(\App\Models\User $user, array $filters = []): LengthAwarePaginator;

    /**
     * Get vendors with product count
     */
    public function getWithProductCount(): Collection;

    /**
     * Get vendors with order count
     */
    public function getWithOrderCount(): Collection;

    /**
     * Get vendors with sales statistics
     */
    public function getWithSalesStats(): Collection;

    /**
     * Get recent vendors
     */
    public function getRecent(int $limit = 10): Collection;

    /**
     * Get vendors by slug
     */
    public function getBySlug(string $slug): Collection;

    /**
     * Get vendors with high commission rates
     */
    public function getHighCommissionVendors(): Collection;

    /**
     * Get vendors with low commission rates
     */
    public function getLowCommissionVendors(): Collection;

    /**
     * Get vendor count by rating range
     */
    public function getCountByRatingRange(float $minRating, float $maxRating): int;

    /**
     * Get vendors with their total sales
     */
    public function getWithTotalSales(): Collection;
}
