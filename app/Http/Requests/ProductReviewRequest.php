<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // We'll handle authorization in the controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'rating.required' => 'يجب اختيار تقييم للمنتج',
            'rating.integer' => 'التقييم يجب أن يكون رقم صحيح',
            'rating.min' => 'التقييم يجب أن يكون على الأقل نجمة واحدة',
            'rating.max' => 'التقييم يجب أن يكون على الأكثر 5 نجوم',
            'comment.string' => 'التعليق يجب أن يكون نص',
            'comment.max' => 'التعليق يجب أن يكون أقل من 1000 حرف',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'rating' => 'التقييم',
            'comment' => 'التعليق',
        ];
    }
}
