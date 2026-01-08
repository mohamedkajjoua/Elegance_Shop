<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    // Autoriser l'action (par défaut c'est false, il faut mettre true)
    public function authorize(): bool
    {
        return true; 
    }

    // Les règles de validation
    public function rules(): array
    {
        return [
            // Le nom est obligatoire, doit être une chaîne, max 255 chars, et UNIQUE dans la table categories
            'name' => 'required|string|max:255|unique:categories,name',
        ];
    }
    
    // Messages d'erreur personnalisés (optionnel mais sympa)
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de la catégorie est obligatoire.',
            'name.unique' => 'Cette catégorie existe déjà.',
        ];
    }
}