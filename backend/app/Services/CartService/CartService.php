<?php

namespace App\Services\CartService;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;

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
                'expires_at' => now()->addDays(7),
            ]);
        }

        return $cart;
    }

    /**
     * Afficher le panier avec sous-total et total
     */
    public function get(int $userId): Cart
    {
        $cart = $this->getUserCart($userId)
            ->load('cartItems.productVariant');

        return $this->calculateTotals($cart);
    }

    /**
     * Ajouter un produit au panier avec vérification du stock
     *
     * @param int $userId
     * @param int $variantId
     * @param int $quantity
     * @return Cart
     * @throws \Exception si le stock est insuffisant
     */
    public function add(int $userId, int $variantId, int $quantity): Cart
    {
        if ($quantity <= 0) {
            throw new \Exception("La quantité doit être supérieure à zéro.");
        }

        $variant = ProductVariant::findOrFail($variantId);

        if ($variant->stock < $quantity) {
            throw new \Exception("Quantité demandée non disponible. Stock disponible : {$variant->stock}");
        }

        $cart = $this->getUserCart($userId);

        $item = $cart->cartItems()
            ->where('product_variant_id', $variantId)
            ->first();

        if ($item) {
            if ($item->quantity + $quantity > $variant->stock) {
                throw new \Exception("Impossible d'ajouter cette quantité. Stock maximum : {$variant->stock}");
            }
            $item->increment('quantity', $quantity);
        } else {
            $cart->cartItems()->create([
                'product_variant_id' => $variantId,
                'quantity'           => $quantity,
            ]);
        }

        return $this->calculateTotals($cart->load('cartItems.productVariant'));
    }

    /**
     * Modifier la quantité d'un item dans le panier
     */
    public function update(int $userId, int $itemId, int $quantity): Cart
    {
        if ($quantity <= 0) {
            throw new \Exception("La quantité doit être supérieure à zéro.");
        }

        $cart = $this->getUserCart($userId);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('id', $itemId)
            ->firstOrFail();

        // Vérifier le stock
        if ($item->productVariant->stock < $quantity) {
            throw new \Exception("Quantité demandée non disponible. Stock disponible : {$item->productVariant->stock}");
        }

        $item->update(['quantity' => $quantity]);

        return $this->calculateTotals($cart->load('cartItems.productVariant'));
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

        return $this->calculateTotals($cart->load('cartItems.productVariant'));
    }

    /**
     * Calculer le sous-total et le total du panier
     */
    protected function calculateTotals(Cart $cart): Cart
    {
        $subTotal = $cart->cartItems->sum(function ($item) {
            return $item->quantity * $item->productVariant->price;
        });

        // Pour l'instant, cartTotal = subTotal
        // On peut ajouter taxes, shipping, remises, etc.
        $cart->subTotal = $subTotal;
        $cart->cartTotal = $subTotal;

        return $cart;
    }
}
