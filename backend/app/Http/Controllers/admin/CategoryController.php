<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Services\Admin\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {

        try {

            $category = $this->categoryService->getAll();
            return response()->json([
                'success' => true,
                'message' => 'category showing',
                'data' => $category
            ], 200);
        } catch (\Exception $err) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get categories' . $err,
            ], 500);
        }
    }

    public function store(StoreCategoryRequest $request)
    {

        try {

            $category = $this->categoryService->createCategory($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Create category success',
                'data' => $category
            ], 200);
        } catch (\Exception $err) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to  Create category' . $err,
            ], 500);
        }
    }

    public function update($id, StorecategoryRequest $request)
    {

        try {

            $category = $this->categoryService->updateCategory($id, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'update category success',
                'data' => $category
            ], 200);
        } catch (\Exception $err) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to  update category' . $err,
            ], 500);
        }
    }

    public function show($id)
    {

        try {

            $category = $this->categoryService->getCategoryById($id);
            return response()->json([
                'success' => true,
                'message' => 'showing category success',
                'data' => $category
            ], 200);
        } catch (\Exception $err) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to  showing category' . $err,
            ], 500);
        }
    }


    public function destroy($id)
    {

        try {

            $category = $this->categoryService->deleteCategory($id);
            return response()->json([
                'success' => true,
                'message' => 'Delete category success',
            ], 200);
        } catch (\Exception $err) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to  Delete category' . $err,
            ], 500);
        }
    }
}
