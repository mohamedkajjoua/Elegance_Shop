<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'addresse_id' => ['required', 'integer', 'exists:addresses,id'],
            'payment_method' => ['required', 'in:cod,card,paypal'],
        ];
    }
}
