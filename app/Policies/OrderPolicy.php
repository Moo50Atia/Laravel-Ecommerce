<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Order $order): bool
    {
        // Admin can see any order
        if ($user->role === 'admin') {
            return true;
        }

        // Vendor can see orders containing their products
        if ($user->role === 'vendor') {
            return $order->items()->where('vendor_id', $user->vendor->id)->exists();
        }

        // User can only see their own orders
        return $user->id === $order->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Order $order): bool
    {
        // Admin can update any order
        if ($user->role === 'admin') {
            return true;
        }

        // Vendor can update order status if it's their product (simplified)
        if ($user->role === 'vendor') {
            return $order->items()->where('vendor_id', $user->vendor->id)->exists();
        }

        // Users cannot update orders after placement (usually)
        return false;
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->role === 'admin';
    }
}
