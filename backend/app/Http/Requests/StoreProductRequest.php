// Dans App\Http\Requests\StoreProductRequest.php

public function rules(): array
{
    return [
        // Champs de la table 'products'
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id', // Exemple

        // Champs pour la relation 'variants' (IMPORTANT)
        'variants' => 'required|array|min:1', // Doit être un tableau
        'variants.*.color' => 'required|string', // Validation de chaque élément du tableau
        'variants.*.size' => 'required|string',
        'variants.*.stock' => 'required|integer|min:0',
    ];
}