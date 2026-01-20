<script setup>
import { ref, onMounted, computed } from "vue";
import { useRouter } from "vue-router";
import { useCartStore } from "@/stores/cart";

import { loadStripe } from "@stripe/stripe-js";
import Breadcrumb from "@/components/layout/Breadcrumb.vue";
import { usePaymentStore } from "@/stores/user/PaymentStore";

const router = useRouter();
const cartStore = useCartStore();
const paymentStore = usePaymentStore();

const STRIPE_KEY =
  "pk_test_51Sp4JHIyutujgUyJUS9cTrhyoRthvMpHDUzOjEHgZJiSOdh9q18c1IhpAPC5tKZBEN0hzRkiq500QuijtOLDXQfe00jhmWFaHs";

const breadcrumbItems = [
  { label: "Cart", to: "/cart" },
  { label: "Checkout", to: "/checkout" },
  { label: "Payment" },
];

const paymentMethod = ref("card");
const saveCard = ref(false);
const cardHolderName = ref("");

const stripe = ref(null);
const elements = ref(null);

const stripeAppearance = {
  theme: "stripe",
  variables: {
    colorPrimary: "#4f46e5",
    colorBackground: "#ffffff",
    colorText: "#1f2937",
    colorDanger: "#ef4444",
    fontFamily: "ui-sans-serif, system-ui, sans-serif",
    spacingUnit: "4px",
    borderRadius: "12px",
  },
  rules: {
    ".Input": {
      border: "1px solid #e5e7eb",
      paddingTop: "12px",
      paddingBottom: "12px",
      boxShadow: "none",
    },
    ".Input:focus": {
      border: "1px solid #4f46e5",
      boxShadow: "0 0 0 1px #4f46e5",
    },
  },
};

onMounted(async () => {
  const orderId = localStorage.getItem("last_order_id");

  if (!orderId) {
    console.warn("No Order ID found for Stripe demo");
  }

  stripe.value = await loadStripe(STRIPE_KEY);

  if (orderId) {
    await paymentStore.initializePayment(orderId);

    if (paymentStore.clientSecret) {
      elements.value = stripe.value.elements({
        clientSecret: paymentStore.clientSecret,
        appearance: stripeAppearance,
      });

      const paymentElement = elements.value.create("payment", {
        layout: "tabs",
      });

      paymentElement.mount("#payment-element");
    }
  }
});
const getImageUrl = (item) => {
  const images = item.product_variant?.product?.images;
  if (images && images.length > 0) {
    return `http://127.0.0.1:8000/storage/${images[0].image_url}`;
  }
  return "/placeholder-image.jpg";
};

const handlePayment = async () => {
  if (paymentMethod.value === "card") {
    await paymentStore.confirmPayment(stripe.value, elements.value, cardHolderName.value);

    if (paymentStore.paymentStatus === "success") {
      cartStore.clearCart();
    }
  } else {
    alert("Please select Credit Card for this demo.");
  }
};
</script>

