<?php
namespace App\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use App\Services\admin\AdminOrderService;
use Illuminate\Http\Request;
use App\Models\Order;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\JsonResponse;

class AdminOrderController extends Controller
{
    protected AdminOrderService $orderService;

    public function __construct(AdminOrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    //  List orders
 public function index(Request $request)
{
    $period = $request->query('period', 'this_month');

    return response()->json(
        $this->orderService->listOrders($period)
    );
}


    // //  Order details
 public function show($orderId)
{
    $order = $this->orderService->getOrderDetails((int) $orderId);
    return response()->json($order);
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




     public function refund(int $orderId): JsonResponse
    {
        $order = Order::findOrFail($orderId);

        try {
            $this->orderService->refundOrder($order);
            return response()->json([
                'success' => true,
                'message' => 'Order refunded successfully',
                'order' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }


    public function exportCsv(Request $request)
    {
          $period = $request->query('period', 'this_month');

         $orders = $this->orderService->getOrdersForExport($period);

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=orders.csv",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            // CSV Header
            fputcsv($file, [
                'Order ID',
                'User Email',
                'Total Price',
                'Status',
                'Payment Method',
                'Payment Status',
                'Created At'
            ]);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->user->email ?? '',
                    $order->total_price,
                    $order->status,
                    $order->payment_method,
                    $order->payment_status,
                    $order->created_at
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

        // Get order statistics
    public function stats()
    {
        $stats = $this->orderService->getOrderStats();
        return response()->json($stats);
    }
}

