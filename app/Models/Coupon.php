<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    /** @use HasFactory<\Database\Factories\CouponFactory> */
    use HasFactory;
    protected $fillable = [
        "code",
        "discount_type",
        "discount_value",
        "max_uses",
       " valid_from",
       " valid_to",
       " min_order_amount",
       "discription",
    ];

public function product()
{
    return $this->belongsTo(Product::class);
}

// app/Models/Coupon.php

// جوه موديل Coupon
public function getVendorNameAttribute()
{
    return $this->product?->vendorProducts?->vendor->store_name ?? "Unknown Vendor";
}





}
