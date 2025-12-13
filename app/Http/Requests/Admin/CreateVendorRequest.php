<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreateVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'store_name' => 'required|string|max:255|unique:vendors,store_name',
            'email' => 'required|email|unique:vendors,email',
            'phone' => 'required|string|max:20',
            'description' => 'nullable|string|max:1000',
            'commission_rate' => 'nullable|numeric|min:0|max:100',

            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|unique:users,email',
            'user_phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'store_name.required' => 'اسم المتجر مطلوب',
            'store_name.unique' => 'اسم المتجر مستخدم من قبل',
            'email.required' => 'البريد الإلكتروني للمتجر مطلوب',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل',
            'phone.required' => 'رقم الهاتف مطلوب',
            'commission_rate.numeric' => 'نسبة العمولة يجب أن تكون رقم',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
        ];
    }

    public function attributes(): array
    {
        return [
            'store_name' => 'اسم المتجر',
            'email' => 'بريد المتجر',
            'phone' => 'هاتف المتجر',
            'description' => 'وصف المتجر',
            'commission_rate' => 'نسبة العمولة',
            'user_name' => 'اسم المستخدم',
            'user_email' => 'بريد المستخدم',
            'user_phone' => 'هاتف المستخدم',
            'password' => 'كلمة المرور',
        ];
    }
}


