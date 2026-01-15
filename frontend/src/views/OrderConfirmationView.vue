<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useCartStore } from "@/stores/cart";

const route = useRoute();
const router = useRouter();
const cartStore = useCartStore();

const status = ref("loading"); // loading | success | failed
const orderId = ref(null);

// حساب تاريخ التوصيل (كما هو في كودك)
const estimatedDelivery = computed(() => {
  const date = new Date();
  date.setDate(date.getDate() + 7);
  return date.toLocaleDateString("en-US", {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  });
});

onMounted(() => {
  // 1. قراءة حالة الدفع من الرابط القادم من Stripe
  const { redirect_status } = route.query;

  if (redirect_status === "succeeded") {
    status.value = "success";

    // نجاح! نفرغ السلة
    cartStore.clearCart();

    // نجلب رقم الطلب الحقيقي
    orderId.value = localStorage.getItem("last_order_id");

    // تنظيف
    localStorage.removeItem("last_order_id");
    localStorage.removeItem("address_id");
  } else if (redirect_status === "processing") {
    status.value = "processing";
  } else {
    // أي شيء آخر يعتبر فشل
    status.value = "failed";
  }
});
</script>

<template>
  <div class="min-h-[calc(100vh-200px)] flex items-center justify-center px-4 py-12">
    <div v-if="status === 'loading' || status === 'processing'" class="text-center">
      <div
        class="w-16 h-16 border-4 border-gray-200 border-t-primary rounded-full animate-spin mx-auto mb-6"
      ></div>
      <h2 class="text-xl font-bold text-gray-700">Verifying Payment...</h2>
      <p class="text-gray-500 mt-2">Please wait while we confirm your transaction.</p>
    </div>

    <div v-else-if="status === 'success'" class="text-center max-w-lg w-full animate-fade-in">
      <div
        class="w-20 h-20 sm:w-24 sm:h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6"
      >
        <i class="fa-solid fa-check text-green-600 text-3xl sm:text-4xl"></i>
      </div>

      <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-3 sm:mb-4">Order Confirmed!</h1>
      <p class="text-gray-500 mb-6 sm:mb-8 text-sm sm:text-base">
        Thank you for your purchase. Your order has been successfully placed and is being processed.
      </p>

      <div
        class="bg-white border border-gray-100 rounded-xl sm:rounded-2xl p-4 sm:p-6 mb-6 sm:mb-8 text-left shadow-sm"
      >
        <div class="flex flex-col sm:flex-row justify-between py-3 border-b border-gray-100 gap-1">
          <span class="text-gray-500 text-sm">Order Number</span>
          <span class="font-bold text-sm sm:text-base">#{{ orderId || "NEW-ORDER" }}</span>
        </div>
        <div class="flex flex-col sm:flex-row justify-between py-3 border-b border-gray-100 gap-1">
          <span class="text-gray-500 text-sm">Estimated Delivery</span>
          <span class="font-bold text-sm sm:text-base">{{ estimatedDelivery }}</span>
        </div>
        <div class="flex flex-col sm:flex-row justify-between py-3 gap-1">
          <span class="text-gray-500 text-sm">Payment Status</span>
          <span
            class="text-green-600 font-bold text-sm sm:text-base uppercase flex items-center gap-2"
          >
            Paid <i class="fa-solid fa-circle-check"></i>
          </span>
        </div>
      </div>

      <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
        <router-link
          to="/orders"
          class="w-full sm:w-auto px-5 sm:px-6 py-3 bg-black text-white rounded-xl font-semibold hover:bg-gray-800 transition-colors text-sm sm:text-base"
        >
          View Orders
        </router-link>
        <router-link
          to="/"
          class="w-full sm:w-auto px-5 sm:px-6 py-3 border-2 border-gray-200 text-gray-700 rounded-xl font-semibold hover:border-black hover:text-black transition-colors text-sm sm:text-base"
        >
          Continue Shopping
        </router-link>
      </div>
    </div>

    <div v-else class="text-center max-w-lg w-full animate-fade-in">
      <div
        class="w-20 h-20 sm:w-24 sm:h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6"
      >
        <i class="fa-solid fa-xmark text-red-600 text-3xl sm:text-4xl"></i>
      </div>

      <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-3 sm:mb-4">Payment Failed</h1>
      <p class="text-gray-500 mb-8 text-sm sm:text-base">
        Something went wrong with your transaction. No charges were made.
      </p>

      <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
        <router-link
          to="/payment"
          class="w-full sm:w-auto px-8 py-3 bg-black text-white rounded-xl font-semibold hover:bg-gray-800 transition-colors"
        >
          Try Again
        </router-link>
        <router-link
          to="/cart"
          class="w-full sm:w-auto px-8 py-3 border-2 border-gray-200 text-gray-700 rounded-xl font-semibold hover:border-black transition-colors"
        >
          Return to Cart
        </router-link>
      </div>
    </div>
  </div>
</template>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.5s ease-out forwards;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
