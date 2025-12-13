<?php

namespace App\Repositories;

use App\Models\Coupon;
use App\Repositories\Contracts\CouponRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CouponRepository extends BaseRepository implements CouponRepositoryInterface
{
    protected function getModel(): Coupon
    {
        return new Coupon();
    }

    public function getWithProducts(): Collection
    {
        return $this->resetQuery()
            ->with(['products'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getActive(): Collection
    {
        return $this->resetQuery()
            ->where('status', 'active')
            ->where('expires_at', null)
            ->orWhere('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getExpired(): Collection
    {
        return $this->resetQuery()
            ->where('expires_at', '<', now())
            ->orderBy('expires_at', 'desc')
            ->get();
    }

    public function getByType(string $type): Collection
    {
        return $this->resetQuery()
            ->where('type', $type)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getByStatus(string $status): Collection
    {
        return $this->resetQuery()
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getByCode(string $code): ?object
    {
        return $this->resetQuery()
            ->where('code', $code)
            ->first();
    }

    public function search(string $query): Collection
    {
        return $this->resetQuery()
            ->where('code', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getStatistics(): array
    {
        $allCoupons = $this->resetQuery()->get();
        
        return [
            'total_coupons' => $allCoupons->count(),
            'active_coupons' => $allCoupons->where('status', 'active')->count(),
            'inactive_coupons' => $allCoupons->where('status', 'inactive')->count(),
            'expired_coupons' => $allCoupons->filter(function($coupon) {
                return $coupon->expires_at && $coupon->expires_at < now();
            })->count(),
            'percentage_coupons' => $allCoupons->where('type', 'percentage')->count(),
            'fixed_coupons' => $allCoupons->where('type', 'fixed')->count(),
            'free_shipping_coupons' => $allCoupons->where('type', 'free_shipping')->count(),
        ];
    }

    public function getForAdmin(array $filters = []): LengthAwarePaginator
    {
        $query = $this->resetQuery()
            ->with(['products']);

        // Apply filters
        if (isset($filters['search']) && $filters['search']) {
            $query->where('code', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
        }

        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['type']) && $filters['type']) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['expired'])) {
            if ($filters['expired'] === 'yes') {
                $query->where('expires_at', '<', now());
            } elseif ($filters['expired'] === 'no') {
                $query->where('expires_at', null)
                      ->orWhere('expires_at', '>', now());
            }
        }

        if (isset($filters['min_discount']) && $filters['min_discount']) {
            $query->where('discount', '>=', $filters['min_discount']);
        }

        if (isset($filters['max_discount']) && $filters['max_discount']) {
            $query->where('discount', '<=', $filters['max_discount']);
        }

        // Default sorting
        $query->orderBy('created_at', 'desc');

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function getValidForDate(string $date): Collection
    {
        return $this->resetQuery()
            ->where('status', 'active')
            ->where('starts_at', null)
            ->orWhere('starts_at', '<=', $date)
            ->where('expires_at', null)
            ->orWhere('expires_at', '>=', $date)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getByMinimumAmount(float $amount): Collection
    {
        return $this->resetQuery()
            ->where('minimum_amount', '<=', $amount)
            ->where('status', 'active')
            ->orderBy('minimum_amount', 'desc')
            ->get();
    }

    public function getByUsageLimit(int $limit): Collection
    {
        return $this->resetQuery()
            ->where('usage_limit', '<=', $limit)
            ->orderBy('usage_limit', 'asc')
            ->get();
    }
}
