<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CartService\CartService;

class CartController extends Controller
{
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    private function userId(): int
    {
        $user = auth()->user();

        if (!$user) {
            abort(401, 'Unauthenticated');
        }

        return $user->id;
    }

    public function index()
    {
        return response()->json(
            $this->cartService->get($this->userId())
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);

        return response()->json(
            $this->cartService->add(
                $this->userId(),
                $request->product_variant_id,
                $request->quantity
            )
        );
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        return response()->json(
            $this->cartService->update(
                $this->userId(),
                $id,
                $request->quantity
            )
        );
    }

    public function destroy(int $id)
    {
        return response()->json(
            $this->cartService->remove($this->userId(), $id)
        );
    }
}
