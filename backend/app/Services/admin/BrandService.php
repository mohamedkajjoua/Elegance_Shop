<?php

namespace App\Services\Admin;

use App\Models\Brand;

class BrandService
{

    public function getAll()
    {
        return Brand::query()
            ->select(['id', 'name'])
            ->select(['id', 'name', 'created_at'])
            ->orderBy('name', 'asc')
            ->get();
    }


    public function createBrand(array $data)
    {
        return Brand::create($data);
    }


    public function getBrandById($id)
    {
        return Brand::findOrFail($id);
    }


    public function updateBrand($id, array $data)
    {
        $brand = Brand::findOrFail($id);

        $brand->update($data);

        return $brand;
    }


    public function deleteBrand($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        return true;
    }
}
