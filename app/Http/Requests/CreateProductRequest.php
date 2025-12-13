<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'vendor_id' => 'nullable|exists:vendors,id',
            'is_active' => 'boolean',
            'short_description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم المنتج مطلوب',
            'name.max' => 'اسم المنتج طويل جدًا',
            'vendor_id.exists' => 'البائع غير موجود',
            'is_active.boolean' => 'قيمة التفعيل غير صحيحة',
            'images.*.image' => 'الملف يجب أن يكون صورة',
            'images.*.mimes' => 'امتداد الصورة غير مدعوم',
            'images.*.max' => 'حجم الصورة يجب ألا يتجاوز 2MB',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'اسم المنتج',
            'description' => 'الوصف',
            'vendor_id' => 'البائع',
            'is_active' => 'مفعل',
            'short_description' => 'الوصف المختصر',
            'images' => 'الصور',
        ];
    }
}
