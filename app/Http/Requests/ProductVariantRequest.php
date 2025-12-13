<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'variants_json' => ['sometimes', 'nullable', 'string'],
            'variants' => ['sometimes', 'array'],
            'variants.*.id' => ['sometimes', 'integer', 'exists:product_variants,id'],
            'variants.*.option_name' => ['sometimes', 'string', 'max:255'],
            'variants.*.option_value' => ['sometimes', 'string', 'max:255'],
            'variants.*.price_modifier' => ['sometimes', 'numeric'],
            'variants.*.stock' => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'المنتج مطلوب',
            'product_id.exists' => 'المنتج غير موجود',
            'variants.array' => 'تنسيق المتغيرات غير صحيح',
            'variants.*.stock.integer' => 'المخزون يجب أن يكون رقم صحيح',
            'variants.*.stock.min' => 'المخزون لا يمكن أن يكون سالب',
        ];
    }

    public function attributes(): array
    {
        return [
            'product_id' => 'المنتج',
            'variants' => 'المتغيرات',
            'variants.*.option_name' => 'اسم الخيار',
            'variants.*.option_value' => 'قيمة الخيار',
            'variants.*.price_modifier' => 'تعديل السعر',
            'variants.*.stock' => 'المخزون',
        ];
    }
}


