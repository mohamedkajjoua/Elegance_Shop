<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cart'
import Breadcrumb from '@/components/layout/Breadcrumb.vue'

const router = useRouter()
const cartStore = useCartStore()

const breadcrumbItems = [
  { label: 'Cart', to: '/cart' },
  { label: 'Checkout', to: '/checkout' },
  { label: 'Payment' }
]

const paymentMethod = ref('card')
const isProcessing = ref(false)
const saveCard = ref(false)

const cardForm = ref({
  number: '',
  name: '',
  expiry: '',
  cvv: ''
})

// Format card number with spaces
const formattedCardNumber = computed(() => {
  return cardForm.value.number.replace(/\s/g, '').replace(/(\d{4})/g, '$1 ').trim()
})

function formatCardNumber(e) {
  let value = e.target.value.replace(/\s/g, '').replace(/\D/g, '')
  if (value.length > 16) value = value.slice(0, 16)
  cardForm.value.number = value.replace(/(\d{4})/g, '$1 ').trim()
}

function formatExpiry(e) {
  let value = e.target.value.replace(/\D/g, '')
  if (value.length > 4) value = value.slice(0, 4)
  if (value.length >= 2) {
    value = value.slice(0, 2) + '/' + value.slice(2)
  }
  cardForm.value.expiry = value
}

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
  }, 2500)
}
</script>

