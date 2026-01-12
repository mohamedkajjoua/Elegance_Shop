<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\Brand\StoreBrandRequest;
use App\Services\Admin\BrandService;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    protected BrandService $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function index()
    {

        try {

            $brand = $this->brandService->getAll();
            return response()->json([
                'success' => true,
                'message' => 'Brand showing',
                'data' => $brand
            ], 200);
        } catch (\Exception $err) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get Brand' . $err,
            ], 500);
        }
    }

    public function store(StoreBrandRequest $request)
    {

        try {

            $brand = $this->brandService->createBrand($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Create brand success',
                'data' => $brand
            ], 200);
        } catch (\Exception $err) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to  Create brand' . $err,
            ], 500);
        }
    }

    public function update($id, StoreBrandRequest $request)
    {

        try {

            $brand = $this->brandService->updateBrand($id, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'update brand success',
                'data' => $brand
            ], 200);
        } catch (\Exception $err) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to  update brand' . $err,
            ], 500);
        }
    }

    public function show($id)
    {

        try {

            $brand = $this->brandService->getBrandById($id);
            return response()->json([
                'success' => true,
                'message' => 'showing brand success',
                'data' => $brand
            ], 200);
        } catch (\Exception $err) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to  showing brand' . $err,
            ], 500);
        }
    }


    public function destroy($id)
    {

        try {

            $brand = $this->brandService->deleteBrand($id);
            return response()->json([
                'success' => true,
                'message' => 'Delete brand success',
            ], 200);
        } catch (\Exception $err) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to  Delete brand' . $err,
            ], 500);
        }
    }
}
