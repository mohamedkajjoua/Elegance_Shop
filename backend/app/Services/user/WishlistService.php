<?php
namespace App\Services\User;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;


class WishlistService
{
    public function list()
    {
        return Wishlist::with('product.images',    
        'product.variants')
            ->where('user_id', Auth::id())
            ->get();
    }

    public function toggle(int $productId)
    {
        $userId = Auth::id();

        $wishlist = Wishlist::withTrashed()
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            if ($wishlist->trashed()) {
                $wishlist->restore();
                return ['action' => 'added', 'message' => 'Produit ajouté à vos favoris'];
            }
            $wishlist->delete();
            return ['action' => 'removed', 'message' => 'Produit retiré de vos favoris'];
        }

        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        return ['action' => 'added', 'message' => 'Produit ajouté à vos favoris'];
    }

    public function deleteItem(int $productId)
{
    return Wishlist::where('user_id', auth()->id())
        ->where('product_id', $productId)
        ->delete();
}
}