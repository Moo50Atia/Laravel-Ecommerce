<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

class UpdatePersonalInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
             "name" => ["required", "string", "max:255"],
        "email" => [
            "required",
            "string",
            "lowercase",
            "email",
            "max:255",
            Rule::unique(User::class)->ignore($this->user()->id),
        ],
        ];
    }
}
