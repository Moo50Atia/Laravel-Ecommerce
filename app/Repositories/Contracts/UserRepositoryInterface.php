<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * Get users by role
     */
    public function getByRole(string $role): Collection;

    /**
     * Get all customers
     */
    public function getCustomers(): Collection;

    /**
     * Get all vendors
     */
    public function getVendors(): Collection;

    /**
     * Get all admins
     */
    public function getAdmins(): Collection;

    /**
     * Get users with addresses
     */
    public function getWithAddresses(): Collection;

    /**
     * Get users with vendor information
     */
    public function getWithVendor(): Collection;

    /**
     * Get users with orders
     */
    public function getWithOrders(): Collection;

    /**
     * Get users by status
     */
    public function getByStatus(string $status): Collection;

    /**
     * Get users by city
     */
    public function getByCity(string $city): Collection;

    /**
     * Search users by name or email
     */
    public function search(string $query): Collection;

    /**
     * Get user statistics
     */
    public function getStatistics(): array;

    /**
     * Get recent users
     */
    public function getRecent(int $limit = 10): Collection;

    /**
     * Get users with wishlist
     */
    public function getWithWishlist(): Collection;

    /**
     * Get users with subscriptions
     */
    public function getWithSubscriptions(): Collection;

    /**
     * Get active users
     */
    public function getActive(): Collection;

    /**
     * Get inactive users
     */
    public function getInactive(): Collection;

    /**
     * Get users by role and status
     */
    public function getByRoleAndStatus(string $role, string $status): Collection;

    /**
     * Get users for admin with filtering
     */
    public function getForAdmin(array $filters = []): LengthAwarePaginator;

    /**
     * Get user count by role
     */
    public function getCountByRole(string $role): int;

    /**
     * Get user count by status
     */
    public function getCountByStatus(string $status): int;

    /**
     * Get users by registration date range
     */
    public function getByRegistrationDateRange(string $startDate, string $endDate): Collection;
}
