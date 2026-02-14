<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BlogRepositoryInterface extends RepositoryInterface
{
    /**
     * Get blogs for admin with filtering
     */
    public function getForAdmin(User $user, array $filters = []): LengthAwarePaginator;

    /**
     * Get blogs by author
     */
    public function getByAuthor(int $authorId): Collection;

    /**
     * Get published blogs
     */
    public function getPublished(): Collection;

    /**
     * Get draft blogs
     */
    public function getDrafts(): Collection;

    /**
     * Get blogs with reviews
     */
    public function getWithReviews(): Collection;

    /**
     * Search blogs by title or content
     */
    public function search(string $query): Collection;

    /**
     * Get blog statistics for admin
     */
    public function getAdminStatistics(User $user): array;

    /**
     * Get recent blogs
     */
    public function getRecent(int $limit = 10): Collection;

    /**
     * Get blogs by status
     */
    public function getByStatus(string $status): Collection;

    /**
     * Get blogs with author information
     */
    public function getWithAuthor(): Collection;

    /**
     * Get blogs by date range
     */
    public function getByDateRange(string $startDate, string $endDate): Collection;

    /**
     * Get blog count by status
     */
    public function getCountByStatus(string $status, User $user = null): int;

    /**
     * Get authors for admin
     */
    public function getAuthorsForAdmin(User $user): Collection;

    /**
     * Get blogs for public listing
     */
    public function getForPublic(array $filters = []): LengthAwarePaginator;

    /**
     * Get top rated blogs
     */
    public function getTopRated(int $limit = 5): Collection;
}
