import { defineStore } from "pinia";
import api from "@/services/api";

export const useCartStore = defineStore("cart", {
  state: () => ({
    items: [],
    subtotal: 0,
    total: 0,
    tax: 0,
    shipping: 0,
  }),

  getters: {
    totalItems(state) {
      return state.items.reduce((sum, item) => sum + item.quantity, 0);
    },
  },

  actions: {
    // Mettre à jour les totaux
    updateTotals(data) {
      // 1. جلب العناصر (مع دعم snake_case)
      const cartItems = data?.cart_items ?? data?.cartItems ?? [];

      // 2. ✅ إصلاح هام: حساب المجموع يدوياً لأن الـ API لا يرسله
      const subTotal = cartItems.reduce((sum, item) => {
        // حماية: تحويل السعر لرقم والتأكد من وجوده
        const price = parseFloat(item.product_variant?.price || 0);
        return sum + price * item.quantity;
      }, 0);

      // 3. حساب باقي القيم بناءً على المجموع المحسوب
      const tax = subTotal * 0.05;
      const shipping = cartItems.length > 0 ? 10 : 0;
      const total = subTotal + tax + shipping;

      // 4. تحديث الـ State
      this.items = cartItems;
      this.subtotal = subTotal;
      this.tax = tax;
      this.shipping = shipping;
      this.total = total;
    },

    // Récupérer le panier depuis l'API
    async fetchCart() {
      try {
        const { data } = await api.get("/cart");
        this.updateTotals(data);
      } catch (error) {
        console.error("Erreur fetchCart :", error.response?.data || error);
      }
    },

    // Ajouter un produit au panier
    async addToCart(productVariantId, quantity = 1) {
      try {
        const { data } = await api.post("/cart", {
          product_variant_id: productVariantId,
          quantity,
        });
        this.updateTotals(data);
      } catch (error) {
        console.error("Erreur addToCart :", error.response?.data || error);
      }
    },

    // Mettre à jour la quantité d'un produit
    async updateItem(cartItemId, quantity) {
      try {
        const { data } = await api.put(`/cart/${cartItemId}`, { quantity });
        this.updateTotals(data);
      } catch (error) {
        console.error("Erreur updateItem :", error.response?.data || error);
      }
    },

    // Supprimer un produit du panier
    async removeItem(cartItemId) {
      try {
        const { data } = await api.delete(`/cart/${cartItemId}`);
        this.updateTotals(data);
      } catch (error) {
        console.error("Erreur removeItem :", error.response?.data || error);
      }
    },
  },
});
