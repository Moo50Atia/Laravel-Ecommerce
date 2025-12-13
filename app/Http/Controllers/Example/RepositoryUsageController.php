<?php

namespace App\Http\Controllers\Example;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\VendorRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepositoryUsageController extends Controller
{
    protected $productRepository;
    protected $orderRepository;
    protected $userRepository;
    protected $vendorRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        OrderRepositoryInterface $orderRepository,
        UserRepositoryInterface $userRepository,
        VendorRepositoryInterface $vendorRepository
    ) {
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
        $this->userRepository = $userRepository;
        $this->vendorRepository = $vendorRepository;
    }

    /**
     * Example: Get products for admin dashboard
     */
    public function getAdminProducts(Request $request)
    {
        $user = Auth::user();
        
        // Using repository instead of direct model access
        $products = $this->productRepository->getForAdmin($user, [
            'search' => $request->get('search'),
            'category_id' => $request->get('category_id'),
            'vendor_id' => $request->get('vendor_id'),
            'per_page' => 15
        ]);

        $statistics = $this->productRepository->getAdminStatistics($user);

        return response()->json([
            'products' => $products,
            'statistics' => $statistics
        ]);
    }

    /**
     * Example: Get top rated products for homepage
     */
    public function getTopRatedProducts()
    {
        $topProducts = $this->productRepository->getTopRated(5);
        $specialProducts = $this->productRepository->getSpecialProducts();

        return response()->json([
            'top_rated' => $topProducts,
            'special' => $specialProducts
        ]);
    }

    /**
     * Example: Get orders for admin with filtering
     */
    public function getAdminOrders(Request $request)
    {
        $user = Auth::user();
        
        $orders = $this->orderRepository->getForAdmin($user, [
            'search' => $request->get('search'),
            'status' => $request->get('status'),
            'payment_status' => $request->get('payment_status'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'per_page' => 15
        ]);

        $dashboardData = $this->orderRepository->getDashboardData($user);

        return response()->json([
            'orders' => $orders,
            'dashboard_data' => $dashboardData
        ]);
    }

    /**
     * Example: Get user statistics
     */
    public function getUserStatistics()
    {
        $statistics = $this->userRepository->getStatistics();
        $recentUsers = $this->userRepository->getRecent(10);
        $customers = $this->userRepository->getCustomers();
        $vendors = $this->userRepository->getVendors();

        return response()->json([
            'statistics' => $statistics,
            'recent_users' => $recentUsers,
            'customers' => $customers,
            'vendors' => $vendors
        ]);
    }

    /**
     * Example: Get vendor statistics and top vendors
     */
    public function getVendorData()
    {
        $statistics = $this->vendorRepository->getStatistics();
        $topVendors = $this->vendorRepository->getTopRated(10);
        $vendorsWithSales = $this->vendorRepository->getWithTotalSales();

        return response()->json([
            'statistics' => $statistics,
            'top_vendors' => $topVendors,
            'vendors_with_sales' => $vendorsWithSales
        ]);
    }

    /**
     * Example: Search functionality using repositories
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return response()->json(['error' => 'Search query is required'], 400);
        }

        $products = $this->productRepository->search($query);
        $vendors = $this->vendorRepository->search($query);
        $users = $this->userRepository->search($query);

        return response()->json([
            'products' => $products,
            'vendors' => $vendors,
            'users' => $users
        ]);
    }

    /**
     * Example: Get dashboard data using multiple repositories
     */
    public function getDashboardData()
    {
        $user = Auth::user();
        
        $productStats = $this->productRepository->getAdminStatistics($user);
        $orderStats = $this->orderRepository->getDashboardData($user);
        $userStats = $this->userRepository->getStatistics();
        $vendorStats = $this->vendorRepository->getStatistics();

        return response()->json([
            'products' => $productStats,
            'orders' => $orderStats,
            'users' => $userStats,
            'vendors' => $vendorStats
        ]);
    }
}
