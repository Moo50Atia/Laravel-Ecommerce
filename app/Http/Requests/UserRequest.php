<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'email_verified_at' => 'nullable|date',
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:30',
            'avatar' => 'nullable|file|image|max:2048',
            'role' => 'nullable|string|in:admin,vendor,user',
            'status' => 'nullable|string|in:active,suspended,banned',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
            'password.min' => 'كلمة المرور يجب ألا تقل عن 6 أحرف',
            'avatar.image' => 'الصورة يجب أن تكون من نوع صورة',
            'role.in' => 'قيمة الدور غير صحيحة',
            'status.in' => 'قيمة الحالة غير صحيحة',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'الاسم',
            'email' => 'البريد الإلكتروني',
            'email_verified_at' => 'تاريخ تأكيد البريد',
            'password' => 'كلمة المرور',
            'phone' => 'رقم الهاتف',
            'avatar' => 'الصورة',
            'role' => 'الدور',
            'status' => 'الحالة',
        ];
    }
}
