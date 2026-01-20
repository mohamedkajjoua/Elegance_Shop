<?php

namespace App\Services\User;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Exception;

class ReviewService
{
    public function getReviewsByProduct(int $productId)
    {
        return Review::with('user:id,last_name,first_name,avatar')
            ->where('product_id', (int)$productId)
            ->latest()
            ->get();
    }

    public function upsertReview($data)
    {
        $userId = Auth::id();
        $productId = $data['product_id'];


        $order = Order::where('user_id', $userId)
            ->where('status', 'delivered')
            ->whereHas('products', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->latest()
            ->first();


        if (!$order) {
            throw new Exception("Sorry, you cannot rate this product because you have not yet purchased and received it.");
        }


        $review = Review::withTrashed()
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($review) {
            $review->update([
                'order_id'   => $order->id,
                'rating'     => $data['rating'],
                'comment'    => $data['comment'] ?? null,
                'deleted_at' => null,
            ]);
            if ($review->trashed()) $review->restore();
            return $review;
        }

        return Review::create([
            'user_id'    => $userId,
            'product_id' => $productId,
            'order_id'   => $order->id,
            'rating'     => $data['rating'],
            'comment'    => $data['comment'] ?? null,
        ]);
    }


    public function deleteReview($id)
    {

        $review = Review::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return $review->delete();
    }
}
