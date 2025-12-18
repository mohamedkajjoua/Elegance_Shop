<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cart'
import Breadcrumb from '@/components/layout/Breadcrumb.vue'

const router = useRouter()
const cartStore = useCartStore()

const breadcrumbItems = [{ label: 'Payment' }]

const paymentMethod = ref('card')
const isProcessing = ref(false)

const cardForm = ref({
  number: '',
  name: '',
  expiry: '',
  cvv: ''
})

function processPayment() {
  if (paymentMethod.value === 'card') {
    if (!cardForm.value.number || !cardForm.value.name || !cardForm.value.expiry || !cardForm.value.cvv) {
      alert('Please fill in all card details')
      return
    }
  }
  
  isProcessing.value = true
  
  // Simulate payment processing
  setTimeout(() => {
    cartStore.clearCart()
    router.push('/order-confirmation')
  }, 2000)
}
</script>

<template>
  <div class="pb-12">
    <Breadcrumb :items="breadcrumbItems" />
    
    <div class="max-w-2xl mx-auto">
      <div class="bg-white rounded-2xl p-8">
        <h2 class="text-2xl font-bold mb-6">Payment Method</h2>
        
        <!-- Payment Options -->
        <div class="space-y-4 mb-8">
          <label 
            :class="[
              'flex items-center gap-4 p-4 border-2 rounded-xl cursor-pointer transition-all',
              paymentMethod === 'card' ? 'border-primary bg-primary/5' : 'border-border hover:border-gray-300'
            ]"
          >
            <input v-model="paymentMethod" type="radio" value="card" class="w-5 h-5 text-primary">
            <i class="fa-solid fa-credit-card text-xl text-text-light"></i>
            <span class="font-semibold">Credit / Debit Card</span>
          </label>
          
          <label 
            :class="[
              'flex items-center gap-4 p-4 border-2 rounded-xl cursor-pointer transition-all',
              paymentMethod === 'paypal' ? 'border-primary bg-primary/5' : 'border-border hover:border-gray-300'
            ]"
          >
            <input v-model="paymentMethod" type="radio" value="paypal" class="w-5 h-5 text-primary">
            <i class="fa-brands fa-paypal text-xl text-blue-600"></i>
            <span class="font-semibold">PayPal</span>
          </label>
          
          <label 
            :class="[
              'flex items-center gap-4 p-4 border-2 rounded-xl cursor-pointer transition-all',
              paymentMethod === 'apple' ? 'border-primary bg-primary/5' : 'border-border hover:border-gray-300'
            ]"
          >
            <input v-model="paymentMethod" type="radio" value="apple" class="w-5 h-5 text-primary">
            <i class="fa-brands fa-apple text-xl"></i>
            <span class="font-semibold">Apple Pay</span>
          </label>
        </div>

        <!-- Card Form -->
        <div v-if="paymentMethod === 'card'" class="space-y-4 mb-8">
          <div>
            <label class="block text-sm font-medium mb-2">Card Number</label>
            <input 
              v-model="cardForm.number"
              type="text" 
              class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none"
              placeholder="1234 5678 9012 3456"
            >
          </div>
          
          <div>
            <label class="block text-sm font-medium mb-2">Cardholder Name</label>
            <input 
              v-model="cardForm.name"
              type="text" 
              class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none"
              placeholder="John Doe"
            >
          </div>
          
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-2">Expiry Date</label>
              <input 
                v-model="cardForm.expiry"
                type="text" 
                class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                placeholder="MM/YY"
              >
            </div>
            <div>
              <label class="block text-sm font-medium mb-2">CVV</label>
              <input 
                v-model="cardForm.cvv"
                type="text" 
                class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                placeholder="123"
              >
            </div>
          </div>
        </div>

        <!-- Order Total -->
        <div class="border-t border-border pt-6 mb-6">
          <div class="flex justify-between text-lg font-bold">
            <span>Total to Pay</span>
            <span class="text-primary">${{ cartStore.total.toFixed(2) }}</span>
          </div>
        </div>

        <button 
          :disabled="isProcessing"
          :class="[
            'w-full py-4 rounded-xl font-bold text-lg transition-all',
            isProcessing 
              ? 'bg-gray-400 cursor-not-allowed' 
              : 'bg-primary text-white hover:bg-primary-dark'
          ]"
          @click="processPayment"
        >
          <template v-if="isProcessing">
            <i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Processing...
          </template>
          <template v-else>
            Pay ${{ cartStore.total.toFixed(2) }}
          </template>
        </button>

        <p class="text-center text-sm text-text-light mt-4">
          <i class="fa-solid fa-lock mr-1"></i>
          Your payment is secured with 256-bit SSL encryption
        </p>
      </div>
    </div>
  </div>
</template>
