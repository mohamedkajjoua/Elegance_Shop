<?php

namespace App\Http\Requests\admin\product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productId = $this->route('id');

        return [
            'name' => [
                'sometimes',
                'string',
                'min:3',
                'max:255',

                Rule::unique('products')->ignore($productId)->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
            'description' => 'sometimes|string|min:10',
            'short_description' => 'sometimes|string|min:5|max:255',
            'price' => 'sometimes|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'category_id' => 'sometimes|exists:categories,id',
            'brand_id' => 'sometimes|exists:brands,id',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'shipping' => 'nullable|numeric|min:0',
            'variants' => 'nullable|array',
            'variants.*.color' => 'required_with:variants|string',
            'variants.*.size' => 'required_with:variants|string',
            'variants.*.price' => 'required_with:variants|numeric|min:0',
            'variants.*.stock' => 'required_with:variants|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'deleted_images'   => 'nullable|array',
            'deleted_images.*' => 'integer',
        ];
    }
}
