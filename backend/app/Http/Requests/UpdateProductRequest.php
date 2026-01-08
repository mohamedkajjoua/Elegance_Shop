<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // IMPORTANT : Mettre à true
    }

    public function rules(): array
    {
        return [
            'category_id'       => 'sometimes|exists:categories,id',
            'brand_id'          => 'nullable|exists:brands,id',
            'name'              => 'sometimes|string|max:255',
            'description'       => 'sometimes|string',
            'short_description' => 'nullable|string|max:500',
            'price'             => 'sometimes|numeric|min:0',
            'discount'          => 'nullable|numeric|min:0',
            'shipping'          => 'nullable|numeric|min:0',
            'is_featured'       => 'boolean',
            'is_active'         => 'boolean',
            'stock'             => 'nullable|integer|min:0',
        ];
    }
}