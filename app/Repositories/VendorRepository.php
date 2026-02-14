<?php

namespace App\Repositories;

use App\Models\Vendor;
use App\Repositories\Contracts\VendorRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class VendorRepository extends BaseRepository implements VendorRepositoryInterface
{
    protected function getModel(): Vendor
    {
        return new Vendor();
    }

    public function getWithUser(): Collection
    {
        return $this->resetQuery()
            ->with(['user'])
            ->get();
    }

    public function getVendorList(): Collection
    {
        return $this->model->newQuery()
            ->join('users', 'vendors.user_id', '=', 'users.id')
            ->select('vendors.id', 'vendors.store_name', 'users.name as user_name')
            ->get();
    }

    public function getWithProducts(): Collection
    {
        return $this->resetQuery()
            ->with(['products.category', 'products.image'])
            ->get();
    }

    public function getWithOrders(): Collection
    {
        return $this->resetQuery()
            ->with(['orders.user', 'orders.items.product'])
            ->get();
    }

    public function getByRatingRange(float $minRating, float $maxRating): Collection
    {
        return $this->resetQuery()
            ->whereBetween('rating', [$minRating, $maxRating])
            ->with(['user', 'image'])
            ->orderBy('rating', 'desc')
            ->get();
    }

    public function getTopRated(int $limit = 10): Collection
    {
        return $this->resetQuery()
            ->with(['user', 'image'])
            ->orderBy('rating', 'desc')
            ->take($limit)
            ->get();
    }

    public function getByCommissionRate(float $minRate, float $maxRate): Collection
    {
        return $this->resetQuery()
            ->whereBetween('commission_rate', [$minRate, $maxRate])
            ->with(['user', 'image'])
            ->orderBy('commission_rate', 'desc')
            ->get();
    }

    public function getWithImages(): Collection
    {
        return $this->resetQuery()
            ->with(['image'])
            ->get();
    }

    public function search(string $query): Collection
    {
        return $this->resetQuery()
            ->getQuery()
            ->where(function ($q) use ($query) {
                $q->where('store_name', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%')
                    ->orWhere('slug', 'like', '%' . $query . '%');
            })
            ->with(['user', 'image'])
            ->get();
    }

    public function getStatistics(): array
    {
        // Combined scalar stats
        $stats = $this->model->newQuery()
            ->selectRaw('
                COUNT(*) as total_vendors,
                AVG(rating) as average_rating,
                AVG(commission_rate) as average_commission
            ')
            ->first();

        $totalVendors = $stats->total_vendors ?? 0;

        // Relation counts still need separate queries or subqueries
        // We'll keep them separate for clarity as they involve different tables
        $vendorsWithProducts = $this->resetQuery()->whereHas('products')->count();
        $vendorsWithOrders = $this->resetQuery()->whereHas('orders')->count();

        return [
            'total_vendors' => $totalVendors,
            'vendors_with_products' => $vendorsWithProducts,
            'vendors_with_orders' => $vendorsWithOrders,
            'average_rating' => round($stats->average_rating ?? 0, 2),
            'average_commission_rate' => round($stats->average_commission ?? 0, 2),
            'active_vendors_percentage' => $totalVendors > 0 ? round(($vendorsWithProducts / $totalVendors) * 100, 2) : 0,
        ];
    }

    public function getForAdmin(\App\Models\User $user, array $filters = []): LengthAwarePaginator
    {
        $this->resetQuery();

        // Apply AdminScopeable scope
        $this->query->forAdmin($user);

        $query = $this->with(['user', 'image'])
            ->withCount(['products', 'orders'])
            ->getQuery();

        // Apply filters
        if (isset($filters['search']) && $filters['search']) {
            $query->where(function ($q) use ($filters) {
                $q->where('store_name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('description', 'like', '%' . $filters['search'] . '%')
                    ->orWhereHas('user', function ($userQuery) use ($filters) {
                        $userQuery->where('name', 'like', '%' . $filters['search'] . '%')
                            ->orWhere('email', 'like', '%' . $filters['search'] . '%');
                    });
            });
        }

        if (isset($filters['min_rating']) && $filters['min_rating']) {
            $query->where('rating', '>=', $filters['min_rating']);
        }

        if (isset($filters['max_rating']) && $filters['max_rating']) {
            $query->where('rating', '<=', $filters['max_rating']);
        }

        if (isset($filters['min_commission']) && $filters['min_commission']) {
            $query->where('commission_rate', '>=', $filters['min_commission']);
        }

        if (isset($filters['max_commission']) && $filters['max_commission']) {
            $query->where('commission_rate', '<=', $filters['max_commission']);
        }

        if (isset($filters['has_products']) && $filters['has_products'] !== '') {
            if ($filters['has_products']) {
                $query->whereHas('products');
            } else {
                $query->whereDoesntHave('products');
            }
        }

        if (isset($filters['has_orders']) && $filters['has_orders'] !== '') {
            if ($filters['has_orders']) {
                $query->whereHas('orders');
            } else {
                $query->whereDoesntHave('orders');
            }
        }

        if (isset($filters['sort_by'])) {
            switch ($filters['sort_by']) {
                case 'store_name_asc':
                    $query->orderBy('store_name', 'asc');
                    break;
                case 'store_name_desc':
                    $query->orderBy('store_name', 'desc');
                    break;
                case 'rating_asc':
                    $query->orderBy('rating', 'asc');
                    break;
                case 'rating_desc':
                    $query->orderBy('rating', 'desc');
                    break;
                case 'commission_asc':
                    $query->orderBy('commission_rate', 'asc');
                    break;
                case 'commission_desc':
                    $query->orderBy('commission_rate', 'desc');
                    break;
                case 'created_at_asc':
                    $query->orderBy('created_at', 'asc');
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

    public function getWithProductCount(): Collection
    {
        return $this->resetQuery()
            ->withCount(['products'])
            ->with(['user', 'image'])
            ->get();
    }

    public function getWithOrderCount(): Collection
    {
        return $this->resetQuery()
            ->withCount(['orders'])
            ->with(['user', 'image'])
            ->get();
    }

    public function getWithSalesStats(): Collection
    {
        return $this->resetQuery()
            ->with(['user', 'image'])
            ->withCount(['orders'])
            ->withSum('orders', 'grand_total')
            ->get();
    }

    public function getRecent(int $limit = 10): Collection
    {
        return $this->resetQuery()
            ->with(['user', 'image'])
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    public function getBySlug(string $slug): Collection
    {
        return $this->resetQuery()
            ->where('slug', $slug)
            ->with(['user', 'image', 'products'])
            ->get();
    }

    public function getHighCommissionVendors(): Collection
    {
        $averageCommission = $this->resetQuery()->avg('commission_rate');

        return $this->resetQuery()
            ->where('commission_rate', '>', $averageCommission)
            ->with(['user', 'image'])
            ->orderBy('commission_rate', 'desc')
            ->get();
    }

    public function getLowCommissionVendors(): Collection
    {
        $averageCommission = $this->resetQuery()->avg('commission_rate');

        return $this->resetQuery()
            ->where('commission_rate', '<', $averageCommission)
            ->with(['user', 'image'])
            ->orderBy('commission_rate', 'asc')
            ->get();
    }

    public function getCountByRatingRange(float $minRating, float $maxRating): int
    {
        return $this->resetQuery()
            ->whereBetween('rating', [$minRating, $maxRating])
            ->count();
    }

    public function getWithTotalSales(): Collection
    {
        return $this->resetQuery()
            ->with(['user', 'image'])
            ->withSum('orders', 'grand_total')
            ->orderBy('orders_sum_grand_total', 'desc')
            ->get();
    }

    /**
     * Get vendors with their monthly sales
     */
    public function getWithMonthlySales(): Collection
    {
        return $this->resetQuery()
            ->with(['user', 'image'])
            ->withCount(['orders' => function ($query) {
                $query->where('created_at', '>=', Carbon::now()->subMonth());
            }])
            ->withSum(['orders' => function ($query) {
                $query->where('created_at', '>=', Carbon::now()->subMonth());
            }], 'grand_total')
            ->get();
    }

    /**
     * Get vendors by city
     */
    public function getByCity(string $city): Collection
    {
        return $this->resetQuery()
            ->whereHas('user.addresses', function ($query) use ($city) {
                $query->where('city', $city);
            })
            ->with(['user', 'image'])
            ->get();
    }

    /**
     * Get vendors with their best selling products
     */
    public function getWithBestSellingProducts(): Collection
    {
        return $this->resetQuery()
            ->with(['products' => function ($query) {
                $query->withCount('orderItems')
                    ->orderBy('order_items_count', 'desc')
                    ->take(3);
            }])
            ->with(['user', 'image'])
            ->get();
    }

    /**
     * Get vendors with their average order value
     */
    public function getWithAverageOrderValue(): Collection
    {
        return $this->resetQuery()
            ->with(['user', 'image'])
            ->withAvg('orders', 'grand_total')
            ->orderBy('orders_avg_grand_total', 'desc')
            ->get();
    }
}
