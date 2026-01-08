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
    protected $ProductService;

    public function __construct(ProductService $ProductService)
    {
        $this->ProductService = $ProductService;
        // Décommenter si vous utilisez l'auth
        // $this->middleware('auth:sanctum'); 
    }

    public function index(): JsonResponse
    {
        $products = $this->ProductService->getAllProducts();
        return response()->json($products);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            // On appelle la méthode create du Services
            $result = $this->ProductService->createProduct($request->validated());
            
            return response()->json($result, 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = $this->ProductService->getProductById($id);
            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
        // return response()->json($product);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $updatedProduct = $this->ProductService->updateProduct($product, $request->validated());
        return response()->json(['message' => 'Produit mis à jour', 'data' => $updatedProduct]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->ProductService->deleteProduct($product);
        return response()->json(['message' => 'Produit supprimé']);
    }
}