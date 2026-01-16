<?php

namespace App\Services\OrderService;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;

class OrderService
{
    /**
     * Create Order + PDF + Email
     */

    public function createOrder($user, int $addressId, string $paymentMethod): Order
    {
        return DB::transaction(function () use ($user, $addressId, $paymentMethod) {
            $cart = Cart::with('cartItems.productVariant.product')
                ->where('user_id', $user->id)
                ->firstOrFail();

            if ($cart->cartItems->isEmpty()) {
                throw new \Exception('Cart is empty');
            }

            $subtotal = $cart->cartItems->sum(function ($item) {
                return $item->quantity * $item->productVariant->price;
            });


            $order = Order::create([
                'user_id' => $user->id,
                'addresse_id' => $addressId,
                'subtotal' => $subtotal,
                'total_price' => $subtotal,
                'status' => 'pending',
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
            ]);

            foreach ($cart->cartItems as $item) {

                if ($item->quantity > $item->productVariant->stock) {
                    throw new \Exception("Product {$item->productVariant->product->name} en rupture");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->productVariant->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->productVariant->price,
                    'subtotal' => $item->quantity * $item->price,
                ]);


                $item->productVariant->decrement('stock', $item->quantity);
            }

            return $order;
        });
    }

    public function completeOrder(Order $order)
    {
        DB::transaction(function () use ($order) {

            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing'
            ]);


            Cart::where('user_id', $order->user_id)->first()?->cartItems()->delete();


            $this->sendInvoice($order);
        });
    }

    protected function sendInvoice(Order $order)
    {

        try {
            $order->load(['user', 'shippingAddress', 'orderItems.productVariant.product']);


            $pdf = Pdf::loadView('emails.invoice_pdf', compact('order'));

            Mail::to($order->user->email)
                ->send(new OrderConfirmationMail($order, $pdf));
        } catch (\Exception $e) {

            \Log::error("Failed to send invoice email for Order #{$order->id}: " . $e->getMessage());
        }
    }

    /**
     * Order History (User)
     */
    public function getUserOrders($user)
    {
        return Order::with(['orderItems.productVariant.product.images'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Order Details
     */
    public function getOrderDetails(int $orderId, $user)
    {
        $order = Order::with([
            'shippingAddress',
            'orderItems.productVariant.product'
        ])
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->first();

        if (!$order) {
            return null;
        }

        return [
            'id' => $order->id,
            'subtotal' => $order->subtotal,
            'total_price' => $order->total_price,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'address' => $order->shippingAddress ? [
                'city' => $order->shippingAddress->city,
                'street' => $order->shippingAddress->street,
            ] : null,
            'items' => $order->orderItems->map(function ($item) {
                return [
                    'product_name' => $item->productVariant->product->name,
                    'variant' => $item->productVariant
                        ? ($item->productVariant->size . ' / ' . $item->productVariant->color)
                        : 'N/A',
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->quantity * $item->price,
                ];
            }),
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
}
