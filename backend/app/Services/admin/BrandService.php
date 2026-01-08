<?php

namespace App\Services\Admin;

use App\Models\Brand;

class BrandService
{
    // Récupérer toutes les marques
    public function getAll()
    {
        return Brand::query()
            ->select(['id', 'name', 'created_at']) // created_at est utile pour l'admin
            ->orderBy('name', 'asc')
            ->get();
    }

    // 1. CRÉER une marque
    public function createBrand(array $data)
    {
        return Brand::create([
            'name' => $data['name']
            // Ajoutez ici d'autres champs si votre table brands en a (ex: logo, description)
        ]);
    }

    // 2. RÉCUPÉRER une seule marque par ID
    public function getBrandById($id)
    {
        return Brand::findOrFail($id);
    }

    // 3. METTRE À JOUR une marque
    public function updateBrand($id, array $data)
    {
        $brand = Brand::findOrFail($id);
        
        $brand->update([
            'name' => $data['name']
        ]);

        return $brand;
    }

    // 4. SUPPRIMER une marque
    public function deleteBrand($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();
        
        return true;
    }
}