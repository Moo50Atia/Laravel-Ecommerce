<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBlogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'content' => 'required|string',
            'author_id' => 'nullable',
            'is_published' => 'boolean',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'العنوان مطلوب',
            'title.max' => 'العنوان طويل جدًا',
            'content.required' => 'المحتوى مطلوب',
            'is_published.boolean' => 'قيمة حالة النشر غير صحيحة',
            'cover_image.image' => 'يجب أن يكون الملف صورة',
            'cover_image.mimes' => 'امتداد الصورة غير مدعوم',
            'cover_image.max' => 'حجم الصورة يجب ألا يتجاوز 2MB',
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
            'cover_image' => 'صورة الغلاف',
        ];
    }
}
