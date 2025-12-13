<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    protected function getModel(): Category
    {
        return new Category();
    }

    public function getWithProductsCount(): Collection
    {
        return $this->resetQuery()
            ->withCount(['products'])
            ->orderBy('name')
            ->get();
    }

    public function getWithProducts(): Collection
    {
        return $this->resetQuery()
            ->with(['products'])
            ->orderBy('name')
            ->get();
    }

    public function getByStatus(string $status): Collection
    {
        return $this->resetQuery()
            ->where('status', $status)
            ->orderBy('name')
            ->get();
    }

    public function getActive(): Collection
    {
        return $this->getByStatus('active');
    }

    public function getInactive(): Collection
    {
        return $this->getByStatus('inactive');
    }

    public function getWithSubcategories(): Collection
    {
        return $this->resetQuery()
            ->with(['subcategories'])
            ->where('parent_id', null)
            ->orderBy('name')
            ->get();
    }

    public function getParentCategories(): Collection
    {
        return $this->resetQuery()
            ->where('parent_id', null)
            ->orderBy('name')
            ->get();
    }

    public function getSubcategories(int $parentId): Collection
    {
        return $this->resetQuery()
            ->where('parent_id', $parentId)
            ->orderBy('name')
            ->get();
    }

    public function getByName(string $name): Collection
    {
        return $this->resetQuery()
            ->where('name', 'like', '%' . $name . '%')
            ->orderBy('name')
            ->get();
    }

    public function search(string $query): Collection
    {
        return $this->resetQuery()
            ->where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->orderBy('name')
            ->get();
    }

    public function getStatistics(): array
    {
        $allCategories = $this->resetQuery()->get();
        
        return [
            'total_categories' => $allCategories->count(),
            'active_categories' => $allCategories->where('status', 'active')->count(),
            'inactive_categories' => $allCategories->where('status', 'inactive')->count(),
            'parent_categories' => $allCategories->whereNull('parent_id')->count(),
            'subcategories' => $allCategories->whereNotNull('parent_id')->count(),
            'categories_with_products' => $allCategories->filter(function($category) {
                return $category->products->count() > 0;
            })->count(),
        ];
    }

    public function getWithProductCount(): Collection
    {
        return $this->resetQuery()
            ->withCount(['products'])
            ->orderBy('products_count', 'desc')
            ->get();
    }

    public function getBySlug(string $slug): ?object
    {
        return $this->resetQuery()
            ->where('slug', $slug)
            ->first();
    }

    public function getForAdmin(array $filters = []): LengthAwarePaginator
    {
        $query = $this->resetQuery()
            ->withCount(['products'])
            ->with(['subcategories']);

        // Apply filters
        if (isset($filters['search']) && $filters['search']) {
            $query->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
        }

        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['parent_id'])) {
            if ($filters['parent_id'] === 'null') {
                $query->where('parent_id', null);
            } else {
                $query->where('parent_id', $filters['parent_id']);
            }
        }

        if (isset($filters['has_products'])) {
            if ($filters['has_products'] === 'yes') {
                $query->whereHas('products');
            } elseif ($filters['has_products'] === 'no') {
                $query->whereDoesntHave('products');
            }
        }

        // Default sorting
        $query->orderBy('created_at', 'desc');

        return $query->paginate($filters['per_page'] ?? 15);
    }
}