<template>
  <div class="pb-12">
    <Breadcrumb :items="breadcrumbItems" />
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
      <!-- Payment Form -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl p-6 sm:p-8">
          <!-- Progress Steps -->
          <div class="flex items-center justify-center gap-4 mb-8">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-full bg-success text-white flex items-center justify-center text-sm">
                <i class="fa-solid fa-check"></i>
              </div>
              <span class="text-sm font-medium text-success hidden sm:inline">Cart</span>
            </div>
            <div class="w-8 sm:w-16 h-0.5 bg-success"></div>
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-full bg-success text-white flex items-center justify-center text-sm">
                <i class="fa-solid fa-check"></i>
              </div>
              <span class="text-sm font-medium text-success hidden sm:inline">Checkout</span>
            </div>
            <div class="w-8 sm:w-16 h-0.5 bg-primary"></div>
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-sm font-bold">3</div>
              <span class="text-sm font-medium text-primary hidden sm:inline">Payment</span>
            </div>
          </div>

          <h2 class="text-xl sm:text-2xl font-bold mb-6">Select Payment Method</h2>
          
          <!-- Payment Options -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <label 
              :class="[
                'flex flex-col items-center gap-3 p-5 border-2 rounded-2xl cursor-pointer transition-all text-center',
                paymentMethod === 'card' ? 'border-primary bg-primary/5 shadow-md' : 'border-border hover:border-gray-300 hover:shadow-sm'
              ]"
            >
              <input v-model="paymentMethod" type="radio" value="card" class="hidden">
              <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                <i class="fa-solid fa-credit-card text-white text-2xl"></i>
              </div>
              <span class="font-semibold text-sm">Credit Card</span>
              <div class="flex gap-1">
                <i class="fa-brands fa-cc-visa text-blue-600 text-lg"></i>
                <i class="fa-brands fa-cc-mastercard text-red-500 text-lg"></i>
                <i class="fa-brands fa-cc-amex text-blue-400 text-lg"></i>
              </div>
            </label>
            
            <label 
              :class="[
                'flex flex-col items-center gap-3 p-5 border-2 rounded-2xl cursor-pointer transition-all text-center',
                paymentMethod === 'paypal' ? 'border-primary bg-primary/5 shadow-md' : 'border-border hover:border-gray-300 hover:shadow-sm'
              ]"
            >
              <input v-model="paymentMethod" type="radio" value="paypal" class="hidden">
              <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center">
                <i class="fa-brands fa-paypal text-white text-2xl"></i>
              </div>
              <span class="font-semibold text-sm">PayPal</span>
              <span class="text-xs text-text-light">Fast & Secure</span>
            </label>
            
            <label 
              :class="[
                'flex flex-col items-center gap-3 p-5 border-2 rounded-2xl cursor-pointer transition-all text-center',
                paymentMethod === 'apple' ? 'border-primary bg-primary/5 shadow-md' : 'border-border hover:border-gray-300 hover:shadow-sm'
              ]"
            >
              <input v-model="paymentMethod" type="radio" value="apple" class="hidden">
              <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-gray-800 to-black flex items-center justify-center">
                <i class="fa-brands fa-apple text-white text-2xl"></i>
              </div>
              <span class="font-semibold text-sm">Apple Pay</span>
              <span class="text-xs text-text-light">Quick Checkout</span>
            </label>
          </div>

          <!-- Card Form -->
          <div v-if="paymentMethod === 'card'" class="space-y-5">
            <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 rounded-2xl p-6 text-white mb-6">
              <div class="flex justify-between items-start mb-8">
                <i class="fa-solid fa-microchip text-2xl opacity-80"></i>
                <i class="fa-brands fa-cc-visa text-3xl"></i>
              </div>
              <div class="mb-6">
                <p class="text-lg tracking-widest font-mono">
                  {{ cardForm.number || '•••• •••• •••• ••••' }}
                </p>
              </div>
              <div class="flex justify-between items-end">
                <div>
                  <p class="text-xs opacity-70 uppercase">Card Holder</p>
                  <p class="font-medium">{{ cardForm.name || 'YOUR NAME' }}</p>
                </div>
                <div>
                  <p class="text-xs opacity-70 uppercase">Expires</p>
                  <p class="font-medium">{{ cardForm.expiry || 'MM/YY' }}</p>
                </div>
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold mb-2">Card Number</label>
              <div class="relative">
                <input 
                  :value="cardForm.number"
                  @input="formatCardNumber"
                  type="text" 
                  maxlength="19"
                  class="w-full px-4 py-3.5 pl-12 border border-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                  placeholder="1234 5678 9012 3456"
                >
                <i class="fa-solid fa-credit-card absolute left-4 top-1/2 -translate-y-1/2 text-text-light"></i>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-semibold mb-2">Cardholder Name</label>
              <div class="relative">
                <input 
                  v-model="cardForm.name"
                  type="text" 
                  class="w-full px-4 py-3.5 pl-12 border border-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all uppercase"
                  placeholder="John Doe"
                >
                <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-text-light"></i>
              </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold mb-2">Expiry Date</label>
                <div class="relative">
                  <input 
                    :value="cardForm.expiry"
                    @input="formatExpiry"
                    type="text" 
                    maxlength="5"
                    class="w-full px-4 py-3.5 pl-12 border border-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                    placeholder="MM/YY"
                  >
                  <i class="fa-solid fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-text-light"></i>
                </div>
              </div>
              <div>
                <label class="block text-sm font-semibold mb-2">CVV</label>
                <div class="relative">
                  <input 
                    v-model="cardForm.cvv"
                    type="password" 
                    maxlength="4"
                    class="w-full px-4 py-3.5 pl-12 border border-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                    placeholder="•••"
                  >
                  <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-text-light"></i>
                </div>
              </div>
            </div>

            <label class="flex items-center gap-3 cursor-pointer">
              <input v-model="saveCard" type="checkbox" class="w-5 h-5 rounded border-border text-primary focus:ring-primary">
              <span class="text-sm text-text-light">Save this card for future purchases</span>
            </label>
          </div>

          <!-- PayPal -->
          <div v-if="paymentMethod === 'paypal'" class="text-center py-8">
            <div class="w-20 h-20 rounded-2xl bg-blue-100 flex items-center justify-center mx-auto mb-4">
              <i class="fa-brands fa-paypal text-blue-600 text-4xl"></i>
            </div>
            <h3 class="font-bold text-lg mb-2">Pay with PayPal</h3>
            <p class="text-text-light text-sm mb-4">You will be redirected to PayPal to complete your payment securely.</p>
          </div>

          <!-- Apple Pay -->
          <div v-if="paymentMethod === 'apple'" class="text-center py-8">
            <div class="w-20 h-20 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
              <i class="fa-brands fa-apple text-4xl"></i>
            </div>
            <h3 class="font-bold text-lg mb-2">Pay with Apple Pay</h3>
            <p class="text-text-light text-sm mb-4">Use Touch ID or Face ID to complete your payment instantly.</p>
          </div>
        </div>
      </div>

      <!-- Order Summary -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl p-6 sticky top-5">
          <h3 class="font-bold text-lg mb-4">Order Summary</h3>
          
          <!-- Cart Items Preview -->
          <div class="space-y-3 mb-4 max-h-48 overflow-y-auto">
            <div 
              v-for="(item, index) in cartStore.items.slice(0, 3)" 
              :key="index"
              class="flex gap-3"
            >
              <img :src="item.image" class="w-14 h-14 rounded-lg object-cover" :alt="item.name || item.title">
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium truncate">{{ item.name || item.title }}</p>
                <p class="text-xs text-text-light">Qty: {{ item.quantity }}</p>
              </div>
              <span class="text-sm font-semibold">${{ (item.price * item.quantity).toFixed(2) }}</span>
            </div>
            <p v-if="cartStore.items.length > 3" class="text-xs text-text-light text-center">
              +{{ cartStore.items.length - 3 }} more items
            </p>
          </div>

          <div class="border-t border-border pt-4 space-y-3">
            <div class="flex justify-between text-sm">
              <span class="text-text-light">Subtotal</span>
              <span class="font-medium">${{ cartStore.subtotal.toFixed(2) }}</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-text-light">Shipping</span>
              <span class="font-medium">${{ cartStore.shipping.toFixed(2) }}</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-text-light">Tax</span>
              <span class="font-medium">${{ cartStore.tax.toFixed(2) }}</span>
            </div>
            <div class="flex justify-between text-lg font-bold pt-3 border-t border-border">
              <span>Total</span>
              <span class="text-primary">${{ cartStore.total.toFixed(2) }}</span>
            </div>
          </div>

          <button 
            :disabled="isProcessing"
            :class="[
              'w-full py-4 rounded-xl font-bold text-lg transition-all mt-6 flex items-center justify-center gap-2',
              isProcessing 
                ? 'bg-gray-300 text-gray-500 cursor-not-allowed' 
                : 'bg-primary text-white hover:bg-primary-dark shadow-lg shadow-primary/30'
            ]"
            @click="processPayment"
          >
            <template v-if="isProcessing">
              <svg class="animate-spin w-5 h-5" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Processing...
            </template>
            <template v-else>
              <i class="fa-solid fa-lock"></i>
              Pay ${{ cartStore.total.toFixed(2) }}
            </template>
          </button>

          <div class="mt-4 flex items-center justify-center gap-2 text-xs text-text-light">
            <i class="fa-solid fa-shield-halved text-success"></i>
            <span>256-bit SSL Encrypted • Secure Payment</span>
          </div>

          <!-- Payment Partners -->
          <div class="mt-6 pt-4 border-t border-border">
            <p class="text-xs text-text-light text-center mb-3">Secured by</p>
            <div class="flex justify-center gap-4 opacity-50">
              <i class="fa-brands fa-cc-visa text-2xl"></i>
              <i class="fa-brands fa-cc-mastercard text-2xl"></i>
              <i class="fa-brands fa-cc-amex text-2xl"></i>
              <i class="fa-brands fa-cc-stripe text-2xl"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
