<?php

namespace App\Services\User;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductSearchService
{

    protected $selectFields = [
        'id',
        'name',
        'short_description',
        'price',
        'discount',
        'final_price',
        'category_id',
        'brand_id',
        'is_featured',
        'is_active',
        'sales_count',
        'shipping',
        'created_at'
    ];

    /* Product search
    */
    public function searchProducts(string $query, int $perPage = 12)
    {
        return Product::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('short_description', 'like', "%{$query}%")
                    ->orWhereHas('brand', function ($qBrand) use ($query) {
                        $qBrand->where('name', 'like', "%{$query}%");
                    });
            })

            ->with(['category', 'brand', 'variants', 'images'])

            ->withCount('reviews')
            ->withAvg('reviews', 'rating')

            ->select($this->selectFields)
            ->latest()
            ->paginate($perPage);
    }

    // Product Filtering
    public function filterProduct($filters = [], $perPage = 12)
    {

        $query = Product::with(['category', 'brand', 'variants', 'images'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->select($this->selectFields)
            ->where('is_active', true);

        // 1. Category Filter
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // 2. Brand Filter
        if (!empty($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        // 3. Size Filter
        if (!empty($filters['size'])) {
            $query->whereHas('variants', function ($q) use ($filters) {
                $q->where('size', 'LIKE', $filters['size']);
            });
        }

        // 4. Color Filter
        if (!empty($filters['color'])) {
            $query->whereHas('variants', function ($q) use ($filters) {
                $q->whereRaw('LOWER(color) = LOWER(?)', [$filters['color']]);
            });
        }

        // 5. Price Filter
        if (isset($filters['min_price']) || isset($filters['max_price'])) {
            $min = isset($filters['min_price']) ? (float) $filters['min_price'] : 0;
            $max = isset($filters['max_price']) ? (float) $filters['max_price'] : 999999;
            $query->whereBetween('final_price', [$min, $max]);
        }

        // 6. Featured Filter
        if (!empty($filters['is_featured'])) {
            $query->where('is_featured', true);
        }

        // 7. Stock Filter
        if (!empty($filters['in_stock'])) {
            $query->whereHas('variants', function ($q) {
                $q->where('stock', '>', 0);
            });
        }

        // 8. Sorting
        $this->applySorting($query, $filters['sort_by'] ?? 'newest');

        return $query->paginate($perPage);
    }

    // Get premium products (Featured)
    public function getFeaturedProduct($limit = 10)
    {
        return Product::where('is_active', true)
            ->where('is_featured', true)
            ->with(['category', 'brand', 'variants', 'images'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->select($this->selectFields)
            ->latest()
            ->take($limit) // take أفضل من limit في Eloquent
            ->get();
    }

    // Get the best-selling
    public function getBestSellers($limit = 10)
    {
        return Product::where('is_active', true)
            ->with(['category', 'brand', 'variants', 'images'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->select($this->selectFields)
            ->orderBy('sales_count', 'desc')
            ->take($limit)
            ->get();
    }

    // Get similar products
    public function getRelatedProducts(int $productId, int $limit = 6)
    {
        $product = Product::find($productId);

        if (!$product) {
            return collect([]);
        }

        return Product::where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $productId)
            ->with(['category', 'images'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->select($this->selectFields)
            ->inRandomOrder()
            ->take($limit)
            ->get();
    }

    // Helper function for sorting
    private function applySorting(Builder $query, $sort)
    {
        switch ($sort) {
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
    }
}
