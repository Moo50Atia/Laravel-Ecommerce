<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogReview extends Model
{ 
        /** @use HasFactory<\Database\Factories\BlogReviewFactory> */
    use HasFactory;

    protected $fillable = ["rate", "user_id", "blog_id"];


    public function blog()
{
    return $this->belongsTo(Blog::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}

}
