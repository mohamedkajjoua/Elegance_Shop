<script setup>
import { useCartStore } from "@/stores/cart";

const cartStore = useCartStore();
const emit = defineEmits(["checkout"]);

function proceedToCheckout() {
  emit("checkout");
}
</script>

<template>
  <div class="cart-summary p-4 bg-gray-50 rounded shadow">
    <h3 class="summary-header text-xl font-bold mb-4">Order Summary</h3>

    <div class="summary-row flex justify-between mb-2">
      <span>Subtotal</span>
      <span>${{ cartStore.subtotal.toFixed(2) }}</span>
    </div>

    <div class="summary-row flex justify-between mb-2">
      <span>Shipping</span>
      <span>${{ cartStore.shipping.toFixed(2) }}</span>
    </div>

    <div class="summary-row flex justify-between mb-2">
      <span>Tax (5%)</span>
      <span>${{ cartStore.tax.toFixed(2) }}</span>
    </div>

    <div class="summary-row total flex justify-between mt-4 pt-4 border-t font-bold text-lg">
      <span>Total</span>
      <span>${{ cartStore.total.toFixed(2) }}</span>
    </div>

    <button
      class="btn-checkout w-full mt-4 bg-black text-white py-2 rounded hover:bg-gray-800 transition"
      @click="proceedToCheckout"
    >
      Proceed to Checkout <i class="fa-solid fa-arrow-right ml-2"></i>
    </button>
  </div>
</template>
