<?php
namespace App\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use App\Services\admin\AdminOrderService;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    protected AdminOrderService $orderService;

    public function __construct(AdminOrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    //  List orders
    public function index()
    {
        return response()->json(
            $this->orderService->listOrders()
        );
    }

    //  Order details
    public function show($id)
    {
        return response()->json(
            $this->orderService->getOrderDetails($id)
        );
    }

    //  Update order status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        return response()->json(
            $this->orderService->updateStatus($id, $request->status)
        );
    }
}

