<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    /** @use HasFactory<\Database\Factories\ProductVariantFactory> */
    use HasFactory;
    protected $fillable = [
        "option_name",
        "option_value",
        "price_modifier",
        "stock",
        "product_id",
    ];
    protected $casts = [    

        "price_modifier" => "float",
        "stock" => "int",
        ];
    public function product()
{
    return $this->belongsTo(Product::class);
}

}
