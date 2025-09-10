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

class AdminController extends Controller
{
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
        // Get current month and previous month for comparison
        $currentMonth = Carbon::now()->startOfMonth();
        $previousMonth = Carbon::now()->subMonth()->startOfMonth();
        $user = Auth::user();
        
        // Apply city-based filtering for admin users
        $productQuery = Product::query();
        $vendorQuery = Vendor::query();
        $orderQuery = Order::query();
        
        if ($user->role == 'admin') {
            // Filter products by vendor's city
            $productQuery = $productQuery->ForAdmin($user);
            
            // Filter vendors by user's city
            $vendorQuery = $vendorQuery->ForAdmin($user);
            
        // Filter orders by user's city
            $orderQuery = $orderQuery->ForAdmin($user);
            
        }
        
        // Total counts with city filtering applied
        $totalOrders = $orderQuery->count();
        $totalProducts = $productQuery->count();
        $totalUsers = User::ForAdmin($user)->count(); // Not filtering users by city
        $totalVendors = $vendorQuery->count();
        $totalCategories = Category::count(); // Not filtering categories by city
        
        // Revenue calculations with city filtering applied
        $totalRevenue = clone $orderQuery;
        $totalRevenue = $totalRevenue->where('status', '!=', 'cancelled')
            ->sum('grand_total');
        
        $currentMonthRevenue = clone $orderQuery;
        $currentMonthRevenue = $currentMonthRevenue->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$currentMonth, Carbon::now()])
            ->sum('grand_total');
        
        $previousMonthRevenue = clone $orderQuery;
        $previousMonthRevenue = $previousMonthRevenue->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$previousMonth, $currentMonth])
            ->sum('grand_total');
        
        // Calculate revenue growth percentage
        $revenueGrowth = 0;
        if ($previousMonthRevenue > 0) {
            $revenueGrowth = round((($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100, 1);
        }
        
        // New items this month with city filtering applied
        $newOrders = clone $orderQuery;
        $newOrders = $newOrders->whereBetween('created_at', [$currentMonth, Carbon::now()])->count();
        
        $newProducts = clone $productQuery;
        $newProducts = $newProducts->whereBetween('created_at', [$currentMonth, Carbon::now()])->count();
        
        $newUsers = User::forAdmin($user)->whereBetween('created_at', [$currentMonth, Carbon::now()])->count();
        
        // Recent orders (last 5) with city filtering applied
        $recentOrders = clone $orderQuery;
        $recentOrders = $recentOrders->with(['user', 'items'])
            ->latest()
            ->take(5)
            ->get();
        
        // Recent products (last 5) with city filtering applied
        $recentProducts = clone $productQuery;
        $recentProducts = $recentProducts->with(['vendor', 'image'])
            ->latest()
            ->take(5)
            ->get();
        
        // Orders by status with city filtering applied
        $ordersByStatus = [
            'pending' => (clone $orderQuery)->where('status', 'pending')->count(),
            'processing' => (clone $orderQuery)->where('status', 'processing')->count(),
            'completed' => (clone $orderQuery)->where('status', 'completed')->count(),
            'cancelled' => (clone $orderQuery)->where('status', 'cancelled')->count(),
        ];
        
        // Top selling products (by order count) with city filtering applied
        $topProducts = clone $productQuery;
        $topProducts = $topProducts->withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();
        
        // Monthly revenue data for charts (last 6 months) with city filtering applied
        $monthlyRevenue = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenue = (clone $orderQuery)->where('status', '!=', 'cancelled')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('grand_total');
            
            $monthlyRevenue->push([
                'month' => $month->format('M Y'),
                'revenue' => $revenue
            ]);
        }
        
        // Orders by month for charts (last 6 months) with city filtering applied
        $monthlyOrders = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $orders = (clone $orderQuery)->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            
            $monthlyOrders->push([
                'month' => $month->format('M Y'),
                'orders' => $orders
            ]);
        }
        
        return view('admin.dashboard', compact(
            'totalOrders',
            'totalProducts', 
            'totalUsers',
            'totalVendors',
            'totalCategories',
            'totalRevenue',
            'revenueGrowth',
            'newOrders',
            'newProducts',
            'newUsers',
            'recentOrders',
            'recentProducts',
            'ordersByStatus',
            'topProducts',
            'monthlyRevenue',
            'monthlyOrders'
        ));
    }
}
