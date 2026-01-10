<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Services\Admin\CategoryService;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    // GET /api/admin/categories
    public function index(): JsonResponse
    {
        $categories = $this->categoryService->getAll();
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    // POST /api/admin/categories
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->createCategory($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Catégorie créée avec succès',
            'data' => $category
        ], 201);
    }

    // GET /api/admin/categories/{id}
    public function show($id): JsonResponse
    {
        $category = $this->categoryService->getCategoryById($id);
        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    // PUT /api/admin/categories/{id}
    public function update(UpdateCategoryRequest $request, $id): JsonResponse
    {
        $category = $this->categoryService->updateCategory($id, $request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Catégorie mise à jour',
            'data' => $category
        ]);
    }

    // DELETE /api/admin/categories/{id}
    public function destroy($id): JsonResponse
    {
        $this->categoryService->deleteCategory($id);
        return response()->json([
            'success' => true,
            'message' => 'Catégorie supprimée'
        ]);
    }
}