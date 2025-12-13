<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SearchFilterService
{
    /**
     * Apply search filters to orders query
     */
    public function applyOrderFilters(Builder $query, Request $request): Builder
    {
        // Search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $status = $request->get('status');
            $query->where('status', $status);
        }

        // Payment method filter
        if ($request->filled('payment_method')) {
            $paymentMethod = $request->get('payment_method');
            $query->where('payment_method', $paymentMethod);
        }

        // Payment status filter
        if ($request->filled('payment_status')) {
            $paymentStatus = $request->get('payment_status');
            $query->where('payment_status', $paymentStatus);
        }

        return $query;
    }

    /**
     * Apply search filters to products query
     */
    public function applyProductFilters(Builder $query, Request $request): Builder
    {
        // Search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('vendor', function($vendorQuery) use ($search) {
                      $vendorQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $category = $request->get('category');
            $query->whereHas('category', function($q) use ($category) {
                $q->where('name', 'like', "%{$category}%");
            });
        }

        // Vendor filter
        if ($request->filled('vendor')) {
            $vendor = $request->get('vendor');
            $query->whereHas('vendor', function($q) use ($vendor) {
                $q->where('name', 'like', "%{$vendor}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        return $query;
    }

    /**
     * Apply search filters to blogs query
     */
    public function applyBlogFilters(Builder $query, Request $request): Builder
    {
        // Search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('title', 'like', "%{$search}%");
        }

        // Status filter
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'published') {
                $query->where('is_published', true);
            } elseif ($status === 'draft') {
                $query->where('is_published', false);
            }
        }

        // Author filter
        if ($request->filled('author')) {
            $author = $request->get('author');
            $query->whereHas('author', function($q) use ($author) {
                $q->where('name', 'like', "%{$author}%");
            });
        }

        return $query;
    }

    /**
     * Apply search filters to users query
     */
    public function applyUserFilters(Builder $query, Request $request): Builder
    {
        // Search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $role = $request->get('role');
            $query->where('role', $role);
        }

        // Status filter
        if ($request->filled('status')) {
            $status = $request->get('status');
            $query->where('status', $status);
        }

        return $query;
    }

    /**
     * Apply search filters to vendors query
     */
    public function applyVendorFilters(Builder $query, Request $request): Builder
    {
        $status = $request->get('status');
        
        if ($status && in_array($status, ['active', 'inactive', 'suspended'])) {
            $query->whereHas('user', function($q) use ($status) {
                $q->where('status', $status);
            });
        }

        return $query;
    }

    /**
     * Get paginated results with query string preservation
     */
    public function getPaginatedResults(Builder $query, int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $query->latest()->paginate($perPage)->withQueryString();
    }

    /**
     * Get filter dropdown values
     */
    public function getFilterDropdowns(Builder $query, array $fields): array
    {
        $dropdowns = [];
        
        foreach ($fields as $field) {
            $dropdowns[$field] = $query->distinct()->pluck($field)->filter()->values();
        }
        
        return $dropdowns;
    }

    /**
     * Apply date range filters
     */
    public function applyDateRangeFilters(Builder $query, Request $request, string $dateField = 'created_at'): Builder
    {
        if ($request->filled('date_from')) {
            $query->where($dateField, '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->where($dateField, '<=', $request->get('date_to'));
        }

        return $query;
    }

    /**
     * Apply price range filters
     */
    public function applyPriceRangeFilters(Builder $query, Request $request, string $priceField = 'price'): Builder
    {
        if ($request->filled('min_price')) {
            $query->where($priceField, '>=', $request->get('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where($priceField, '<=', $request->get('max_price'));
        }

        return $query;
    }
}
