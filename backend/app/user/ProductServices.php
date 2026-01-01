<?php
namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function getAllProducts()
    {
        return Product::latest()->paginate(10);
    }

    public function createProduct(array $data)
    {
        $data['slug'] = Str::slug($data['name']);

        if (isset($data['image'])) {
            $data['image'] = $this->uploadImage($data['image']);
        }

        return Product::create($data);
    }

    public function updateProduct(Product $product, array $data)
    {
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if (isset($data['image'])) {
            // Supprimer l'ancienne image si elle existe
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $this->uploadImage($data['image']);
        }

        $product->update($data);
        return $product;
    }

    public function deleteProduct(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        return $product->delete();
    }

    private function uploadImage($image)
    {
        return $image->store('products', 'public');
    }
}