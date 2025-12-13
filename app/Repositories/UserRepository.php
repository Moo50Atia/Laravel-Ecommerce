<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected function getModel(): User
    {
        return new User();
    }

    public function getByRole(string $role): Collection
    {
        return $this->resetQuery()
            ->where('role', $role)
            ->with(['addresses', 'vendor'])
            ->get();
    }

    public function getCustomers(): Collection
    {
        return $this->getByRole('customer');
    }

    public function getVendors(): Collection
    {
        return $this->getByRole('vendor');
    }

    public function getAdmins(): Collection
    {
        return $this->getByRole('admin');
    }

    public function getWithAddresses(): Collection
    {
        return $this->resetQuery()
            ->with(['addresses'])
            ->get();
    }

    public function getWithVendor(): Collection
    {
        return $this->resetQuery()
            ->with(['vendor'])
            ->get();
    }

    public function getWithOrders(): Collection
    {
        return $this->resetQuery()
            ->with(['orders'])
            ->get();
    }

    public function getByStatus(string $status): Collection
    {
        return $this->resetQuery()
            ->where('status', $status)
            ->with(['addresses', 'vendor'])
            ->get();
    }
    public function getByRoleAndStatus(string $role, string $status): Collection
    {
        return $this->resetQuery()
            ->where('role', $role)
            ->where('status', $status)
            ->with(['addresses', 'vendor'])
            ->get();
    }

    public function getByCity(string $city): Collection
    {
        return $this->resetQuery()
            ->getQuery()
            ->whereHas('addresses', function($query) use ($city) {
                $query->where('city', $city);
            })
            ->with(['addresses', 'vendor'])
            ->get();
    }

    public function search(string $query): Collection
    {
        return $this->resetQuery()
            ->getQuery()
            ->where(function($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('email', 'like', '%' . $query . '%')
                  ->orWhere('phone', 'like', '%' . $query . '%');
            })
            ->with(['addresses', 'vendor'])
            ->get();
    }

    public function getStatistics(): array
    {
        $totalUsers = $this->resetQuery()->count();
        $customers = $this->getCountByRole('customer');
        $vendors = $this->getCountByRole('vendor');
        $admins = $this->getCountByRole('admin');
        $activeUsers = $this->getCountByStatus('active');
        $inactiveUsers = $this->getCountByStatus('inactive');

        return [
            'total_users' => $totalUsers,
            'customers' => $customers,
            'vendors' => $vendors,
            'admins' => $admins,
            'active_users' => $activeUsers,
            'inactive_users' => $inactiveUsers,
            'customer_percentage' => $totalUsers > 0 ? round(($customers / $totalUsers) * 100, 2) : 0,
            'vendor_percentage' => $totalUsers > 0 ? round(($vendors / $totalUsers) * 100, 2) : 0,
        ];
    }

    public function getRecent(int $limit = 10): Collection
    {
        return $this->resetQuery()
            ->with(['addresses', 'vendor'])
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    public function getWithWishlist(): Collection
    {
        return $this->resetQuery()
            ->with(['wishlists.product'])
            ->get();
    }

    public function getWithSubscriptions(): Collection
    {
        return $this->resetQuery()
            ->with(['subscriptions'])
            ->get();
    }

    public function getActive(): Collection
    {
        return $this->getByStatus('active');
    }

    public function getInactive(): Collection
    {
        return $this->getByStatus('inactive');
    }

    public function getForAdmin(array $filters = []): LengthAwarePaginator
    {
        $query = $this->resetQuery()
            ->with(['addresses', 'vendor'])
            ->getQuery();

        // Apply filters
        if (isset($filters['search']) && $filters['search']) {
            $query->where(function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('phone', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (isset($filters['role']) && $filters['role']) {
            $query->where('role', $filters['role']);
        }

        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['city']) && $filters['city']) {
            $query->whereHas('addresses', function($q) use ($filters) {
                $q->where('city', $filters['city']);
            });
        }

        if (isset($filters['date_from']) && $filters['date_from']) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to']) && $filters['date_to']) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (isset($filters['sort_by'])) {
            switch ($filters['sort_by']) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'email_asc':
                    $query->orderBy('email', 'asc');
                    break;
                case 'email_desc':
                    $query->orderBy('email', 'desc');
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

    public function getCountByRole(string $role): int
    {
        return $this->resetQuery()
            ->where('role', $role)
            ->count();
    }

    public function getCountByStatus(string $status): int
    {
        return $this->resetQuery()
            ->where('status', $status)
            ->count();
    }

    /**
     * Get users with their order statistics
     */
    public function getWithOrderStats(): Collection
    {
        return $this->resetQuery()
            ->withCount(['orders'])
            ->with(['orders' => function($query) {
                $query->selectRaw('user_id, SUM(grand_total) as total_spent, COUNT(*) as order_count')
                      ->groupBy('user_id');
            }])
            ->get();
    }

    /**
     * Get users by registration date range
     */
    public function getByRegistrationDateRange(string $startDate, string $endDate): Collection
    {
        return $this->resetQuery()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['addresses', 'vendor'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get users with email verification status
     */
    public function getWithEmailVerificationStatus(): Collection
    {
        return $this->resetQuery()
            ->with(['addresses', 'vendor'])
            ->get()
            ->map(function($user) {
                $user->email_verified = !is_null($user->email_verified_at);
                return $user;
            });
    }

    /**
     * Get users by phone number pattern
     */
    public function getByPhonePattern(string $pattern): Collection
    {
        return $this->resetQuery()
            ->where('phone', 'like', '%' . $pattern . '%')
            ->with(['addresses', 'vendor'])
            ->get();
    }

    /**
     * Get users with their last login information
     */
    public function getWithLastLogin(): Collection
    {
        return $this->resetQuery()
            ->with(['addresses', 'vendor'])
            ->orderBy('updated_at', 'desc')
            ->get();
    }
}
