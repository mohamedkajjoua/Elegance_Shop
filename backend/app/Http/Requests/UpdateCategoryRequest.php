<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // On récupère l'ID de la catégorie depuis la route (ex: categories/5)
        $categoryId = $this->route('category'); // 'category' correspond au paramètre dans la route apiResource

        return [
            // On vérifie l'unicité sauf pour l'ID actuel
            'name' => 'required|string|max:255|unique:categories,name,' . $categoryId,
        ];
    }
}