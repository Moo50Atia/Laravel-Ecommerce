<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;
    protected $fillable = [
        "name",
        "description",
        "price",
        "weight",
        "dimensions",
        "short_description",
        "category_id",
        "vendor_id",
    ];

    // Mutators
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(ucwords(strtolower($value)));
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = round((float)$value, 2);
    }

    public function setWeightAttribute($value)
    {
        // Convert to grams if needed, or keep as is
        $this->attributes['weight'] = round((float)$value, 2);
    }
    public function category()
{
    return $this->belongsTo(Category::class, 'category_id');
}

public function variants()
{
    return $this->hasMany(ProductVariant::class);
}

public function productReviews()
{
    return $this->hasMany(ProductReview::class);
}

public function coupon()
{
    return $this->hasOne(Coupon::class);
}

    public function vendor(){

    return $this->belongsTo(Vendor::class);
}
public function getVendorNameAttribute()
{
    return $this->vendor->user->name ?? "Unknown Vendor";
}
public function image()
{
    return $this->morphOne(Image::class, "imageable")->where('type', 'card');
}

public function images()
{
    return $this->morphMany(Image::class, 'imageable')->where('type', 'detail');
}
public function getTotalStockAttribute()
{
    return $this->variants->sum("stock");
}


public function orderItems()
{
    return $this->hasMany(OrderItem::class);
}

public function orders()
{
    return $this->belongsToMany(Order::class, 'order_items')
                ->withPivot('quantity', 'price', 'variant_id')
                ->withTimestamps();
}
// public function wishlistedByUsers()
// {
//     return $this->belongsToMany(User::class, "wishlists", "product_id", "user_id");
// }
public function wishlists()
{
    return $this->hasMany(Wishlist::class);
}


 #[Scope]
    protected function ForAdmin(Builder $query, User $user ): void
    {
        if ($user->role == "superadmin")  {
            return ;               // سوبر أدمن يشوف كل البيانات
        }

        if ($user->role == "admin") {
            
            // Filter products based on vendor's city matching admin's city
            if ($user->addresses && $user->addresses->city) {
                $query->whereHas('vendor', function($vendorQuery) use ($user) {
                    $vendorQuery->whereHas('user', function($userQuery) use ($user) {
                        $userQuery->whereHas('addresses', function($addressQuery) use ($user) {
                            $addressQuery->where('city', $user->addresses->city);
                        });
                    });
                }); // يجيب نطاق الأدمن فقط
            }
            return;
        }

        // أي دور تاني ممنوع يشوف هنا
        $query->whereRaw('1=0');  // يرجّع فاضي
    }



}



