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

    // Mutators
    public function setAddressLine1Attribute($value)
    {
        $this->attributes['address_line1'] = trim(ucwords(strtolower($value)));
    }

    public function setAddressLine2Attribute($value)
    {
        $this->attributes['address_line2'] = trim(ucwords(strtolower($value)));
    }

    public function setCityAttribute($value)
    {
        $this->attributes['city'] = trim(ucwords(strtolower($value)));
    }

    public function setStateAttribute($value)
    {
        $this->attributes['state'] = trim(ucwords(strtolower($value)));
    }

    public function setCountryAttribute($value)
    {
        $this->attributes['country'] = trim(ucwords(strtolower($value)));
    }

    public function setPostalCodeAttribute($value)
    {
        $this->attributes['postal_code'] = strtoupper(trim($value));
    }
    public function user()
{
    return $this->belongsTo(User::class);
}

}
