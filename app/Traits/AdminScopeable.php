<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;

/**
 * Unified admin scoping trait.
 *
 * Replaces duplicated ForAdmin scopes across Product, Order, Blog, Vendor, and User models.
 * Handles superadmin bypass, city-based filtering for admins, and deny-all for unauthorized roles.
 *
 * Usage: Add `use AdminScopeable;` to your model and implement `getAdminCityRelationPath()`.
 */
trait AdminScopeable
{
    /**
     * Define the relationship path from this model to user_addresses.city.
     *
     * Examples:
     *   Product: "vendor.user.addresses"
     *   Order:   "user.addresses"
     *   Blog:    "author.addresses"
     *   Vendor:  "user.addresses"
     *   User:    "addresses"
     */
    abstract protected function getAdminCityRelationPath(): string;

    /**
     * Scope to filter records based on admin's city.
     * Superadmins see everything. Regular admins see only records matching their city.
     * All other roles see nothing (deny-all).
     */
    #[Scope]
    protected function forAdmin(Builder $query, User $user): void
    {
        // Superadmin bypass — handles both naming conventions
        if (in_array($user->role, ['superadmin', 'super_admin'])) {
            return; // See all data
        }

        // Admin city-based filtering
        if ($user->role === 'admin') {
            if ($user->addresses && $user->addresses->city) {
                $city = $user->addresses->city;
                $path = $this->getAdminCityRelationPath();

                // Build nested whereHas dynamically from dot-notation path
                // e.g. "vendor.user.addresses" becomes whereHas('vendor', fn => whereHas('user', fn => whereHas('addresses', fn => where('city', $city))))
                $query->where(function ($q) use ($city, $path) {
                    $segments = explode('.', $path);
                    $this->buildNestedWhereHas($q, $segments, $city);
                });
            }
            return;
        }

        // Deny-all for unauthorized roles
        $query->whereRaw('1 = 0');
    }

    /**
     * Recursively build nested whereHas from relationship path segments.
     */
    private function buildNestedWhereHas(Builder $query, array $segments, string $city): void
    {
        if (count($segments) === 1) {
            // Last segment — this is the addresses table, apply the city filter
            $query->whereHas($segments[0], function ($q) use ($city) {
                $q->where('city', $city);
            });
            return;
        }

        // Nest into the next relationship
        $current = array_shift($segments);
        $query->whereHas($current, function ($q) use ($segments, $city) {
            $this->buildNestedWhereHas($q, $segments, $city);
        });
    }
}
