<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
        // Décommenter si vous utilisez l'auth
        // $this->middleware('auth:sanctum'); 
    }

    public function index(): JsonResponse
    {
        $products = $this->productService->getAllProducts();
        return response()->json($products);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->createProduct($request->validated());
        return response()->json(['message' => 'Produit créé', 'data' => $product], 201);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json($product);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $updatedProduct = $this->productService->updateProduct($product, $request->validated());
        return response()->json(['message' => 'Produit mis à jour', 'data' => $updatedProduct]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->productService->deleteProduct($product);
        return response()->json(['message' => 'Produit supprimé']);
    }
}