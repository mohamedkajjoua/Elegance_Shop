<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use  App\Models\Order;
use App\Services\OrderService\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    //  Create a new order
    public function store(StoreOrderRequest $request): JsonResponse
    {

        $order = $this->orderService->createOrder(
            $request->user(),
            $request->addresse_id,
            $request->payment_method
        );

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully',
            'data' => $order,
        ], 201);
    }

    //  Order History
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $orders = $this->orderService->getUserOrders($user);

        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }

    //  Order Details
    public function show(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $order = $this->orderService->getOrderDetails($id, $user);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'order' => $order
        ]);
    }

          //Cancel order

    public function cancel($id): JsonResponse
    {
        $order = Order::findOrFail($id);
       try {
        $this->orderService->cancelOrder($order);

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully',
            
        ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
