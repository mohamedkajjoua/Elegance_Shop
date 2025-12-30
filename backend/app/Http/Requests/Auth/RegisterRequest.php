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

            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],


            'email'      => ['required', 'string', 'email', 'max:150', 'unique:users,email'],

            'password'   => ['required', 'string', 'min:8', 'confirmed'],


            'role'       => ['nullable', 'string', Rule::in(['customer', 'admin', 'editor'])],

            'phone'      => ['nullable', 'string', 'max:30'],

            'avatar'     => ['nullable', 'image', 'max:2048'],


        ];
    }
}
