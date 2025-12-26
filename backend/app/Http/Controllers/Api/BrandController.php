<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    
    public function index()
    {
        return Brand::latest()->get();
    }


    public function show(Brand $brand)
    {
        return $brand;
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'slug' => 'required|string|unique:brands,slug',
            'description' => 'nullable|string',
            'logo' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $brand = Brand::create($data);

        return response()->json($brand, 201);
    }


    public function update(Request $request, Brand $brand)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:150',
            'slug' => 'sometimes|string|unique:brands,slug,' . $brand->id,
            'description' => 'nullable|string',
            'logo' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $brand->update($data);

        return response()->json($brand);
    }


    public function destroy(Brand $brand)
    {
        $brand->delete();

        return response()->json(['message' => 'Brand deleted successfully']);
    }
}
