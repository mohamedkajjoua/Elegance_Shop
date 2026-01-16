<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Webhook;

class StripePaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createPaymentIntent(Order $order): string
    {

        $paymentIntent = PaymentIntent::create([
            'amount' => (int) ($order->total_price * 100),
            'currency' => 'usd',
            'automatic_payment_methods' => ['enabled' => true],
            'metadata' => [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
            ],

            'description' => 'Order #' . ($order->order_number ?? $order->id),
        ]);

        return $paymentIntent->client_secret;
    }

    public function handleWebhook(string $payload, string $sigHeader)
    {
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (SignatureVerificationException $e) {
            Log::error("Stripe webhook signature error: " . $e->getMessage());
            throw new \Exception('Invalid Signature');
        } catch (\Exception $e) {
            Log::error("Stripe webhook general error: " . $e->getMessage());
            throw new \Exception('Webhook error');
        }

        if ($event->type === 'payment_intent.succeeded') {
            $this->fulfillOrder($event->data->object);
        }

        return true;
    }

    protected function fulfillOrder($paymentIntent)
    {
        $orderId = $paymentIntent->metadata->order_id ?? null;

        if (!$orderId) {
            Log::error("Stripe Webhook: Order ID not found in metadata.");
            return;
        }


        $order = Order::with('orderItems.product')->find($orderId);

        if (!$order) {
            Log::error("Stripe Webhook: Order #{$orderId} not found in database.");
            return;
        }

        if ($order->payment_status === 'paid') {
            Log::info("Stripe Webhook: Order #{$orderId} is already marked as paid.");
            return;
        }

        try {
            DB::transaction(function () use ($order, $paymentIntent) {
                $orderService = app(\App\Services\OrderService\OrderService::class);
                $orderService->completeOrder($order);


                Log::info("Processing sales count for order #{$order->id}");

                if ($order->orderItems) {
                    foreach ($order->orderItems as $item) {

                        if ($item->product) {
                            $item->product->increment('sales_count', (int)$item->quantity);
                            Log::info("Success: Incremented product #{$item->product_id}");
                        } else {
                            Log::warning("Warning: Product relation is null for OrderItem #{$item->id}");
                        }
                    }
                }

                PaymentTransaction::create([
                    'order_id'         => $order->id,
                    'transaction_id'   => $paymentIntent->id,
                    'payment_method'   => 'stripe',
                    'amount'           => $paymentIntent->amount / 100,
                    'status'           => 'success',
                    'gateway_response' => json_encode($paymentIntent),
                ]);
            });
        } catch (\Exception $e) {

            Log::error("STRIPE WEBHOOK FATAL ERROR: " . $e->getMessage(), [
                'order_id' => $orderId,
                'trace'    => $e->getTraceAsString()
            ]);


            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
