<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
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

            $category = $this->brandService->getAll();
            return response()->json([
                'success' => true,
                'message' => 'Brand showing',
                'data' => $category
            ], 200);
        } catch (\Exception $err) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get Brand' . $err,
            ], 500);
        }
    }
}
