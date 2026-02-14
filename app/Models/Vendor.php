<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use App\Traits\AdminScopeable;
use App\Traits\HasActivityLog;

class Vendor extends Model
{
    /** @use HasFactory<\Database\Factories\VendorFactory> */
    use HasFactory, AdminScopeable, HasActivityLog;

    protected $fillable = [
        "store_name",
        "slug",
        "description",
        "commission_rate",
        "rating",
        'email',
        'phone',
        'user_id',
    ];

    /**
     * Relationship path from Vendor to user_addresses for admin city scoping.
     * Vendor → User → Addresses
     */
    protected function getAdminCityRelationPath(): string
    {
        return 'user.addresses';
    }

    // Mutators
    public function setStoreNameAttribute($value)
    {
        $this->attributes['store_name'] = trim(ucwords(strtolower($value)));
        // Auto-generate slug if not provided
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = \Illuminate\Support\Str::slug($value);
        }
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = \Illuminate\Support\Str::slug($value);
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = trim(strip_tags($value));
    }

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

    public function orders()
    {
        return $this->belongsToMany(Order::class, "order_items", "vendor_id", "order_id")
            ->withPivot(["product_id", "quantity", "price", "variant_id"])
            ->withTimestamps()
            ->distinct();
    }
}
