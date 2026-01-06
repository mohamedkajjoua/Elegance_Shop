<?php

namespace App\Services\User;

use App\Models\Product;

class ProductSearchService
{

    /* 
           Product search
    */

    public function searchProducts(string $query, int $perPage = 10)
    {

        return Product::where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->orWhere('short_description', 'like', "%{$query}%")
                ->orWhereHas('brand', function ($qBrand) use ($query) {
                    $qBrand->where('name', 'like', "%{$query}%");
                });
        })
            ->with(['category', 'brand', 'variants', 'images'])
            ->latest()
            ->paginate($perPage);
    }

    //Product Filtering

    public function filterProduct($filters = [], $perPage = 10)
    {
        $query = Product::with(['category', 'brand', 'variants', 'images']);

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }
        // Size filter
        if (isset($filters['size']) && $filters['size'] !== '') {
            $query->whereHas('variants', function ($q) use ($filters) {

                $q->where('size', 'LIKE', $filters['size']);
            });
        }

        // Color filter
        if (isset($filters['color']) && $filters['color'] !== '' && $filters['color'] !== null) {
            $query->whereHas('variants', function ($q) use ($filters) {
                $q->whereRaw('LOWER(color) = LOWER(?)', [$filters['color']]);
            });
        }
        //filter price
        if (isset($filters['min_price']) && isset($filters['max_price'])) {


            $min = (float) $filters['min_price'];
            $max = (float) $filters['max_price'];

            $query->whereBetween('final_price', [$min, $max]);
        } elseif (isset($filters['min_price'])) {
            $query->where('final_price', '>=', (float) $filters['min_price']);
        } elseif (isset($filters['max_price'])) {
            $query->where('final_price', '<=', (float) $filters['max_price']);
        }


        if (!empty($filters['is_featured'])) {
            $query->where('is_featured', true);
        }

        if (!empty($filters['in_stock'])) {
            $query->whereHas('variants', function ($q) {
                $q->where('stock', '>', 0);
            });
        }
        //Sort by
        if (!empty($filters['sort_by'])) {
            switch ($filters['sort_by']) {
                case 'newest':
                    $query->latest('created_at');
                    break;
                case 'oldest':
                    $query->oldest('created_at');
                    break;
                case 'price_low':
                    $query->orderBy('final_price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('final_price', 'desc');
                    break;
                case 'popular':
                    $query->orderBy('sales_count', 'desc');
                    break;
                case 'trending':
                    $query->orderBy('views_count', 'desc');
                    break;
                default:
                    $query->latest('created_at');
            }
        } else {
            $query->latest('created_at');
        }
        return $query->paginate($perPage);
    }

    //Get premium products

    public function getFeaturedProduct($limit = 10)
    {
        return Product::where('is_active', true)
            ->where('is_featured', true)
            ->with('category', 'brand', 'variants', 'images')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    //Get the best-selling

    public function getBestSellers($limit = 10)
    {
        return Product::where('is_active', true)
            ->with('category', 'brand', 'variants', 'images')
            ->orderBy('sales_count', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    //Get similar products

    public function getRelatedProducts(int $productId, int $limit = 6)
    {
        $product = Product::find($productId);

        if (!$product) {
            return collect([]);
        }
        return Product::query()
            ->where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $productId)
            ->with(['category', 'images'])
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}
