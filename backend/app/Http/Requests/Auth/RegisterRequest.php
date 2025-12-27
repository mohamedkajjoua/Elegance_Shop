<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // تم تحديث الطول إلى 100 ليطابق المايجريشن
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],

            // تم تحديث الطول إلى 150
            'email'      => ['required', 'string', 'email', 'max:150', 'unique:users,email'],

            'password'   => ['required', 'string', 'min:8', 'confirmed'],

            // مهم جداً: الـ Role يجب أن يكون واحداً من القيم المحددة في الـ Enum
            'role'       => ['required', 'string', Rule::in(['customer', 'admin', 'editor'])],

            'phone'      => ['nullable', 'string', 'max:30'],

            'avatar'     => ['nullable', 'image', 'max:2048'],

            // قمت بإزالة is_active و last_login لأنها غير موجودة في المايجريشن
        ];
    }
}
