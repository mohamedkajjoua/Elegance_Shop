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
  <div class="min-h-[calc(100vh-200px)] flex items-center justify-center px-4">
    <div class="text-center max-w-lg w-full">
      <div class="w-20 h-20 sm:w-24 sm:h-24 bg-success/20 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
        <i class="fa-solid fa-check text-success text-3xl sm:text-4xl"></i>
      </div>
      
      <h1 class="text-2xl sm:text-3xl font-bold text-text mb-3 sm:mb-4">Order Confirmed!</h1>
      <p class="text-text-light mb-6 sm:mb-8 text-sm sm:text-base">
        Thank you for your purchase. Your order has been successfully placed and is being processed.
      </p>

      <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 mb-6 sm:mb-8 text-left">
        <div class="flex flex-col sm:flex-row justify-between py-3 border-b border-border gap-1">
          <span class="text-text-light text-sm">Order Number</span>
          <span class="font-bold text-sm sm:text-base">{{ orderNumber }}</span>
        </div>
        <div class="flex flex-col sm:flex-row justify-between py-3 border-b border-border gap-1">
          <span class="text-text-light text-sm">Estimated Delivery</span>
          <span class="font-bold text-sm sm:text-base">{{ estimatedDelivery }}</span>
        </div>
        <div class="flex flex-col sm:flex-row justify-between py-3 gap-1">
          <span class="text-text-light text-sm">Payment Status</span>
          <span class="text-success font-bold text-sm sm:text-base">Paid</span>
        </div>
      </div>

      <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
        <router-link 
          to="/orders"
          class="w-full sm:w-auto px-5 sm:px-6 py-3 bg-primary text-white rounded-xl font-semibold hover:bg-primary-dark transition-colors text-sm sm:text-base"
        >
          View Orders
        </router-link>
        <router-link 
          to="/"
          class="w-full sm:w-auto px-5 sm:px-6 py-3 border-2 border-border text-text rounded-xl font-semibold hover:border-primary hover:text-primary transition-colors text-sm sm:text-base"
        >
          Continue Shopping
        </router-link>
      </div>
    </div>
  </div>
</template>
