<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;

class Blog extends Model
{
    /** @use HasFactory<\Database\Factories\BlogFactory> */
    use HasFactory;
    protected $fillable = [
        "title",
        "slug",
        "content",
        "short_description",
        "author_id",
        "is_published",
        "published_at",
    ];
    protected $casts = [
        "created_at"=> "datetime",
        "published_at"=> "datetime",
    ];
    public function author()
{
    return $this->belongsTo(User::class, 'author_id');
}
public function coverImage() {
    return $this->morphOne(Image::class, 'imageable')->where('type', 'cover');
}

public function detailImages() {
    return $this->morphMany(Image::class, 'imageable')->where('type', 'detail');
}
public function reviews()
{
    return $this->hasMany(BlogReview::class);
}
public function image()
{
    return $this->morphOne(Image::class, 'imageable');
}

public function getStatusAttribute()
{
    if ($this->is_published) {
        return 'published';
    }
    return 'draft';
}

public function getStatusTextAttribute()
{
    return $this->status === 'published' ? 'منشورة' : 'مسودة';
}

#[Scope]
protected function ForAdmin(Builder $query, User $user): void
{
    if ($user->role == "superadmin" || $user->role == "super_admin") {
        return;               // سوبر أدمن يشوف كل البيانات
    }

    if ($user->role == "admin") {
        // Filter blogs based on author's city matching admin's city
        if ($user->addresses && $user->addresses->city) {
            $query->whereHas('author', function($authorQuery) use ($user) {
                $authorQuery->whereHas('addresses', function($addressQuery) use ($user) {
                    $addressQuery->where('city', $user->addresses->city);
                });
            });
        }
        return;
    }

    // أي دور تاني ممنوع يشوف هنا
    $query->whereRaw('1=0');  // يرجّع فاضي
}

}
