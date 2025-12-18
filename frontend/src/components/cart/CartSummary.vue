<script setup>
import { useCartStore } from '@/stores/cart'

const cartStore = useCartStore()

const emit = defineEmits(['checkout'])

function proceedToCheckout() {
  emit('checkout')
}
</script>

<template>
  <div class="cart-summary">
    <h3 class="summary-header">Order Summary</h3>
    <div class="summary-row">
      <span>Subtotal</span>
      <span>${{ cartStore.subtotal.toFixed(2) }}</span>
    </div>
    <div class="summary-row">
      <span>Shipping</span>
      <span>${{ cartStore.shipping.toFixed(2) }}</span>
    </div>
    <div class="summary-row">
      <span>Tax (5%)</span>
      <span>${{ cartStore.tax.toFixed(2) }}</span>
    </div>
    <div class="summary-row total">
      <span>Total</span>
      <span>${{ cartStore.total.toFixed(2) }}</span>
    </div>
    <button class="btn-checkout" @click="proceedToCheckout">
      Proceed to Checkout <i class="fa-solid fa-arrow-right"></i>
    </button>
  </div>
</template>
