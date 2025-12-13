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

    public function messages(): array
    {
        return [
            'name.required' => 'اسم المنتج مطلوب',
            'name.max' => 'اسم المنتج طويل جدًا',
            'price.required' => 'السعر مطلوب',
            'price.numeric' => 'السعر يجب أن يكون رقم',
            'vendor_id.exists' => 'البائع غير موجود',
            'image.image' => 'الصورة الرئيسية يجب أن تكون صورة',
            'image.mimes' => 'امتداد الصورة غير مدعوم',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 2MB',
            'additional_images.*.image' => 'كل ملف صورة إضافية يجب أن يكون صورة',
            'additional_images.*.mimes' => 'امتداد الصورة الإضافية غير مدعوم',
            'additional_images.*.max' => 'حجم الصورة الإضافية يجب ألا يتجاوز 5MB',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'اسم المنتج',
            'description' => 'الوصف',
            'short_description' => 'الوصف المختصر',
            'price' => 'السعر',
            'is_active' => 'مفعل',
            'is_featured' => 'مميز',
            'weight' => 'الوزن',
            'dimensions' => 'الأبعاد',
            'image' => 'الصورة الرئيسية',
            'additional_images' => 'الصور الإضافية',
            'category' => 'التصنيف',
            'vendor_id' => 'البائع',
        ];
    }
}
