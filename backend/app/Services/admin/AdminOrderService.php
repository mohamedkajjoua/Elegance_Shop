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
// cancel Order

public function cancelOrder(Order $order): Order
    {
        if ($order->status === 'delivered') {
            throw new \Exception('Delivered orders cannot be cancelled');
        }

        $order->update([
            'status' => 'cancelled'
        ]);
                // Revert stock in product_variants
        foreach ($order->orderItems as $item) {
            $item->productVariant->increment('stock', $item->quantity);
        }

        return $order;
    }
// refund Order
    public function refundOrder(Order $order): Order
    {
        if ($order->payment_status !== 'paid') {
            throw new \Exception('Order is not paid');
        }

        if ($order->status !== 'cancelled') {
            throw new \Exception('Order must be cancelled first');
        }



        $order->update([
            'payment_status' => 'refunded'
        ]);

        return $order;
    }
// get Orders For Export

     public function getOrdersForExport()
    {
        return Order::with('user')->orderBy('created_at', 'desc')->get();
    }

     // Get order statistics
    public function getOrderStats()
    {
        return [
            'total' => Order::count(),
            'refunded' => Order::where('payment_status', 'refunded')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'delivering' => Order::where('status', 'delivering')->count(),
            'pending' => Order::where('status', 'pending')->count(),
            'paid' => Order::where('payment_status', 'paid')->count(),
            'processing' => Order::where('status', 'processing')->count(),
        ];
    }

}
