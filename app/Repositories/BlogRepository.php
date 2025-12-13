<?php

namespace App\Repositories;

use App\Models\Blog;
use App\Models\User;
use App\Repositories\Contracts\BlogRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BlogRepository extends BaseRepository implements BlogRepositoryInterface
{
    protected function getModel(): Blog
    {
        return new Blog();
    }

    public function getForAdmin(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = $this->resetQuery()
            ->with(['author', 'reviews'])
            ->getQuery()
            ->ForAdmin($user);

        // Apply filters
        if (isset($filters['search']) && $filters['search']) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        if (isset($filters['status']) && $filters['status']) {
            if ($filters['status'] === 'published') {
                $query->where('is_published', true);
            } elseif ($filters['status'] === 'draft') {
                $query->where('is_published', false);
            }
        }

        if (isset($filters['author']) && $filters['author']) {
            $query->whereHas('author', function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['author'] . '%');
            });
        }

        if (isset($filters['date_from']) && $filters['date_from']) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to']) && $filters['date_to']) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Default sorting
        $query->orderBy('created_at', 'desc');

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function getByAuthor(int $authorId): Collection
    {
        return $this->resetQuery()
            ->where('author_id', $authorId)
            ->with(['author', 'reviews'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getPublished(): Collection
    {
        return $this->resetQuery()
            ->where('is_published', true)
            ->with(['author', 'reviews'])
            ->orderBy('published_at', 'desc')
            ->get();
    }

    public function getDrafts(): Collection
    {
        return $this->resetQuery()
            ->where('is_published', false)
            ->with(['author'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getWithReviews(): Collection
    {
        return $this->resetQuery()
            ->with(['reviews.user', 'author'])
            ->get();
    }

    public function search(string $query): Collection
    {
        return $this->resetQuery()
            ->where('title', 'like', '%' . $query . '%')
            ->orWhere('content', 'like', '%' . $query . '%')
            ->orWhere('short_description', 'like', '%' . $query . '%')
            ->with(['author', 'reviews'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getAdminStatistics(User $user): array
    {
        $allBlogs = $this->resetQuery()
            ->getQuery()
            ->ForAdmin($user)
            ->get();

        return [
            'total_blogs' => $allBlogs->count(),
            'published_blogs' => $allBlogs->where('is_published', true)->count(),
            'draft_blogs' => $allBlogs->where('is_published', false)->count(),
            'total_reviews' => $allBlogs->sum(function($blog) {
                return $blog->reviews->count();
            }),
            'average_reviews' => $allBlogs->avg(function($blog) {
                return $blog->reviews->count();
            }),
        ];
    }

    public function getRecent(int $limit = 10): Collection
    {
        return $this->resetQuery()
            ->with(['author', 'reviews'])
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    public function getByStatus(string $status): Collection
    {
        $isPublished = $status === 'published';
        
        return $this->resetQuery()
            ->where('is_published', $isPublished)
            ->with(['author', 'reviews'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getWithAuthor(): Collection
    {
        return $this->resetQuery()
            ->with(['author'])
            ->get();
    }

    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        return $this->resetQuery()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['author', 'reviews'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getCountByStatus(string $status, User $user = null): int
    {
        $query = $this->resetQuery()->getQuery();
        
        if ($user) {
            $query->ForAdmin($user);
        }

        $isPublished = $status === 'published';
        return $query->where('is_published', $isPublished)->count();
    }

    public function getAuthorsForAdmin(User $user): Collection
    {
        return $this->resetQuery()
            ->getQuery()
            ->ForAdmin($user)
            ->with(['author'])
            ->whereHas('author')
            ->get()
            ->pluck('author')
            ->unique('id')
            ->values();
    }
}