<template>
  <div class="pb-12">
    <Breadcrumb :items="breadcrumbItems" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
      <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-100">
          <div class="flex items-center justify-center gap-4 mb-8">
            <div class="flex items-center gap-2">
              <div
                class="w-8 h-8 rounded-full bg-success text-white flex items-center justify-center text-sm"
              >
                <i class="fa-solid fa-check"></i>
              </div>
              <span class="text-sm font-medium text-success hidden sm:inline">Cart</span>
            </div>
            <div class="w-8 sm:w-16 h-0.5 bg-success"></div>
            <div class="flex items-center gap-2">
              <div
                class="w-8 h-8 rounded-full bg-success text-white flex items-center justify-center text-sm"
              >
                <i class="fa-solid fa-check"></i>
              </div>
              <span class="text-sm font-medium text-success hidden sm:inline">Checkout</span>
            </div>
            <div class="w-8 sm:w-16 h-0.5 bg-primary"></div>
            <div class="flex items-center gap-2">
              <div
                class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-sm font-bold"
              >
                3
              </div>
              <span class="text-sm font-medium text-primary hidden sm:inline">Payment</span>
            </div>
          </div>

          <h2 class="text-xl sm:text-2xl font-bold mb-6">Select Payment Method</h2>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <label
              :class="[
                'flex flex-col items-center gap-3 p-5 border-2 rounded-2xl cursor-pointer transition-all text-center',
                paymentMethod === 'card'
                  ? 'border-primary bg-primary/5 shadow-md'
                  : 'border-border hover:border-gray-300 hover:shadow-sm',
              ]"
            >
              <input v-model="paymentMethod" type="radio" value="card" class="hidden" />
              <div
                class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center"
              >
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
                paymentMethod === 'paypal'
                  ? 'border-primary bg-primary/5 shadow-md'
                  : 'border-border hover:border-gray-300 hover:shadow-sm',
              ]"
            >
              <input v-model="paymentMethod" type="radio" value="paypal" class="hidden" />
              <div
                class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center"
              >
                <i class="fa-brands fa-paypal text-white text-2xl"></i>
              </div>
              <span class="font-semibold text-sm">PayPal</span>
              <span class="text-xs text-text-light">Fast & Secure</span>
            </label>

            <label
              :class="[
                'flex flex-col items-center gap-3 p-5 border-2 rounded-2xl cursor-pointer transition-all text-center',
                paymentMethod === 'apple'
                  ? 'border-primary bg-primary/5 shadow-md'
                  : 'border-border hover:border-gray-300 hover:shadow-sm',
              ]"
            >
              <input v-model="paymentMethod" type="radio" value="apple" class="hidden" />
              <div
                class="w-14 h-14 rounded-xl bg-gradient-to-br from-gray-800 to-black flex items-center justify-center"
              >
                <i class="fa-brands fa-apple text-white text-2xl"></i>
              </div>
              <span class="font-semibold text-sm">Apple Pay</span>
              <span class="text-xs text-text-light">Quick Checkout</span>
            </label>
          </div>

          <div v-show="paymentMethod === 'card'" class="space-y-6">
            <div
              v-if="paymentStore.error"
              class="p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl flex items-center gap-3"
            >
              <i class="fa-solid fa-circle-exclamation text-xl"></i>
              <span class="text-sm font-medium">{{ paymentStore.error }}</span>
            </div>

            <div>
              <label class="block text-sm font-semibold mb-2">Cardholder Name</label>
              <div class="relative">
                <input
                  v-model="cardHolderName"
                  type="text"
                  class="w-full px-4 py-3.5 pl-12 border border-border rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all uppercase"
                  placeholder="JOHN DOE"
                />
                <i
                  class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-text-light"
                ></i>
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold mb-2">Card Details</label>
              <div class="relative min-h-[150px]">
                <div
                  v-if="paymentStore.loading && !elements"
                  class="absolute inset-0 flex items-center justify-center bg-gray-50 rounded-xl z-10"
                >
                  <i class="fa-solid fa-circle-notch fa-spin text-gray-400 text-2xl"></i>
                </div>

                <div id="payment-element"></div>
              </div>
            </div>

            <label class="flex items-center gap-3 cursor-pointer">
              <input
                v-model="saveCard"
                type="checkbox"
                class="w-5 h-5 rounded border-border text-primary focus:ring-primary"
              />
              <span class="text-sm text-text-light">Save this card for future purchases</span>
            </label>
          </div>

          <div v-if="paymentMethod === 'paypal'" class="text-center py-8">
            <div
              class="w-20 h-20 rounded-2xl bg-blue-100 flex items-center justify-center mx-auto mb-4"
            >
              <i class="fa-brands fa-paypal text-blue-600 text-4xl"></i>
            </div>
            <h3 class="font-bold text-lg mb-2">Pay with PayPal</h3>
            <p class="text-text-light text-sm mb-4">
              You will be redirected to PayPal to complete your payment securely.
            </p>
          </div>

          <div v-if="paymentMethod === 'apple'" class="text-center py-8">
            <div
              class="w-20 h-20 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4"
            >
              <i class="fa-brands fa-apple text-4xl"></i>
            </div>
            <h3 class="font-bold text-lg mb-2">Pay with Apple Pay</h3>
            <p class="text-text-light text-sm mb-4">
              Use Touch ID or Face ID to complete your payment instantly.
            </p>
          </div>
        </div>
      </div>

      <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl p-6 sticky top-5 shadow-sm border border-gray-100">
          <h3 class="font-bold text-lg mb-4">Order Summary</h3>
          <div></div>

          <div class="space-y-3 mb-4 max-h-48 overflow-y-auto">
            <div
              v-for="(item, index) in cartStore.items.slice(0, 3)"
              :key="item.id"
              class="flex gap-3"
            >
              <img
                :src="getImageUrl(item)"
                class="w-14 h-14 rounded-lg object-cover border border-gray-100"
                :alt="item.product_variant?.product?.name"
              />

              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium truncate text-gray-800">
                  {{ item.product_variant?.product?.name }}
                </p>

                <p class="text-xs text-gray-500">
                  {{ item.product_variant?.color }} / {{ item.product_variant?.size }}
                </p>

                <p class="text-xs text-text-light mt-1">Qty: {{ item.quantity }}</p>
              </div>

              <span class="text-sm font-semibold text-primary">
                ${{ (Number(item.product_variant?.price) * item.quantity).toFixed(2) }}
              </span>
            </div>

            <p v-if="cartStore.items.length > 3" class="text-xs text-text-light text-center mt-2">
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
            <div class="flex justify-between text-lg font-bold pt-3 border-t border-border">
              <span>Total</span>
              <span class="text-primary">${{ cartStore.total.toFixed(2) }}</span>
            </div>
          </div>

          <button
            :disabled="paymentStore.loading || !stripe"
            :class="[
              'w-full py-4 rounded-xl font-bold text-lg transition-all mt-6 flex items-center justify-center gap-2',
              paymentStore.loading || !stripe
                ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                : 'bg-primary text-white hover:bg-primary-dark shadow-lg shadow-primary/30',
            ]"
            @click="handlePayment"
          >
            <template v-if="paymentStore.loading">
              <i class="fa-solid fa-circle-notch fa-spin"></i>
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
