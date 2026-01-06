<?php

namespace App\Services\Admin;

use App\Models\Brand;

class BrandService
{
    public function getAll()
    {
        return Brand::query()
            ->select(['id', 'name'])
            ->orderBy('name', 'asc')
            ->get();
    }
}
