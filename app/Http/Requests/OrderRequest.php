<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'integer||nullable',
            'vendor_id' => 'integer||nullable',
            'order_number' => 'string||nullable',
            'status' => 'string||nullable',
            'total_amount' => 'string||nullable',
            'discount_amount' => 'string||nullable',
            'shipping_amount' => 'string||nullable',
            'grand_total' => 'string||nullable',
            'payment_method' => 'string||nullable',
            'payment_status' => 'string||nullable',
            'shipping_address' => 'string||nullable',
            'billing_address' => 'string||nullable',
            'notes' => 'string||nullable',
        ];
    }
}
