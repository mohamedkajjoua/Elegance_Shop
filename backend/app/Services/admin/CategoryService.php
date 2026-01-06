<?php

namespace App\Services\Admin;

use App\Models\Categorie;

class CategoryService
{

    public function getAll()
    {
        return Categorie::all();
    }
}
