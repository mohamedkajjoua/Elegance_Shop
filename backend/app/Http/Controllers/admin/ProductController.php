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
        $perPage = $request->input('per_page', 15);
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
}
