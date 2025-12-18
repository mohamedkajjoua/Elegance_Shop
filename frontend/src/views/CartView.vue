<script setup>
import { useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cart'
import CartItem from '@/components/cart/CartItem.vue'
import CartSummary from '@/components/cart/CartSummary.vue'
import Breadcrumb from '@/components/layout/Breadcrumb.vue'

const router = useRouter()
const cartStore = useCartStore()

const breadcrumbItems = [
  { label: 'Shopping Cart' }
]

function handleUpdateQuantity(index, change) {
  cartStore.updateQuantity(index, change)
}

function handleRemoveItem(index) {
  if (confirm('Remove this item from cart?')) {
    cartStore.removeFromCart(index)
  }
}

function handleCheckout() {
  router.push('/checkout')
}
</script>

<template>
  <div class="container" style="max-width: 100%; padding: 0;">
    <Breadcrumb :items="breadcrumbItems" />

    <div class="cart-layout">
      <!-- Cart Items -->
      <div class="cart-items">
        <div class="cart-header">
          <h2>Your Cart</h2>
          <span class="cart-count">{{ cartStore.totalItems }} Items</span>
        </div>

        <template v-if="cartStore.items.length > 0">
          <CartItem 
            v-for="(item, index) in cartStore.items"
            :key="index"
            :item="item"
            :index="index"
            @update-quantity="(change) => handleUpdateQuantity(index, change)"
            @remove="() => handleRemoveItem(index)"
          />
        </template>
        <div v-else class="empty-cart text-center py-12 text-text-light">
          <i class="fa-solid fa-cart-shopping text-5xl mb-4 opacity-30"></i>
          <p class="text-lg">Your cart is empty</p>
          <router-link to="/" class="text-primary hover:underline mt-2 inline-block">
            Continue Shopping
          </router-link>
        </div>
      </div>

      <!-- Summary -->
      <CartSummary @checkout="handleCheckout" />
    </div>
  </div>
</template>
