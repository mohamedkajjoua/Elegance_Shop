<?php

namespace App\Services\Admin;

use App\Models\Category;

class CategoryService
{

    public function getAll()
    {
        return Category::query()
            ->select(['id', 'name'])
            ->select(['id', 'name', 'created_at'])
            ->orderBy('name', 'asc')
            ->get();
    }

    //  CRÉER une catégorie
    public function createCategory(array $data)
    {
        return Category::create([
            'name' => $data['name']
            // Ajoute d'autres champs ici si ta table en a (ex: image, description)
        ]);
    }

    //  RÉCUPÉRER une seule catégorie par ID
    public function getCategoryById($id)
    {
        return Category::findOrFail($id); // Renvoie une erreur 404 automatique si pas trouvé
    }

    //  METTRE À JOUR une catégorie
    public function updateCategory($id, array $data)
    {
        $category = Category::findOrFail($id);

        $category->update([
            'name' => $data['name']
        ]);

        return $category;
    }

    //  SUPPRIMER une catégorie
    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return true;
    }
}
