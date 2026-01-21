<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Services\User\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    protected ReviewService $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function index($productId)
    {
        $reviews = $this->reviewService->getReviewsByProduct($productId);
        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|in:1,2,3,4,5',
            'comment'    => 'nullable|string|max:1000',
        ]);

        try {
            $review = $this->reviewService->upsertReview($validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Review submitted successfully',
                'data' => $review
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 403);
        }
    }
    public function destroy($id)
    {
        try {
            $this->reviewService->deleteReview($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Review deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized or review not found'
            ], 403);
        }
    }

    public function getAll()
    {
        $reviews = $this->reviewService->getHomeReviews();
        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }
}
