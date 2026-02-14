<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Traits\AdminScopeable;
use App\Traits\HasActivityLog;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory, AdminScopeable, HasActivityLog;

    protected $fillable = [
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
        "coupon_id",
    ];

    /**
     * Relationship path from Order to user_addresses for admin city scoping.
     * Order → User → Addresses
     */
    protected function getAdminCityRelationPath(): string
    {
        return 'user.addresses';
    }

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

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
