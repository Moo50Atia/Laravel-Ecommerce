<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /** @use HasFactory<\Database\Factories\ImagesFactory> */
    use HasFactory;
    protected $fillable = ["url", "type", "imageable_id", "imageable_type"];

    public function imageable()
    {
        return $this->morphTo();
    }
}
