<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\User;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    protected function getModel(): Product
    {
        return new Product();
    }

    public function getForAdmin(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = $this->resetQuery()
            ->with(['vendor', 'category', 'variants'])
            ->getQuery()
            ->ForAdmin($user);

        // Apply filters
        if (isset($filters['search']) && $filters['search']) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (isset($filters['category_id']) && $filters['category_id']) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['vendor_id']) && $filters['vendor_id']) {
            $query->where('vendor_id', $filters['vendor_id']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['min_price']) && $filters['min_price']) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price']) && $filters['max_price']) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (isset($filters['sort_by'])) {
            switch ($filters['sort_by']) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'created_at_desc':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function getTopRated(int $limit = 5): Collection
    {
        return $this->resetQuery()
            ->withAvg('productReviews', 'rating')
            ->with(['image'])
            ->orderByDesc('product_reviews_avg_rating')
            ->take($limit)
            ->get();
    }

    public function getWithAverageRating(): Collection
    {
        return $this->resetQuery()
            ->withAvg('productReviews', 'rating')
            ->orderByDesc('product_reviews_avg_rating')
            ->get();
    }

    public function getByCategory(int $categoryId): Collection
    {
        return $this->resetQuery()
            ->where('category_id', $categoryId)
            ->with(['vendor', 'category', 'image'])
            ->get();
    }

    public function getByVendor(int $vendorId): Collection
    {
        return $this->resetQuery()
            ->where('vendor_id', $vendorId)
            ->with(['category', 'variants', 'image'])
            ->get();
    }

    public function search(string $query): Collection
    {
        return $this->resetQuery()
            ->getQuery()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%')
                    ->orWhere('short_description', 'like', '%' . $query . '%');
            })
            ->with(['vendor', 'category', 'image'])
            ->get();
    }

    public function getInStock(): Collection
    {
        return $this->resetQuery()
            ->getQuery()
            ->whereHas('variants', function ($q) {
                $q->where('stock', '>', 0);
            })
            ->with(['variants', 'image'])
            ->get();
    }

    public function getWithImages(): Collection
    {
        return $this->resetQuery()
            ->with(['image', 'images'])
            ->get();
    }

    public function getAdminStatistics(User $user): array
    {
        // Base stats using conditional aggregation
        $stats = $this->model->newQuery()->ForAdmin($user)
            ->selectRaw('
                COUNT(*) as total_products,
                SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_products,
                SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive_products,
                AVG(price) as average_price,
                SUM(price) as total_value
            ')
            ->first();

        // Total variants requires joining/counting related table
        // We do a separate efficient query for this
        $totalVariants = $this->model->newQuery()->ForAdmin($user)
            ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->count('product_variants.id');

        return [
            'total_products' => $stats->total_products ?? 0,
            'active_products' => $stats->active_products ?? 0,
            'inactive_products' => $stats->inactive_products ?? 0,
            'total_variants' => $totalVariants,
            'average_price' => $stats->average_price ?? 0,
            'total_value' => $stats->total_value ?? 0,
        ];
    }

    public function getSpecialProducts(int $limit = 10): Collection
    {
        return $this->resetQuery()
            ->where('is_active', true)
            ->withAvg('productReviews', 'rating')
            ->orderByDesc('product_reviews_avg_rating')
            ->take($limit)
            ->get();
    }

    public function getForPublic(array $filters = []): LengthAwarePaginator
    {
        $this->resetQuery();
        $query = $this->getQuery(); // Access the builder directly

        $query->where('is_active', true)
            ->with(['vendor.user', 'category', 'image', 'productReviews']);

        // Apply filters
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['vendor_id'])) {
            $query->where('vendor_id', $filters['vendor_id']);
        }

        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // Sorting
        $query->orderBy('created_at', 'desc');

        // Use repository paginate to ensure reset
        return $this->paginate($filters['per_page'] ?? 10);
    }

    public function getWithVariants(): Collection
    {
        return $this->resetQuery()
            ->with(['variants', 'vendor', 'category'])
            ->get();
    }

    public function getByPriceRange(float $minPrice, float $maxPrice): Collection
    {
        return $this->resetQuery()
            ->where('price', '>=', $minPrice)
            ->where('price', '<=', $maxPrice)
            ->with(['vendor', 'category', 'image'])
            ->orderBy('price', 'asc')
            ->get();
    }

    public function getWithReviews(): Collection
    {
        return $this->resetQuery()
            ->with(['productReviews.user', 'productReviews'])
            ->get();
    }

    /**
     * Get products for public listing with pagination
     */
    public function getPublicProducts(array $filters = []): LengthAwarePaginator
    {
        $query = $this->resetQuery()
            ->with(['vendor', 'category', 'image'])
            ->getQuery()
            ->where('is_active', true);

        // Apply filters
        if (isset($filters['search']) && $filters['search']) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (isset($filters['category_id']) && $filters['category_id']) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['vendor_id']) && $filters['vendor_id']) {
            $query->where('vendor_id', $filters['vendor_id']);
        }

        if (isset($filters['min_price']) && $filters['min_price']) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price']) && $filters['max_price']) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // Default sorting
        $query->orderBy('created_at', 'desc');

        return $query->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Get products with stock information
     */
    public function getWithStockInfo(): Collection
    {
        return $this->resetQuery()
            ->with(['variants' => function ($query) {
                $query->select('id', 'product_id', 'stock', 'name');
            }])
            ->get()
            ->map(function ($product) {
                $product->total_stock = $product->variants->sum('stock');
                return $product;
            });
    }

    /**
     * Get products by date range
     */
    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        return $this->resetQuery()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['vendor', 'category', 'image'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get recent products
     */
    public function getRecent(int $limit = 10): Collection
    {
        return $this->resetQuery()
            ->with(['vendor', 'image'])
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }
}
