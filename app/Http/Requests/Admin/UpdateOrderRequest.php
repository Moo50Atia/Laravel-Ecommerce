<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:pending,processing,shipped,delivered,canceled,refunded',
            'payment_status' => 'required|in:paid,unpaid,failed',
            'total_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'shipping_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:credit_card,cod,bank_transfer',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'حالة الطلب مطلوبة',
            'status.in' => 'قيمة حالة الطلب غير صحيحة',
            'payment_status.required' => 'حالة الدفع مطلوبة',
            'payment_status.in' => 'قيمة حالة الدفع غير صحيحة',
            'total_amount.numeric' => 'إجمالي المبلغ يجب أن يكون رقم',
            'discount_amount.numeric' => 'قيمة الخصم يجب أن تكون رقم',
            'shipping_amount.numeric' => 'قيمة الشحن يجب أن تكون رقم',
            'payment_method.in' => 'طريقة الدفع غير صحيحة',
            'notes.max' => 'الملاحظات يجب ألا تتجاوز 1000 حرف',
        ];
    }

    public function attributes(): array
    {
        return [
            'status' => 'حالة الطلب',
            'payment_status' => 'حالة الدفع',
            'total_amount' => 'إجمالي المبلغ',
            'discount_amount' => 'قيمة الخصم',
            'shipping_amount' => 'قيمة الشحن',
            'payment_method' => 'طريقة الدفع',
            'notes' => 'الملاحظات',
        ];
    }
}


