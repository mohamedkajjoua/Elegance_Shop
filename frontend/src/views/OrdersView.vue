<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import Breadcrumb from "@/components/layout/Breadcrumb.vue";
import { useOrderStore } from "@/stores/user/OrderStore";
import api from '@/services/api'

const router = useRouter();
const orderStore = useOrderStore();
const breadcrumbItems = [{ label: "My Orders" }];
const activeTab = ref("all");
const orders = ref([]);
const loading = ref(false);

const BASE_URL = "http://127.0.0.1:8000";

const statusColors = {
  pending: "bg-yellow-100 text-yellow-700 border border-yellow-200",
  processing: "bg-blue-100 text-blue-600",
  shipping: "bg-purple-100 text-purple-600",
  delivered: "bg-green-100 text-green-600",
  cancelled: "bg-red-100 text-red-600",
};

const getImageUrl = (img) => {
  if (!img) return "/images/placeholder.jpg";
  if (typeof img === "object" && img.url) return img.url;
  const path = typeof img === "object" ? img.image_url || img.url : img;
  if (!path) return "/images/placeholder.jpg";
  if (path.startsWith("http")) return path;
  return `${BASE_URL}/storage/${path.replace(/^\//, "")}`;
};

const resumePayment = (order) => {
  localStorage.setItem("last_order_id", order.id);
  router.push("/payment");
};

const fetchOrders = async () => {
  loading.value = true;
  try {
    await orderStore.fetchUserOrders();

    orders.value = orderStore.orders.map((order) => {
      const normalizedStatus = order.status.toLowerCase();

      return {
        id: order.id,
        date: new Date(order.created_at).toLocaleDateString("en-US", {
          year: "numeric",
          month: "short",
          day: "numeric",
        }),
        status: normalizedStatus,
        payment_status: order.payment_status,
        total: parseFloat(order.total_price),
        items: order.order_items
          ? order.order_items.map((item) => ({
              title: item.product_variant?.product?.name || "Product",
              image: getImageUrl(item.product_variant?.product?.images?.[0]),
              price: item.price,
              size: item.product_variant?.size,
              color: item.product_variant?.color,
              quantity: item.quantity,
            }))
          : [],
      };
    });
  } catch (error) {
    console.error("Failed to load orders:", error);
  } finally {
    loading.value = false;
  }
};
// Cancel order
const cancelOrder = async (orderId) => {
  const confirmed = window.confirm(
    'Are you sure you want to cancel this order?'
  )

  if (!confirmed) return

try {
  const res = await api.post(`/orders/${orderId}/cancel`)
  alert(res.data.message)
   await fetchOrders()
} catch (error) {
  console.log( error?.response?.data?.message)
}

}
onMounted(fetchOrders);

const filteredOrders = (status) => {
  if (status === "all") return orders.value;
  return orders.value.filter((o) => o.status === status);
};
</script>

