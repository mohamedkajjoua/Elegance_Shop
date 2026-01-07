<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\product\StoreProductRequest;
use App\Http\Requests\admin\product\UpdateProductRequest;
use App\Services\Admin\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductService $productServices;

    public function __construct(ProductService $productService)
    {
        $this->productServices = $productService;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 9);
        $products = $this->productServices->getAllProducts($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully',
            'data' => $products->items(),
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
            ],
        ], 200);
    }



    public function store(StoreProductRequest $request)
    {

        try {
            $validated = $request->validated();
            $product = $this->productServices->createProduct($validated);

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $this->productServices->formatProductResponse($product),
            ], 201);
        } catch (\Exception $err) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to create product :' . $err->getMessage()

            ], 500);
        }
    }

    public function show(int $id)
    {
        $product = $this->productServices->getProductById($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product retrieved successfully',
            'data' => $this->productServices->formatProductResponse($product),
        ], 200);
    }

    public function update($id, UpdateProductRequest $request)
    {
        try {
            $product = $this->productServices->updateProduct($id, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Product updated  successfully',
                'data' => $this->productServices->formatProductResponse($product)
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {

            return response()->json([
                'success' => false,
                'message' => 'Product not found',

            ], 404);
        } catch (\Exception $err) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Product :' . $err->getMessage()

            ], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->productServices->deleteProduct($id);

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product: ' . $e->getMessage(),
            ], 500);
        }
    }

    //Activate/deactivate product
    public function toggleStatus(int $id)
    {
        try {
            $product = $this->productServices->toggleProductStatus($id);

            return response()->json([
                'success' => true,
                'message' => 'Product status updated successfully',
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'is_active' => $product->is_active,
                ],
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }
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
        $products = $this->productServices->searchProducts($query, $perPage);

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
        $product = $this->productServices->filterProduct($filters, $perPage);

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
        $product = $this->productServices->getFeaturedProduct($limit);
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
        $product = $this->productServices->getBestSellers($limit);

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
        $product = $this->productServices->getRelatedProducts($id, $limit);
        return response()->json([
            'success' => true,
            'message' => 'Related products retrieved successfully'
        ], 200);
    }
}
