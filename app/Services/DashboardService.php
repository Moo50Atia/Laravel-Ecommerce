<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;

/**
 * DashboardService — replaces the 30-query AdminController::dashboard() pattern.
 *
 * Strategy:
 *   Real-time  → recent items (no cache)
 *   5-min cache → stats, status counts, revenue
 *   1-hour cache → monthly charts
 */
class DashboardService
{
    /**
     * Get all admin dashboard data in one call.
     *
     * @param User $admin  The current admin user
     * @return array  ['stats' => [...], 'charts' => [...], 'recent' => [...]]
     */
    public function getAdminDashboard(User $admin): array
    {
        return [
            'stats'  => $this->getStats(),
            'charts' => $this->getChartData(),
            'recent' => $this->getRecentItems(),
        ];
    }

    /**
     * Cached aggregated statistics (5-minute TTL).
     */
    protected function getStats(): array
    {
        return Cache::remember('dashboard:stats', 300, function () {
            // Single query per table using SQL aggregates
            $productStats = DB::selectOne("
                SELECT
                    COUNT(*) AS total,
                    COALESCE(SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END), 0) AS active,
                    COALESCE(SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END), 0) AS inactive,
                    COALESCE(SUM(CASE WHEN is_featured = 1 THEN 1 ELSE 0 END), 0) AS featured,
                    COALESCE(AVG(price), 0) AS avg_price
                FROM products
            ");

            $orderStats = DB::selectOne("
                SELECT
                    COUNT(*) AS total,
                    COALESCE(SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END), 0) AS pending,
                    COALESCE(SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END), 0) AS processing,
                    COALESCE(SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END), 0) AS completed,
                    COALESCE(SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END), 0) AS cancelled,
                    COALESCE(SUM(CASE WHEN status != 'cancelled' THEN grand_total ELSE 0 END), 0) AS total_revenue,
                    COALESCE(AVG(CASE WHEN status != 'cancelled' THEN grand_total END), 0) AS avg_order_value
                FROM orders
            ");

            $userStats = DB::selectOne("
                SELECT
                    COUNT(*) AS total,
                    COALESCE(SUM(CASE WHEN role = 'user' THEN 1 ELSE 0 END), 0) AS customers,
                    COALESCE(SUM(CASE WHEN role = 'vendor' THEN 1 ELSE 0 END), 0) AS vendors_count,
                    COALESCE(SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END), 0) AS active
                FROM users
            ");

            $vendorStats = DB::selectOne("
                SELECT
                    COUNT(*) AS total,
                    COALESCE(AVG(rating), 0) AS avg_rating,
                    COALESCE(AVG(commission_rate), 0) AS avg_commission
                FROM vendors
            ");

            return [
                'products' => (array) $productStats,
                'orders'   => (array) $orderStats,
                'users'    => (array) $userStats,
                'vendors'  => (array) $vendorStats,
            ];
        });
    }

    /**
     * Cached chart data (1-hour TTL).
     */
    protected function getChartData(): array
    {
        return Cache::remember('dashboard:charts', 3600, function () {
            $monthlyOrders = DB::select("
                SELECT
                    DATE_FORMAT(created_at, '%Y-%m') AS month_key,
                    DATE_FORMAT(created_at, '%b %Y') AS label,
                    COUNT(*) AS order_count,
                    COALESCE(SUM(CASE WHEN status != 'cancelled' THEN grand_total ELSE 0 END), 0) AS revenue
                FROM orders
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY month_key, label
                ORDER BY month_key ASC
            ");

            $ordersByStatus = DB::select("
                SELECT status, COUNT(*) AS count
                FROM orders
                GROUP BY status
            ");

            return [
                'monthly_orders' => $monthlyOrders,
                'orders_by_status' => $ordersByStatus,
            ];
        });
    }

    /**
     * Recent items — no cache, but limited queries.
     */
    protected function getRecentItems(): array
    {
        return [
            'recent_orders'   => Order::with('user:id,name')
                ->select('id', 'order_number', 'status', 'grand_total', 'user_id', 'created_at')
                ->latest()
                ->limit(5)
                ->get(),
            'recent_products' => Product::with('vendor:id,store_name')
                ->select('id', 'name', 'price', 'is_active', 'vendor_id', 'created_at')
                ->latest()
                ->limit(5)
                ->get(),
        ];
    }

    /**
     * Invalidate all dashboard caches (called by observers).
     */
    public static function clearCache(): void
    {
        Cache::forget('dashboard:stats');
        Cache::forget('dashboard:charts');
    }
}
