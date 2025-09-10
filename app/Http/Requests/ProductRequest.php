<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
        'name' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'short_description' => ['nullable', 'string', 'max:500'],
        'price' => ['required', 'numeric', 'min:0'],
        'is_active' => ['nullable', 'boolean'],
        'is_featured' => ['nullable', 'boolean'],
        'weight' => ['nullable', 'string', 'max:50'],
        'dimensions' => ['nullable', 'string', 'max:100'],
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'additional_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        'category' => ['nullable', 'string', 'max:255'],
        'vendor_id' => ['nullable', 'exists:vendors,id'],

        ];
    }
}
