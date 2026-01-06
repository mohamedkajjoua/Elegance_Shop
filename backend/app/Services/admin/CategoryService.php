<?php

namespace App\Services\Admin;

use App\Models\category;

class CategoryService
{

    public function getAll()
    {
        return Category::query()
            ->select(['id', 'name'])
            ->orderBy('name', 'asc')
            ->get();
    }
}
