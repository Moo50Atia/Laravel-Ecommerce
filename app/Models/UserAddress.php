<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    /** @use HasFactory<\Database\Factories\UserAddressFactory> */
    use HasFactory;
    protected $fillable = [
        "address_line1",
        "address_line2",
        "city",
       "state",
       "country",
       "postal_code",
    ];
    public function user()
{
    return $this->belongsTo(User::class);
}

}
