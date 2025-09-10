<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'email_verified_at' => 'nullable|date',
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:30',
            'avatar' => 'nullable|file|image|max:2048',
            'role' => 'nullable|string|in:admin,vendor,user',
            'status' => 'nullable|string|in:active,suspended,banned',
        ];
    }
}
