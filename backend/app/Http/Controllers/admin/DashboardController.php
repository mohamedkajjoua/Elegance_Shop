<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {

        try {
            $data = $this->dashboardService->getDashboardStats();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $err) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching dashboard data',
                'error' => $err->getMessage()
            ], 500);
        }
    }
}
