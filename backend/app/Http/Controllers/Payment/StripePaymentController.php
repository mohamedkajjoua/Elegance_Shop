<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Payment\StripePaymentService;
use App\Services\OrderService\OrderService;
use Illuminate\Http\Request;

class StripePaymentController extends Controller
{
    protected StripePaymentService $stripePaymentService;
    protected OrderService $orderService;

    public function __construct(StripePaymentService $stripePaymentService, OrderService $orderService)
    {
        $this->stripePaymentService = $stripePaymentService;
        $this->orderService = $orderService;
    }





    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        $order = Order::findOrFail($request->order_id);

        if ($order->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        if ($order->payment_status === 'paid') {
            return response()->json(['error' => 'Order is already paid'], 400);
        }

        try {
            $clientSecret = $this->stripePaymentService->createPaymentIntent($order);


            return response()->json([
                'clientSecret' => $clientSecret
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function webhook(Request $request)
    {
        $sigHeader = $request->header('Stripe-Signature');
        $payload = $request->getContent();

        try {
            $this->stripePaymentService->handleWebhook($payload, $sigHeader);
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
