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

    // Mutators
    public function setOptionNameAttribute($value)
    {
        $this->attributes['option_name'] = trim(ucwords(strtolower($value)));
    }

    public function setOptionValueAttribute($value)
    {
        $this->attributes['option_value'] = trim(ucwords(strtolower($value)));
    }

    public function setPriceModifierAttribute($value)
    {
        $this->attributes['price_modifier'] = round((float)$value, 2);
    }
    protected $casts = [    

        "price_modifier" => "float",
        "stock" => "int",
        ];
    public function product()
{
    return $this->belongsTo(Product::class);
}

}
