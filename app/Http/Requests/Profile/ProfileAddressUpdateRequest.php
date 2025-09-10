<?php

namespace App\Http\Requests\Profile;


use Illuminate\Foundation\Http\FormRequest;


class ProfileAddressUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
  public function rules(): array
{
    return [ 
        
        "address_line1" => ["required", "string", "max:255"],
        "address_line2" => ["nullable", "string", "max:255"],
        "city" => ["required", "string", "max:100"],
        "state" => ["required", "string", "max:100"],
        "country" => ["required", "string", "max:100"],
        "postal_code" => ["required", "string", "max:6"],
    ];
}

}
