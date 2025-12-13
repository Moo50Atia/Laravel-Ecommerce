<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'content' => 'required|string',
            'author_id' => 'nullable',
            'is_published' => 'nullable',
            'published_at' => 'nullable|date',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'العنوان مطلوب',
            'title.max' => 'العنوان طويل جدًا',
            'content.required' => 'المحتوى مطلوب',
            'published_at.date' => 'تاريخ النشر غير صحيح',
            'featured_image.image' => 'يجب أن يكون الملف صورة',
            'featured_image.mimes' => 'امتداد الصورة غير مدعوم',
            'featured_image.max' => 'حجم الصورة يجب ألا يتجاوز 2MB',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'العنوان',
            'short_description' => 'الوصف المختصر',
            'content' => 'المحتوى',
            'author_id' => 'الكاتب',
            'is_published' => 'حالة النشر',
            'published_at' => 'تاريخ النشر',
            'featured_image' => 'الصورة المميزة',
        ];
    }
}
