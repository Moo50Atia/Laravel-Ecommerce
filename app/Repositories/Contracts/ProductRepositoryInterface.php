<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface extends RepositoryInterface
{
    /**
     * Get products for admin with filtering
     */
    public function getForAdmin(User $user, array $filters = []): LengthAwarePaginator;

    /**
     * Get top rated products
     */
    public function getTopRated(int $limit = 5): Collection;

    /**
     * Get products with average rating
     */
    public function getWithAverageRating(): Collection;

    /**
     * Get products by category
     */
    public function getByCategory(int $categoryId): Collection;

    /**
     * Get products by vendor
     */
    public function getByVendor(int $vendorId): Collection;

    /**
     * Search products by name or description
     */
    public function search(string $query): Collection;

    /**
     * Get products with stock
     */
    public function getInStock(): Collection;

    /**
     * Get products with images
     */
    public function getWithImages(): Collection;

    /**
     * Get product statistics for admin
     */
    public function getAdminStatistics(User $user): array;

    /**
     * Get special/featured products
     */
    public function getSpecialProducts(int $limit = 10): Collection;

    /**
     * Get products for public listing with pagination
     */
    public function getForPublic(array $filters = []): LengthAwarePaginator;

    /**
     * Get products with variants
     */
    public function getWithVariants(): Collection;

    /**
     * Get products by price range
     */
    public function getByPriceRange(float $minPrice, float $maxPrice): Collection;

    /**
     * Get products with reviews
     */
    public function getWithReviews(): Collection;

    /**
     * Get products by date range
     */
    public function getByDateRange(string $startDate, string $endDate): Collection;

    /**
     * Get recent products
     */
    public function getRecent(int $limit = 10): Collection;
}
