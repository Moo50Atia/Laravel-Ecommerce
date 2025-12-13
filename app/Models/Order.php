<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;


class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;
    protected $fillable =[
        "status",
        "total_amount",
        "discount_amount",
        "shipping_amount",
        "grand_total",
        "payment_method",
        "payment_status",
        "shipping_address",
        "billing_address",
        "notes",
        "order_number",
        "vendor_id",
        "user_id",
    ];

    // Mutators
    public function setTotalAmountAttribute($value)
    {
        $this->attributes['total_amount'] = round((float)$value, 2);
    }

    public function setDiscountAmountAttribute($value)
    {
        $this->attributes['discount_amount'] = round((float)$value, 2);
    }

    public function setShippingAmountAttribute($value)
    {
        $this->attributes['shipping_amount'] = round((float)$value, 2);
    }

    public function setGrandTotalAttribute($value)
    {
        $this->attributes['grand_total'] = round((float)$value, 2);
    }

    public function setOrderNumberAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['order_number'] = 'ORD-' . strtoupper(uniqid());
        } else {
            $this->attributes['order_number'] = $value;
        }
    }
    protected $casts = [
        "shipping_address" => "array",
        "billing_address"  => "array",
    ];
    
    public function user()
{
    return $this->belongsTo(User::class);
}

public function items()
{
    return $this->hasMany(OrderItem::class);
}
public function products()
{
    return $this->belongsToMany(Product::class, 'order_items')
                ->withPivot('quantity', 'price', 'variant_id')
                ->withTimestamps();
}
public function vendor (){
    return $this->belongsTo(Vendor::class);
}
#[Scope]
protected function ForAdmin(Builder $query, User $user): void
{
    if ($user->role == "superadmin" || $user->role == "super_admin") {
        return;               // Super admin sees all data
    }

    // Filter orders based on user's city
    if ($user->addresses && $user->addresses->city) {
        $city = $user->addresses->city;
        $query->where(function($q) use ($city, $user) {
            // Filter by order's user city
            $q->whereHas('user', function($userQuery) use ($city) {
                $userQuery->whereHas('addresses', function($addressQuery) use ($city) {
                    $addressQuery->where('city', $city);
                });
            });
        });
    }
    return;
}
}