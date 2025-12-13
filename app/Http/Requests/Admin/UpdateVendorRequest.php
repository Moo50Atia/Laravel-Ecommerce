<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $vendorId = $this->route('vendor')?->id ?? 'NULL';

        return [
            'store_name' => 'required|string|max:255|unique:vendors,store_name,' . $vendorId,
            'email' => 'required|email|unique:vendors,email,' . $vendorId,
            'phone' => 'required|string|max:20',
            'description' => 'nullable|string|max:1000',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive,suspended',
        ];
    }

    public function messages(): array
    {
        return [
            'store_name.required' => 'اسم المتجر مطلوب',
            'store_name.unique' => 'اسم المتجر مستخدم من قبل',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل',
            'phone.required' => 'رقم الهاتف مطلوب',
            'commission_rate.numeric' => 'نسبة العمولة يجب أن تكون رقم',
            'status.required' => 'حالة المتجر مطلوبة',
            'status.in' => 'قيمة حالة المتجر غير صحيحة',
        ];
    }

    public function attributes(): array
    {
        return [
            'store_name' => 'اسم المتجر',
            'email' => 'البريد الإلكتروني',
            'phone' => 'الهاتف',
            'description' => 'الوصف',
            'commission_rate' => 'نسبة العمولة',
            'status' => 'الحالة',
        ];
    }
}


