<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use App\Models\User;
use App\Traits\AdminScopeable;
use App\Traits\HasActivityLog;

class Blog extends Model
{
    /** @use HasFactory<\Database\Factories\BlogFactory> */
    use HasFactory, AdminScopeable, HasActivityLog;

    protected $fillable = [
        "title",
        "slug",
        "content",
        "short_description",
        "author_id",
        "is_published",
        "published_at",
    ];

    /**
     * Relationship path from Blog to user_addresses for admin city scoping.
     * Blog → Author (User) → Addresses
     */
    protected function getAdminCityRelationPath(): string
    {
        return 'author.addresses';
    }

    // Mutators
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = trim(ucwords(strtolower($value)));
        // Auto-generate slug if not provided
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = \Illuminate\Support\Str::slug($value);
        }
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = \Illuminate\Support\Str::slug($value);
    }

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = trim($value);
    }

    protected $casts = [
        "created_at" => "datetime",
        "published_at" => "datetime",
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function coverImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', 'cover');
    }

    public function detailImages()
    {
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
}
