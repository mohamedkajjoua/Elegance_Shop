<?php

namespace App\Services\OrderService;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder($user, int $addressId, string $paymentMethod): Order
    {
        return DB::transaction(function () use ($user, $addressId, $paymentMethod) {

            $cart = Cart::with('cartItems.productVariant.product')
                ->where('user_id', $user->id)
                ->firstOrFail();

            if ($cart->cartItems->isEmpty()) {
                throw new \Exception('Cart is empty');
            }

            $subtotal = 0;

            // calcule  subtotal + vérification de  stock
            foreach ($cart->cartItems as $item) {

                if ($item->quantity > $item->productVariant->stock) {
                    throw new \Exception(
                        "ce produit  {$item->productVariant->product->name} est en rupture de stock"
                    );
                }

                $subtotal += $item->quantity * $item->productVariant->price;
            }

            // ecrire order
            $order = Order::create([
                'user_id' => $user->id,
                'addresse_id' => $addressId,
                'subtotal' => $subtotal,
                'total_price' => $subtotal,
                'status' => 'pending',
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentMethod === 'cod' ? 'pending' : 'paid',
            ]);

            // ecrire order items + decrement stock
            foreach ($cart->cartItems as $item) {

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->productVariant->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->productVariant->price,
                    'subtotal' => $item->quantity * $item->productVariant->price,
                ]);

                //  Decrement stock
                $item->productVariant->decrement('stock', $item->quantity);
            }

            // vider le  panier
            $cart->cartItems()->delete();

            return $order->load([
                'orderItems.product',
                'orderItems.productVariant'
            ]);
        });
    }

       //  User (Order History)
    public function getUserOrders($user)
    {
        return Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get([
                'id',
                'total_price',
                'status',
                'payment_status',
                'created_at'
            ]);
    }


     public function getOrderDetails(int $orderId, $user)
    {

        $order = Order::with([
                'shippingAddress',
                'orderItems.productVariant.product'  //
            ])
            ->where('id', $orderId)
            ->where('user_id', $user->id) // check ownership
            ->first();

        if (!$order) {
            return null; // Forbidden
        }

        //  JSON response
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

}
