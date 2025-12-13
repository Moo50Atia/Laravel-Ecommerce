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
     * Filter query by admin's city
     * This method applies city-based filtering to any model that has a relationship
     * chain leading to a user with an address
     * 
     * @param mixed $query The query builder instance
     * @param string $relationPath The dot-notation path to the user's address (e.g., 'vendor.user')
     * @return mixed The filtered query
     */
    // protected function filterByCityScope($query, $relationPath = null)
    // {
    //     $user = Auth::user();
        
    //     // Super admin sees all data
    //     if ($user->role == 'super_admin') {
    //         return $query;
    //     }
        
    //     // Check if admin has an address with city
    //     if ($user->role == 'admin' && $user->addresses && $user->addresses->city) {
    //         $city = $user->addresses->city;
            
    //         // If a relation path is provided, use it to filter
    //         if ($relationPath) {
    //             $relations = explode('.', $relationPath);
    //             $lastRelation = array_pop($relations);
                
    //             // Build the nested whereHas query
    //             $query->whereHas($lastRelation, function($q) use ($relations, $city) {
    //                 $this->buildNestedWhereHas($q, $relations, $city);
    //             });
    //         }
    //     }
        
    //     return $query;
    // }
    
    /**
     * Build nested whereHas queries for filtering by city
     * 
     * @param mixed $query The query builder instance
     * @param array $relations The array of relations to traverse
     * @param string $city The city to filter by
     */
    protected function buildNestedWhereHas($query, $relations, $city)
    {
        if (empty($relations)) {
            // We've reached the end of the relation chain, apply the city filter
            $query->whereHas('addresses', function($q) use ($city) {
                $q->where('city', $city);
            });
            return;
        }
        
        $relation = array_pop($relations);
        $query->whereHas($relation, function($q) use ($relations, $city) {
            $this->buildNestedWhereHas($q, $relations, $city);
        });
    }
    
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get statistics using repositories
        $productStats = $this->productRepository->getAdminStatistics($user);
        $orderStats = $this->orderRepository->getAdminStatistics($user);
        $userStats = $this->userRepository->getStatistics();
        $vendorStats = $this->vendorRepository->getStatistics();
        
        // Get recent data using repositories
        $recentOrders = $this->orderRepository->getRecent(5);
        $recentProducts = $this->productRepository->getRecent(5);
        
        // Get top products using repository
        $topProducts = $this->productRepository->getTopRated(5);
        
        // Get orders by status using repository
        $ordersByStatus = [
            'pending' => $this->orderRepository->getCountByStatus('pending', $user),
            'processing' => $this->orderRepository->getCountByStatus('processing', $user),
            'completed' => $this->orderRepository->getCountByStatus('completed', $user),
            'cancelled' => $this->orderRepository->getCountByStatus('cancelled', $user),
        ];
        
        // Calculate revenue growth (keeping some direct calculations for complex logic)
        $currentMonth = Carbon::now()->startOfMonth();
        $previousMonth = Carbon::now()->subMonth()->startOfMonth();
        
        $currentMonthRevenue = $this->orderRepository->getByDateRange(
            $currentMonth->toDateString(), 
            Carbon::now()->toDateString()
        )->where('status', '!=', 'cancelled')->sum('grand_total');
        
        $previousMonthRevenue = $this->orderRepository->getByDateRange(
            $previousMonth->toDateString(), 
            $currentMonth->toDateString()
        )->where('status', '!=', 'cancelled')->sum('grand_total');
        
        $revenueGrowth = 0;
        if ($previousMonthRevenue > 0) {
            $revenueGrowth = round((($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100, 1);
        }
        
        // Get new items this month
        $newOrders = $this->orderRepository->getByDateRange(
            $currentMonth->toDateString(), 
            Carbon::now()->toDateString()
        )->count();
        
        $newProducts = $this->productRepository->getByDateRange(
            $currentMonth->toDateString(), 
            Carbon::now()->toDateString()
        )->count();
        
        $newUsers = $this->userRepository->getByRegistrationDateRange(
            $currentMonth->toDateString(), 
            Carbon::now()->toDateString()
        )->count();
        
        // Monthly revenue data for charts (last 6 months)
        $monthlyRevenue = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->startOfMonth()->toDateString();
            $monthEnd = $month->endOfMonth()->toDateString();
            
            $revenue = $this->orderRepository->getByDateRange($monthStart, $monthEnd)
                ->where('status', '!=', 'cancelled')
                ->sum('grand_total');
            
            $monthlyRevenue->push([
                'month' => $month->format('M Y'),
                'revenue' => $revenue
            ]);
        }
        
        // Orders by month for charts (last 6 months)
        $monthlyOrders = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->startOfMonth()->toDateString();
            $monthEnd = $month->endOfMonth()->toDateString();
            
            $orders = $this->orderRepository->getByDateRange($monthStart, $monthEnd)->count();
            
            $monthlyOrders->push([
                'month' => $month->format('M Y'),
                'orders' => $orders
            ]);
        }
        
        // Total categories (keeping direct access as no repository exists)
        $totalCategories = Category::count();
        
        // Extract individual statistics for blade template
        $totalOrders = $orderStats['total_orders'];
        $totalProducts = $productStats['total_products'];
        $totalUsers = $userStats['total_users'];
        
        // Calculate total revenue from non-cancelled orders using repository
        $totalRevenue = $this->orderRepository->getTotalRevenue($user);
        
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
