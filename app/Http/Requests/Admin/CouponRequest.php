<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $couponId = $this->route('coupon') ? $this->route('coupon')->id : null;

        return [
            'code' => 'required|string|max:50|unique:coupons,code,' . $couponId,
            'discount_type' => 'required|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'required|boolean',
            'description' => 'nullable|string|max:500',
            'product_id' => 'nullable|exists:products,id'
        ];
    }
}
