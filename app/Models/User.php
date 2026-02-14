<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Image;
use App\Traits\AdminScopeable;
use App\Traits\HasActivityLog;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, AdminScopeable, HasActivityLog;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        "phone",
        "avatar",
        'role',
        'status',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship path from User to user_addresses for admin city scoping.
     * User → Addresses (direct)
     */
    protected function getAdminCityRelationPath(): string
    {
        return 'addresses';
    }

    // Mutators
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(ucwords(strtolower($value)));
    }

    public function setPhoneAttribute($value)
    {
        // Remove all non-numeric characters except +
        $this->attributes['phone'] = preg_replace('/[^0-9+]/', '', $value);
    }

    public function setAvatarAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['avatar'] = 'default-avatar.png';
        } else {
            $this->attributes['avatar'] = $value;
        }
    }

    public function addresses()
    {
        return $this->hasOne(UserAddress::class);
    }

    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'author_id');
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, "imageable");
    }
}
