<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /** @use HasFactory<\Database\Factories\OrderItemFactory> */
    use HasFactory;
    protected $fillable = [
        "quantity",
        "price",
        "order_id",
        "product_id",
        "variant_id",
        "vendor_id",
    ];

    public function order()
{
    return $this->belongsTo(Order::class);
}

public function product()
{
    return $this->belongsTo(Product::class);
}

public function variant()
{
    return $this->belongsTo(ProductVariant::class);
}
public function vendor()
{
    return $this->belongsTo(Vendor::class); 
}


}
