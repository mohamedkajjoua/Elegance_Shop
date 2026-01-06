<?php

namespace App\Services\CartService;

use App\Models\Cart;
use App\Models\CartItem;

class CartService
{
    /**
     * Récupérer ou créer le panier de l'utilisateur
     */
    public function getUserCart(int $userId): Cart
    {
         $cart = Cart::where('user_id', $userId)
        ->where('expires_at', '>', now())
    ->first();

if (!$cart) {
    $cart = Cart::create([
        'user_id' => $userId,
        'expires_at' => now()->addDays(7)
    ]);
}
return $cart;

    }

    /**
     * Afficher le panier
     */
    public function get(int $userId): Cart
    {
        return $this->getUserCart($userId)
            ->load('cartItems.productVariant');
    }

    /**
     * Ajouter un produit
     */
    public function add(int $userId, int $variantId, int $quantity): Cart
    {
        $cart = $this->getUserCart($userId);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($item) {
            $item->increment('quantity', $quantity);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_variant_id' => $variantId,
                'quantity' => $quantity
            ]);
        }

        return $cart->load('cartItems.productVariant');
    }



    /**
     * Modifier la quantité
     */
    public function update(int $userId, int $itemId, int $quantity): Cart
    {
        $cart = $this->getUserCart($userId);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('id', $itemId)
            ->firstOrFail();

        $item->update(['quantity' => $quantity]);

        return $cart->load('cartItems.productVariant');
    }

    /**
     * Supprimer un produit du panier
     */
    public function remove(int $userId, int $itemId): Cart
    {
        $cart = $this->getUserCart($userId);

        CartItem::where('cart_id', $cart->id)
            ->where('id', $itemId)
            ->delete();

        return $cart->load('cartItems.productVariant');
    }
}
