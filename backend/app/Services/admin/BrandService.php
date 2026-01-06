<?php

namespace App\Services\Admin;

use App\Models\Brand;

class BrandService
{
    public function getAll()
    {
        return Brand::all();
    }
}
