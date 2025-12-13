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
            'user_id' => 'nullable|integer|exists:users,id',
            'vendor_id' => 'nullable|integer|exists:vendors,id',
            'order_number' => 'nullable|string',
            'status' => 'nullable|string|in:pending,processing,shipped,delivered,canceled,refunded',
            'total_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'shipping_amount' => 'nullable|numeric|min:0',
            'grand_total' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|in:credit_card,cod,bank_transfer',
            'payment_status' => 'nullable|string|in:paid,unpaid,failed',
            'shipping_address' => 'nullable|string',
            'billing_address' => 'nullable|string',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.exists' => 'المستخدم المحدد غير موجود',
            'vendor_id.exists' => 'البائع المحدد غير موجود',
            'status.in' => 'قيمة حالة الطلب غير صحيحة',
            'total_amount.numeric' => 'إجمالي المبلغ يجب أن يكون رقم',
            'discount_amount.numeric' => 'قيمة الخصم يجب أن تكون رقم',
            'shipping_amount.numeric' => 'قيمة الشحن يجب أن تكون رقم',
            'grand_total.numeric' => 'الإجمالي النهائي يجب أن يكون رقم',
            'payment_method.in' => 'طريقة الدفع غير صحيحة',
            'payment_status.in' => 'حالة الدفع غير صحيحة',
            'notes.max' => 'الملاحظات يجب ألا تتجاوز 1000 حرف',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id' => 'المستخدم',
            'vendor_id' => 'البائع',
            'order_number' => 'رقم الطلب',
            'status' => 'حالة الطلب',
            'total_amount' => 'إجمالي المبلغ',
            'discount_amount' => 'قيمة الخصم',
            'shipping_amount' => 'قيمة الشحن',
            'grand_total' => 'الإجمالي النهائي',
            'payment_method' => 'طريقة الدفع',
            'payment_status' => 'حالة الدفع',
            'shipping_address' => 'عنوان الشحن',
            'billing_address' => 'عنوان الفاتورة',
            'notes' => 'الملاحظات',
        ];
    }
}
