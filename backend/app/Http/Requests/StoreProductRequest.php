<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // IMPORTANT : Mettre à true, sinon tu auras une erreur 403 Forbidden
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Validation basée sur ton schéma DB (DB.jpg)
            'category_id'       => 'required|exists:categories,id',
            'brand_id'          => 'nullable|exists:brands,id',
            'name'              => 'required|string|max:255',
            'description'       => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price'             => 'required|numeric|min:0',
            'discount'          => 'nullable|numeric|min:0|lt:price', // La remise doit être inférieure au prix
            'shipping'          => 'nullable|numeric|min:0',
            'is_featured'       => 'boolean',
            'is_active'         => 'boolean',
            'stock'             => 'nullable|integer|min:0', // Si tu gères le stock global ici
            // Pour les images, on verra plus tard, mais tu peux ajouter :
            // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}