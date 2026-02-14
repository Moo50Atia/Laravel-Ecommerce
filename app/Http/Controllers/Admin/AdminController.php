<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\VendorRepositoryInterface;

class AdminController extends Controller
{
    protected $productRepository;
    protected $orderRepository;
    protected $userRepository;
    protected $vendorRepository;
    protected $dashboardService;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        OrderRepositoryInterface $orderRepository,
        UserRepositoryInterface $userRepository,
        VendorRepositoryInterface $vendorRepository,
        \App\Services\DashboardService $dashboardService
    ) {
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
        $this->userRepository = $userRepository;
        $this->vendorRepository = $vendorRepository;
        $this->dashboardService = $dashboardService;
    }

    public function dashboard()
    {
        $user = Auth::user();

        // Use DashboardService for cached, optimized data
        $data = $this->dashboardService->getAdminDashboard($user);

        // Extract stats from service logic
        $stats = $data['stats'];
        $productStats = $stats['products'];
        $orderStats = $stats['orders'];
        $userStats = $stats['users'];
        $vendorStats = $stats['vendors'];

        $charts = $data['charts'];
        $recent = $data['recent'];

        // Map service data to view variables

        // Charts data (Service returns array of objects, view expects collections/arrays)
        $monthlyData = collect($charts['monthly_orders']); // Contains both orders and revenue

        $monthlyRevenue = $monthlyData->map(function ($item) {
            return [
                'month' => $item->label,
                'revenue' => $item->revenue
            ];
        });

        $monthlyOrders = $monthlyData->map(function ($item) {
            return [
                'month' => $item->label,
                'orders' => $item->order_count
            ];
        });

        // Orders by status
        $ordersByStatusRaw = collect($charts['orders_by_status'])->pluck('count', 'status')->toArray();
        $ordersByStatus = [
            'pending' => $ordersByStatusRaw['pending'] ?? 0,
            'processing' => $ordersByStatusRaw['processing'] ?? 0,
            'completed' => $ordersByStatusRaw['completed'] ?? 0, // Service query might fallback 'completed' to 'delivered'?
            'cancelled' => $ordersByStatusRaw['cancelled'] ?? 0,
        ];
        // Note: OrderRepository maps 'delivered' as valid completed status usually. 
        // We should ensure service query matches these keys.

        // Recent items
        $recentOrders = $recent['recent_orders'];
        $recentProducts = $recent['recent_products'];

        // Top products - Service doesn't provide this yet, so we use Repo (it's fast enough or can be added to service)
        // For now, keep using repository for top products as it's not heavy
        $topProducts = $this->productRepository->getTopRated(5);

        // Other scalar counts
        $totalCategories = Category::count();
        $totalOrders = $orderStats['total'];
        $totalProducts = $productStats['total'];
        $totalUsers = $userStats['total'];
        $totalRevenue = $orderStats['total_revenue'];

        // Revenue Growth - calculation requires previous month which might be in chart data
        // For simplicity/accuracy, we can keep the specific date range query or calculate from chart data if available
        // Let's rely on repository for accurate growth calc as it's specific business logic
        // Or better, calculate from the monthly data if sufficient
        $currentMonthRevenue = $monthlyData->last()->revenue ?? 0;
        $previousMonthRevenue = $monthlyData->slice(-2, 1)->first()->revenue ?? 0;

        $revenueGrowth = 0;
        if ($previousMonthRevenue > 0) {
            $revenueGrowth = round((($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100, 1);
        }

        // new items counts - can use repo or add to service. 
        // For now, use Repositories but they are efficient simple queries.
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth()->toDateString();
        $endOfMonth = $now->copy()->toDateString();

        $newOrders = $this->orderRepository->getByDateRange($startOfMonth, $endOfMonth)->count();
        // Product/User repo don't have getByDateRange optimized count, but getByDateRange->count() is okay.
        $newProducts = $this->productRepository->getByDateRange($startOfMonth, $endOfMonth)->count();
        $newUsers = $this->userRepository->getByRegistrationDateRange($startOfMonth, $endOfMonth)->count();

        return view('admin.dashboard', compact(
            'orderStats',
            'productStats',
            'userStats',
            'vendorStats',
            'totalCategories',
            'revenueGrowth',
            'newOrders',
            'newProducts',
            'newUsers',
            'recentOrders',
            'recentProducts',
            'ordersByStatus',
            'topProducts',
            'monthlyRevenue',
            'monthlyOrders',
            'totalOrders',
            'totalProducts',
            'totalUsers',
            'totalRevenue'
        ));
    }
}
