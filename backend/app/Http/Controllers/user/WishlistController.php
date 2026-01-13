<?php

namespace App\Http\Controllers\user;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\User\WishlistService;



class WishlistController extends Controller
{
    protected WishlistService $wishlistService;

    public function __construct(WishlistService $wishlistService)
    {
        $this->wishlistService = $wishlistService;
    }

    public function index()
    {
        $items = $this->wishlistService->list();
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $result = $this->wishlistService->toggle($request->product_id);
        
        return response()->json($result);
    }
    public function destroy($productId)
{
    $this->wishlistService->deleteItem($productId);
    
    return response()->json([
        'success' => true,
        'message' => 'Produit supprimé de la liste'
    ]);
}
}