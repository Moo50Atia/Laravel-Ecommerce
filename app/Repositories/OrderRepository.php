<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\User;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    protected function getModel(): Order
    {
        return new Order();
    }

    public function getForAdmin(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = $this->resetQuery()
            ->with(['user', 'vendor', 'items.product'])
            ->getQuery()
            ->ForAdmin($user);

        // Apply filters
        if (isset($filters['search']) && $filters['search']) {
            $query->where(function($q) use ($filters) {
                $q->where('order_number', 'like', '%' . $filters['search'] . '%')
                  ->orWhereHas('user', function($userQuery) use ($filters) {
                      $userQuery->where('name', 'like', '%' . $filters['search'] . '%')
                               ->orWhere('email', 'like', '%' . $filters['search'] . '%');
                  });
            });
        }

        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['payment_status']) && $filters['payment_status']) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (isset($filters['payment_method']) && $filters['payment_method']) {
            $query->where('payment_method', $filters['payment_method']);
        }

        if (isset($filters['date_from']) && $filters['date_from']) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to']) && $filters['date_to']) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (isset($filters['min_amount']) && $filters['min_amount']) {
            $query->where('grand_total', '>=', $filters['min_amount']);
        }

        if (isset($filters['max_amount']) && $filters['max_amount']) {
            $query->where('grand_total', '<=', $filters['max_amount']);
        }

        // Default sorting
        $query->orderBy('created_at', 'desc');

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function getByUser(int $userId): Collection
    {
        return $this->resetQuery()
            ->where('user_id', $userId)
            ->with(['items.product', 'vendor'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getByVendor(int $vendorId): Collection
    {
        return $this->resetQuery()
            ->where('vendor_id', $vendorId)
            ->with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getByStatus(string $status): Collection
    {
        return $this->resetQuery()
            ->where('status', $status)
            ->with(['user', 'vendor', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getByPaymentStatus(string $paymentStatus): Collection
    {
        return $this->resetQuery()
            ->where('payment_status', $paymentStatus)
            ->with(['user', 'vendor', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        return $this->resetQuery()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'vendor', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getAdminStatistics(User $user): array
    {
        $allOrders = $this->resetQuery()
            ->getQuery()
            ->ForAdmin($user)
            ->get();

        return [
            'total_orders' => $allOrders->count(),
            'total_sales' => number_format($allOrders->sum('grand_total'), 2),
            'pending_orders' => $allOrders->where('status', 'pending')->count(),
            'completed_orders' => $allOrders->where('status', 'delivered')->count(),
            'cancelled_orders' => $allOrders->where('status', 'cancelled')->count(),
            'paid_orders' => $allOrders->where('payment_status', 'paid')->count(),
            'unpaid_orders' => $allOrders->where('payment_status', 'pending')->count(),
            'average_order_value' => $allOrders->avg('grand_total'),
            'total_revenue' => $allOrders->sum('grand_total'),
        ];
    }

    public function getVendorStatistics(int $vendorId): array
    {
        $vendorOrders = $this->resetQuery()
            ->where('vendor_id', $vendorId)
            ->get();

        return [
            'total_orders' => $vendorOrders->count(),
            'total_sales' => $vendorOrders->sum('grand_total'),
            'pending_orders' => $vendorOrders->where('status', 'pending')->count(),
            'completed_orders' => $vendorOrders->where('status', 'delivered')->count(),
            'average_order_value' => $vendorOrders->avg('grand_total'),
            'monthly_sales' => $vendorOrders->where('created_at', '>=', Carbon::now()->subMonth())->sum('grand_total'),
        ];
    }

    public function getRecent(int $limit = 10): Collection
    {
        return $this->resetQuery()
            ->with(['user', 'vendor', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    public function getWithItems(): Collection
    {
        return $this->resetQuery()
            ->with(['items.product', 'items.variant'])
            ->get();
    }

    public function getWithProducts(): Collection
    {
        return $this->resetQuery()
            ->with(['products'])
            ->get();
    }

    public function getByPaymentMethod(string $paymentMethod): Collection
    {
        return $this->resetQuery()
            ->where('payment_method', $paymentMethod)
            ->with(['user', 'vendor', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getTotalSales(User $user = null): float
    {
        $query = $this->resetQuery()->getQuery();
        
        if ($user) {
            $query->ForAdmin($user);
        }

        return $query->sum('grand_total');
    }

    public function getCountByStatus(string $status, User $user = null): int
    {
        $query = $this->resetQuery()->getQuery()->where('status', $status);
        
        if ($user) {
            $query->ForAdmin($user);
        }

        return $query->count();
    }

    public function getDashboardData(User $user = null): array
    {
        $query = $this->resetQuery()->getQuery();
        
        if ($user) {
            $query->ForAdmin($user);
        }

        $allOrders = $query->get();

        return [
            'total_orders' => $allOrders->count(),
            'total_sales' => number_format($allOrders->sum('grand_total'), 2),
            'pending_orders' => $allOrders->where('status', 'pending')->count(),
            'completed_orders' => $allOrders->where('status', 'delivered')->count(),
            'cancelled_orders' => $allOrders->where('status', 'cancelled')->count(),
            'paid_orders' => $allOrders->where('payment_status', 'paid')->count(),
            'unpaid_orders' => $allOrders->where('payment_status', 'pending')->count(),
            'today_orders' => $allOrders->where('created_at', '>=', Carbon::today())->count(),
            'this_month_orders' => $allOrders->where('created_at', '>=', Carbon::now()->startOfMonth())->count(),
            'this_month_sales' => $allOrders->where('created_at', '>=', Carbon::now()->startOfMonth())->sum('grand_total'),
        ];
    }

    public function updateStatus(int $orderId, string $status): bool
    {
        $order = $this->find($orderId);
        if (!$order) {
            return false;
        }

        return $order->update(['status' => $status]);
    }

    public function updatePaymentStatus(int $orderId, string $paymentStatus): bool
    {
        $order = $this->find($orderId);
        if (!$order) {
            return false;
        }

        return $order->update(['payment_status' => $paymentStatus]);
    }

    /**
     * Get orders with specific statuses for admin
     */
    public function getStatusesForAdmin(User $user): Collection
    {
        return $this->resetQuery()
            ->getQuery()
            ->ForAdmin($user)
            ->distinct()
            ->pluck('status')
            ->filter()
            ->values();
    }

    /**
     * Get payment methods for admin
     */
    public function getPaymentMethodsForAdmin(User $user): Collection
    {
        return $this->resetQuery()
            ->getQuery()
            ->ForAdmin($user)
            ->distinct()
            ->pluck('payment_method')
            ->filter()
            ->values();
    }

    /**
     * Get payment statuses for admin
     */
    public function getPaymentStatusesForAdmin(User $user): Collection
    {
        return $this->resetQuery()
            ->getQuery()
            ->ForAdmin($user)
            ->distinct()
            ->pluck('payment_status')
            ->filter()
            ->values();
    }
    public function getTotalRevenue(User $user = null): float
    {
        return $this->resetQuery()->getQuery()->where('status', '!=', 'cancelled')->sum('grand_total');
    }
}
