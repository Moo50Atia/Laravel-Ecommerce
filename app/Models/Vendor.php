<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
class Vendor extends Model
{
    /** @use HasFactory<\Database\Factories\VendorFactory> */
    use HasFactory;
    protected $fillable =[
        "store_name",
        "slug",
       "description",
        "commission_rate",
        "rating",
        'email',
        'phone',
        'user_id',
    ];
    public function user()
{
    return $this->belongsTo(User::class);
}

public function products()
{
    return $this->hasMany(Product::class);
}
public function image()
{
    return $this->morphOne(Image::class, "imageable");
}
//   public function orderItems()
//     {
//         return $this->hasMany(OrderItem::class);
//     }
//     public function getOrdersAttribute()
//     {
//         return $this->orderItems()->distinct('order_id')->get()->map(function ($orderItem) {
//             return $orderItem->order;
//         });
//     }
//     public function orders()
// {
//     return $this->hasManyThrough(Order::class, OrderItem::class)
//         ->distinct();
// }
public function orders()
{
    return $this->belongsToMany(Order::class, "order_items", "vendor_id", "order_id")
                ->withPivot(["product_id", "quantity", "price", "variant_id"])
                ->withTimestamps()
                ->distinct();
}

#[Scope]
protected function ForAdmin(Builder $query, User $user): void
{
    if ($user->role == "superadmin") {
        return;               // Super admin sees all data
    }

    if ($user->role == "admin") {
        // Filter vendors based on user's city matching admin's city
        if ($user->addresses && $user->addresses->city) {
            $query->whereHas('user', function($userQuery) use ($user) {
                $userQuery->whereHas('addresses', function($addressQuery) use ($user) {
                    $addressQuery->where('city', $user->addresses->city);
                });
            });
        }
        return;
    }
}

}