<template>
  <div class="pb-12">
    <Breadcrumb :items="breadcrumbItems" />

    <div class="bg-white rounded-xl md:rounded-2xl p-4 sm:p-6 md:p-8">
      <div class="flex justify-between items-center mb-4 md:mb-6">
        <h2 class="text-xl md:text-2xl font-bold">Order History</h2>
        <button @click="fetchOrders" class="text-sm text-primary hover:underline">
          <i class="fa-solid fa-rotate-right mr-1"></i> Refresh
        </button>
      </div>

      <div v-if="loading" class="py-12 text-center text-gray-400">
        <i class="fa-solid fa-spinner fa-spin text-3xl mb-2"></i>
        <p>Loading your orders...</p>
      </div>

      <div v-else>
        <div class="flex gap-2 md:gap-4 mb-6 md:mb-8 border-b border-border overflow-x-auto pb-1">
          <button
            v-for="tab in ['all', 'pending', 'processing', 'shipping', 'delivered']"
            :key="tab"
            :class="[
              'py-2 sm:py-3 px-3 sm:px-4 font-medium text-xs sm:text-sm border-b-2 transition-all capitalize whitespace-nowrap',
              activeTab === tab
                ? 'border-primary text-primary'
                : 'border-transparent text-text-light hover:text-text',
            ]"
            @click="activeTab = tab"
          >
            {{ tab === "all" ? "All Orders" : tab }}
          </button>
        </div>

        <div class="space-y-4 md:space-y-6">
          <div
            v-for="order in filteredOrders(activeTab)"
            :key="order.id"
            class="border border-border rounded-xl overflow-hidden"
          >
            <div
              class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 p-3 sm:p-4 bg-gray-50"
            >
              <div class="flex flex-wrap items-center gap-3 sm:gap-6">
                <div class="min-w-[100px]">
                  <p class="text-xs text-text-light uppercase tracking-wider">Order ID</p>
                  <p class="font-bold text-sm sm:text-base">#{{ order.id }}</p>
                </div>
                <div>
                  <p class="text-xs text-text-light uppercase tracking-wider">Date</p>
                  <p class="font-medium text-sm sm:text-base">{{ order.date }}</p>
                </div>
              </div>
              <div
                class="flex items-center gap-3 sm:gap-4 w-full sm:w-auto justify-between sm:justify-end"
              >
                <span
                  :class="[
                    'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide',
                    statusColors[order.status] || 'bg-gray-100 text-gray-600',
                  ]"
                >
                  {{ order.status }}
                </span>
                <span class="font-bold text-base sm:text-lg text-primary"
                  >${{ order.total.toFixed(2) }}</span
                >
              </div>
            </div>

            <div class="p-3 sm:p-4 space-y-4 bg-white">
              <div
                v-for="(item, index) in order.items"
                :key="index"
                class="flex items-center gap-4 border-b border-gray-100 last:border-0 pb-4 last:pb-0"
              >
                <div
                  class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-lg overflow-hidden shrink-0"
                >
                  <img :src="item.image" class="w-full h-full object-cover" :alt="item.title" />
                </div>

                <div class="flex-1 min-w-0">
                  <p class="font-bold text-sm sm:text-base truncate">{{ item.title }}</p>
                  <p class="text-xs sm:text-sm text-text-light mt-1">
                    <span v-if="item.size" class="mr-2">Size: {{ item.size }}</span>
                    <span v-if="item.color">Color: {{ item.color }}</span>
                  </p>
                  <p class="text-xs text-text-light mt-0.5">Qty: {{ item.quantity }}</p>
                </div>
                <span class="font-bold text-sm sm:text-base">${{ item.price }}</span>
              </div>
            </div>

            <div
              class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 p-3 sm:p-4 border-t border-border bg-gray-50"
            >
              <button
                v-if="order.status === 'pending'"
                @click="resumePayment(order)"
                class="w-full sm:w-auto px-6 py-2 bg-red-600 text-white rounded-lg text-xs sm:text-sm font-bold hover:bg-red-700 transition-colors flex items-center justify-center gap-2"
              >
                <i class="fa-regular fa-credit-card"></i> Pay Now
              </button>
             <button
                    v-if="order.status !== 'delivered' && order.status !== 'cancelled'"
                    @click="cancelOrder(order.id)"
                    class="w-full sm:w-auto px-6 py-2 bg-red-600 text-white rounded-lg text-xs sm:text-sm font-bold hover:bg-red-700 transition-colors flex items-center justify-center gap-2 shadow-md hover:shadow-lg"
                  >
                    <!-- Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>

                    Cancel
                  </button>



              <!--  <button
                v-if="order.status === 'delivered'"
                class="w-full sm:w-auto px-4 py-2 bg-black text-white rounded-lg text-xs sm:text-sm font-bold hover:bg-gray-800 transition-colors"
              >
                Buy Again
              </button> -->
            </div>
          </div>
        </div>

        <div
          v-if="!loading && filteredOrders(activeTab).length === 0"
          class="text-center py-16 border-2 border-dashed border-gray-200 rounded-xl"
        >
          <div
            class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"
          >
            <i class="fa-solid fa-box-open text-2xl text-gray-400"></i>
          </div>
          <h3 class="text-lg font-bold text-gray-800">No orders found</h3>
          <p class="text-gray-500 mb-6">Looks like you haven't placed any orders yet.</p>
          <router-link
            to="/shop"
            class="px-6 py-2 bg-primary text-white rounded-xl font-bold hover:bg-primary-dark transition"
          >
            Start Shopping
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>
