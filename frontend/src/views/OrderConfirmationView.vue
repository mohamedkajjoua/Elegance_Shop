<script setup>
import { computed } from 'vue'

const orderNumber = computed(() => 'ORD-' + Math.random().toString(36).substr(2, 9).toUpperCase())
const estimatedDelivery = computed(() => {
  const date = new Date()
  date.setDate(date.getDate() + 7)
  return date.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })
})
</script>

<template>
  <div class="min-h-[calc(100vh-200px)] flex items-center justify-center">
    <div class="text-center max-w-lg">
      <div class="w-24 h-24 bg-success/20 rounded-full flex items-center justify-center mx-auto mb-6">
        <i class="fa-solid fa-check text-success text-4xl"></i>
      </div>
      
      <h1 class="text-3xl font-bold text-text mb-4">Order Confirmed!</h1>
      <p class="text-text-light mb-8">
        Thank you for your purchase. Your order has been successfully placed and is being processed.
      </p>

      <div class="bg-white rounded-2xl p-6 mb-8 text-left">
        <div class="flex justify-between py-3 border-b border-border">
          <span class="text-text-light">Order Number</span>
          <span class="font-bold">{{ orderNumber }}</span>
        </div>
        <div class="flex justify-between py-3 border-b border-border">
          <span class="text-text-light">Estimated Delivery</span>
          <span class="font-bold">{{ estimatedDelivery }}</span>
        </div>
        <div class="flex justify-between py-3">
          <span class="text-text-light">Payment Status</span>
          <span class="text-success font-bold">Paid</span>
        </div>
      </div>

      <div class="flex gap-4 justify-center">
        <router-link 
          to="/orders"
          class="px-6 py-3 bg-primary text-white rounded-xl font-semibold hover:bg-primary-dark transition-colors"
        >
          View Orders
        </router-link>
        <router-link 
          to="/"
          class="px-6 py-3 border-2 border-border text-text rounded-xl font-semibold hover:border-primary hover:text-primary transition-colors"
        >
          Continue Shopping
        </router-link>
      </div>
    </div>
  </div>
</template>
