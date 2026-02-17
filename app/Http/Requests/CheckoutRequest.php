<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'shipping_address' => 'required|string|max:500',
            'billing_address' => 'required_if:same_as_shipping,0|nullable|string|max:500',
            'payment_method' => 'required|in:credit_card,cod,bank_transfer',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
