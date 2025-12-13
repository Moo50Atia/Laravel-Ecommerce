<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface extends RepositoryInterface
{
    /**
     * Get categories with products count
     */
    public function getWithProductsCount(): Collection;

    /**
     * Get categories with products
     */
    public function getWithProducts(): Collection;

    /**
     * Get categories by status
     */
    public function getByStatus(string $status): Collection;

    /**
     * Get active categories
     */
    public function getActive(): Collection;

    /**
     * Get inactive categories
     */
    public function getInactive(): Collection;

    /**
     * Get categories with subcategories
     */
    public function getWithSubcategories(): Collection;

    /**
     * Get parent categories only
     */
    public function getParentCategories(): Collection;

    /**
     * Get subcategories by parent
     */
    public function getSubcategories(int $parentId): Collection;

    /**
     * Get categories by name
     */
    public function getByName(string $name): Collection;

    /**
     * Search categories
     */
    public function search(string $query): Collection;

    /**
     * Get category statistics
     */
    public function getStatistics(): array;

    /**
     * Get categories with product count
     */
    public function getWithProductCount(): Collection;

    /**
     * Get categories by slug
     */
    public function getBySlug(string $slug): ?object;

    /**
     * Get categories for admin with filtering
     */
    public function getForAdmin(array $filters = []): LengthAwarePaginator;
}
