<?php
namespace App\Services\admin;

use App\Models\Order;

class AdminOrderService
{
    //  List all orders
    public function listOrders()
    {
        return Order::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    // Order details
    public function getOrderDetails(int $orderId)
    {
        return Order::with([
            'user',
            'orderItems.productVariant.product',
            'shippingAddress'
        ])->findOrFail($orderId);
    }

    //  Update order status
    public function updateStatus(int $orderId, string $status)
    {
        $order = Order::findOrFail($orderId);
        $order->status = $status;
        $order->save();

        return [
            'message' => 'Order status updated successfully',
            'order' => $order
        ];
    }
}
