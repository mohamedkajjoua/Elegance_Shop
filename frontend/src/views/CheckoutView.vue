<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cart'
import { useUserStore } from '@/stores/user'
import Breadcrumb from '@/components/layout/Breadcrumb.vue'

const router = useRouter()
const cartStore = useCartStore()
const userStore = useUserStore()

const breadcrumbItems = [{ label: 'Checkout' }]

const shippingForm = ref({
  firstName: '',
  lastName: '',
  email: userStore.profile.email,
  phone: userStore.profile.phone,
  address: '',
  city: '',
  state: '',
  zip: '',
  country: 'United States'
})

const shippingMethod = ref('standard')

const shippingOptions = [
  { id: 'standard', name: 'Standard Shipping', price: 10, days: '5-7 business days' },
  { id: 'express', name: 'Express Shipping', price: 25, days: '2-3 business days' },
  { id: 'overnight', name: 'Overnight Shipping', price: 50, days: '1 business day' }
]

function proceedToPayment() {
  // Validate form
  if (!shippingForm.value.firstName || !shippingForm.value.address || !shippingForm.value.city) {
    alert('Please fill in all required fields')
    return
  }
  
  router.push('/payment')
}
</script>

<template>
  <div class="pb-12">
    <Breadcrumb :items="breadcrumbItems" />
    
    <div class="grid grid-cols-3 gap-8">
      <!-- Checkout Form -->
      <div class="col-span-2">
        <div class="bg-white rounded-2xl p-8 mb-6">
          <h2 class="text-2xl font-bold mb-6">Shipping Information</h2>
          
          <form class="space-y-6">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-2">First Name *</label>
                <input 
                  v-model="shippingForm.firstName"
                  type="text" 
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                  placeholder="John"
                >
              </div>
              <div>
                <label class="block text-sm font-medium mb-2">Last Name *</label>
                <input 
                  v-model="shippingForm.lastName"
                  type="text" 
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                  placeholder="Doe"
                >
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-2">Email *</label>
                <input 
                  v-model="shippingForm.email"
                  type="email" 
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                  placeholder="john@example.com"
                >
              </div>
              <div>
                <label class="block text-sm font-medium mb-2">Phone *</label>
                <input 
                  v-model="shippingForm.phone"
                  type="tel" 
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                  placeholder="+1 234 567 890"
                >
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium mb-2">Address *</label>
              <input 
                v-model="shippingForm.address"
                type="text" 
                class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                placeholder="123 Main Street, Apt 4B"
              >
            </div>

            <div class="grid grid-cols-3 gap-4">
              <div>
                <label class="block text-sm font-medium mb-2">City *</label>
                <input 
                  v-model="shippingForm.city"
                  type="text" 
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                  placeholder="New York"
                >
              </div>
              <div>
                <label class="block text-sm font-medium mb-2">State</label>
                <input 
                  v-model="shippingForm.state"
                  type="text" 
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                  placeholder="NY"
                >
              </div>
              <div>
                <label class="block text-sm font-medium mb-2">ZIP Code *</label>
                <input 
                  v-model="shippingForm.zip"
                  type="text" 
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                  placeholder="10001"
                >
              </div>
            </div>
          </form>
        </div>

        <div class="bg-white rounded-2xl p-8">
          <h2 class="text-2xl font-bold mb-6">Shipping Method</h2>
          
          <div class="space-y-4">
            <label 
              v-for="option in shippingOptions"
              :key="option.id"
              :class="[
                'flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer transition-all',
                shippingMethod === option.id ? 'border-primary bg-primary/5' : 'border-border hover:border-gray-300'
              ]"
            >
              <div class="flex items-center gap-4">
                <input 
                  v-model="shippingMethod"
                  type="radio" 
                  :value="option.id"
                  class="w-5 h-5 text-primary"
                >
                <div>
                  <p class="font-semibold">{{ option.name }}</p>
                  <p class="text-sm text-text-light">{{ option.days }}</p>
                </div>
              </div>
              <span class="font-bold">${{ option.price.toFixed(2) }}</span>
            </label>
          </div>
        </div>
      </div>

      <!-- Order Summary -->
      <div class="col-span-1">
        <div class="bg-white rounded-2xl p-6 sticky top-5">
          <h3 class="text-xl font-bold mb-6">Order Summary</h3>
          
          <div class="space-y-4 mb-6">
            <div 
              v-for="item in cartStore.items"
              :key="item.id"
              class="flex gap-4"
            >
              <img :src="item.image" class="w-16 h-16 rounded-lg object-cover" :alt="item.title">
              <div class="flex-1">
                <p class="font-medium text-sm">{{ item.title }}</p>
                <p class="text-xs text-text-light">{{ item.size }} / {{ item.color }}</p>
                <p class="text-sm font-bold mt-1">${{ item.price }} × {{ item.quantity }}</p>
              </div>
            </div>
          </div>

          <div class="border-t border-border pt-4 space-y-3">
            <div class="flex justify-between text-sm">
              <span class="text-text-light">Subtotal</span>
              <span>${{ cartStore.subtotal.toFixed(2) }}</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-text-light">Shipping</span>
              <span>${{ shippingOptions.find(o => o.id === shippingMethod).price.toFixed(2) }}</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-text-light">Tax</span>
              <span>${{ cartStore.tax.toFixed(2) }}</span>
            </div>
            <div class="flex justify-between font-bold text-lg pt-3 border-t border-border">
              <span>Total</span>
              <span>${{ (cartStore.subtotal + shippingOptions.find(o => o.id === shippingMethod).price + cartStore.tax).toFixed(2) }}</span>
            </div>
          </div>

          <button 
            class="w-full bg-primary text-white py-4 rounded-xl font-bold mt-6 hover:bg-primary-dark transition-colors"
            @click="proceedToPayment"
          >
            Continue to Payment
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
