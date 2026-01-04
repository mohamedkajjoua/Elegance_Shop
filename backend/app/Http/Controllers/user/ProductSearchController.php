<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Services\User\ProductSearchService;
use Illuminate\Http\Request;

class ProductSearchController extends Controller
{
    protected ProductSearchService  $productSearchService;

    public function __construct(ProductSearchService $productSearchService)
    {
        $this->productSearchService = $productSearchService;
    }


    /* 
           Product search
    */
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (!$query || strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Search query must be at least 2 characters',
            ], 400);
        }

        $perPage = $request->input('per_page', 15);
        $products = $this->productSearchService->searchProducts($query, $perPage);

        return response()->json([
            'success' => true,
            'message' => 'Search results retrieved successfully',
            'data' => $products->items(),
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
            ],
        ], 200);
    }

    //filter

    public function filter(Request $request)
    {
        $filters = [
            'category_id' => $request->input('category_id'),
            'brand_id' => $request->input('brand_id'),
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
            'is_featured' => $request->input('is_featured'),
            'in_stock' => $request->input('in_stock'),
            'sort_by' => $request->input('sort_by', 'newest'),
        ];
        $perPage = $request->input('per_page', 15);
        $product = $this->productSearchService->filterProduct($filters, $perPage);

        return response()->json([
            'success' => true,
            'message' => 'Filtered products successfully',
            'data' => $product->items(),
            'pagination' => [
                'total' => $product->total(),
                'per_page' => $product->perPage(),
                'current_page' => $product->currentPage(),
                'last_page' => $product->lastPage()

            ]
        ], 200);
    }

    //Featured Products

    public function featured(Request $request)
    {
        $limit = $request->input('limit', 10);
        $product = $this->productSearchService->getFeaturedProduct($limit);
        return response()->json([
            'success' => true,
            'message' => 'Featured product successfully',
            'data' => $product
        ], 200);
    }

    //best seller

    public function bestSellers(Request $request)
    {
        $limit = $request->input('limit', 10);
        $product = $this->productSearchService->getBestSellers($limit);

        return response()->json([
            'success' => true,
            'messages' => 'Best sellers retrieved successfully',
            'data' => $product
        ], 200);
    }

    //Similar products 
    public function related($id, Request $request)
    {
        $limit = $request->input('limit', 10);
        $product = $this->productSearchService->getRelatedProducts($id, $limit);
        return response()->json([
            'success' => true,
            'message' => 'Related products retrieved successfully'
        ], 200);
    }
}
