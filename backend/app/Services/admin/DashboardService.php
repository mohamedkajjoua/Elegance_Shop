<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardService
{

    public function getDashboardStats()
    {
        return [
            'stats' => [

                'total_revenue'   => Order::where('payment_status', 'paid')->sum('total_price'),
                'orders_count'    => Order::count(),
                'products_count'  => Product::count(),
                'customers_count' => User::where('role', 'customer')->count(),
            ],

            'top_products' => Product::with(['images' => fn($q) => $q->where('is_primary', true)])
                ->orderBy('sales_count', 'desc')
                ->take(5)
                ->get()
                ->map(fn($product) => [
                    'id'          => $product->id,
                    'name'        => $product->name,
                    'sales_count' => $product->sales_count,
                    'final_price' => $product->final_price,
                    'image'       => $product->images->first()?->image_url ?? null,
                ]),

            'recent_orders' => Order::with(['user:id,first_name,last_name,avatar', 'orderItems'])
                ->withCount('orderItems as items_count')
                ->latest()
                ->take(10)
                ->get()
                ->map(fn($order) => [
                    'id'             => $order->id,

                    'customer_name'  => $order->user
                        ? "{$order->user->first_name} {$order->user->last_name}"
                        : 'Guest',
                    'customer_avatar' => $order->user->avatar ?? null,
                    'items_count'    => $order->items_count,
                    'total_amount'   => $order->total_price,
                    'status'         => $order->status,
                    'payment_status' => $order->payment_status,
                    'created_at'     => $order->created_at ? $order->created_at->diffForHumans() : 'No date',
                ]),

            'sales_chart' => $this->getMonthlySalesData()
        ];
    }

    private function getMonthlySalesData()
    {

        return Order::selectRaw('EXTRACT(MONTH FROM created_at) as month, SUM(total_price) as total')
            ->where('payment_status', 'paid')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }
}
