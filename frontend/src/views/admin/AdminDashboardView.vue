<script setup>
import { computed, onMounted, ref } from "vue";
import AdminHeader from "@/components/admin/AdminHeader.vue";
import AdminNav from "@/components/admin/AdminNav.vue";
import { useDashboardStatsStore } from "@/stores/admin/DashboardStatsStore";
import { useAuthStore } from "@/stores/auth/auth";
const sidebarOpen = ref(false);

const dashboardStore = useDashboardStatsStore();
const userStore = useAuthStore();

onMounted(() => {
  dashboardStore.fetchDashboardData();
});

const getImageUrl = (path) => {
  if (!path) return "/default-product.png";
  return path.startsWith("http") ? path : `http://127.0.0.1:8000/storage/${path}`;
};
const getInitials = (name) => {
  if (!name) return "??";
  const parts = name.split(" ");
  const initials = parts.map((p) => p.charAt(0).toUpperCase());
  return initials.slice(0, 2).join("");
};

//chart area

const maxRevenue = computed(() => {
  if (dashboardStore.salesChart.length === 0) return 100;
  return Math.max(...dashboardStore.salesChart.map((item) => parseFloat(item.total)));
});

const getBarHeight = (amount) => {
  const percentage = (parseFloat(amount) / maxRevenue.value) * 100;
  return percentage > 0 ? percentage + "%" : "5%";
};

const monthNames = [
  "Jan",
  "Feb",
  "Mar",
  "Apr",
  "May",
  "Jun",
  "Jul",
  "Aug",
  "Sep",
  "Oct",
  "Nov",
  "Dec",
];
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex">
    <!-- Mobile Overlay -->
    <!-- Sidebar -->
    <AdminNav :isOpen="sidebarOpen" @close="sidebarOpen = false" />

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64">
      <!-- Header -->
      <!-- Header -->
      <AdminHeader title="DASHBOARD" @toggle-sidebar="sidebarOpen = true">
        <!-- Search - Hidden on mobile -->
        <div class="relative hidden md:block">
          <input
            type="text"
            placeholder="Search..."
            class="w-32 lg:w-48 pl-9 pr-3 py-2 bg-gray-100 rounded-lg text-sm"
          />
          <i
            class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"
          ></i>
        </div>
      </AdminHeader>

      <main class="p-3 sm:p-4 md:p-6">
        <!-- Welcome Banner -->
        <div
          class="bg-gradient-to-r from-orange-500 to-pink-500 rounded-xl sm:rounded-2xl p-4 sm:p-6 text-white mb-4 sm:mb-6"
        >
          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div>
              <h2 class="text-lg sm:text-xl md:text-2xl font-bold mb-1 sm:mb-2">
                Welcome back, {{ userStore.user.first_name }}! 👋
              </h2>
              <p class="text-white/80 text-sm sm:text-base">
                Here's what's happening with your store today.
              </p>
            </div>
            <!--  <button
              class="px-3 sm:px-4 py-2 bg-white/20 rounded-lg font-medium hover:bg-white/30 transition-colors text-sm whitespace-nowrap"
            >
              View Reports
            </button> -->
          </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
          <!-- Total Revenue -->
          <div class="bg-white rounded-xl p-3 sm:p-4 md:p-5">
            <div class="flex items-center justify-between mb-2 sm:mb-4">
              <div
                class="w-9 h-9 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-green-100 flex items-center justify-center"
              >
                <i
                  class="fa-solid fa-dollar-sign text-green-500 text-sm sm:text-base md:text-xl"
                ></i>
              </div>
              <!--  <span
                class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-green-100 text-green-600 rounded text-[10px] sm:text-xs font-medium"
                >+12.5%</span
              > -->
            </div>
            <div v-if="dashboardStore.isLoading">
              <div class="h-3 bg-slate-100 rounded w-24 sm:w-32"></div>
              <div class="h-2 bg-slate-50 rounded w-16 sm:w-20"></div>
            </div>
            <p v-else class="text-lg sm:text-xl md:text-2xl font-bold text-slate-800">
              ${{ dashboardStore.stats.total_revenue }}
            </p>
            <p class="text-xs sm:text-sm text-slate-500">Total Revenue</p>
          </div>

          <!-- Total Orders -->
          <div class="bg-white rounded-xl p-3 sm:p-4 md:p-5">
            <div class="flex items-center justify-between mb-2 sm:mb-4">
              <div
                class="w-9 h-9 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-blue-100 flex items-center justify-center"
              >
                <i
                  class="fa-solid fa-cart-shopping text-blue-500 text-sm sm:text-base md:text-xl"
                ></i>
              </div>
              <!--  <span
                class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-blue-100 text-blue-600 rounded text-[10px] sm:text-xs font-medium"
                >+8.2%</span
              > -->
            </div>
            <div v-if="dashboardStore.isLoading">
              <div class="h-3 bg-slate-100 rounded w-24 sm:w-32"></div>
              <div class="h-2 bg-slate-50 rounded w-16 sm:w-20"></div>
            </div>
            <p v-else class="text-lg sm:text-xl md:text-2xl font-bold text-slate-800">
              {{ dashboardStore.stats.orders_count }}
            </p>
            <p class="text-xs sm:text-sm text-slate-500">Total Orders</p>
          </div>

          <!-- Total Products -->
          <div class="bg-white rounded-xl p-3 sm:p-4 md:p-5">
            <div class="flex items-center justify-between mb-2 sm:mb-4">
              <div
                class="w-9 h-9 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-purple-100 flex items-center justify-center"
              >
                <i class="fa-solid fa-cube text-purple-500 text-sm sm:text-base md:text-xl"></i>
              </div>
              <!--   <span
                class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-purple-100 text-purple-600 rounded text-[10px] sm:text-xs font-medium"
                >+5.4%</span
              > -->
            </div>
            <div v-if="dashboardStore.isLoading">
              <div class="h-3 bg-slate-100 rounded w-24 sm:w-32"></div>
              <div class="h-2 bg-slate-50 rounded w-16 sm:w-20"></div>
            </div>
            <p v-else class="text-lg sm:text-xl md:text-2xl font-bold text-slate-800">
              {{ dashboardStore.stats.products_count }}
            </p>
            <p class="text-xs sm:text-sm text-slate-500">Total Products</p>
          </div>

          <!-- Total Customers -->
          <div class="bg-white rounded-xl p-3 sm:p-4 md:p-5">
            <div class="flex items-center justify-between mb-2 sm:mb-4">
              <div
                class="w-9 h-9 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-orange-100 flex items-center justify-center"
              >
                <i class="fa-solid fa-users text-orange-500 text-sm sm:text-base md:text-xl"></i>
              </div>
              <!--  <span
                class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-orange-100 text-orange-600 rounded text-[10px] sm:text-xs font-medium"
                >+15.3%</span
              > -->
            </div>
            <div v-if="dashboardStore.isLoading">
              <div class="h-3 bg-slate-100 rounded w-24 sm:w-32"></div>
              <div class="h-2 bg-slate-50 rounded w-16 sm:w-20"></div>
            </div>
            <p v-else class="text-lg sm:text-xl md:text-2xl font-bold text-slate-800">
              {{ dashboardStore.stats.customers_count }}
            </p>
            <p class="text-xs sm:text-sm text-slate-500">Total Customers</p>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
          <!-- Chart Area -->
          <div
            class="lg:col-span-2 bg-white rounded-xl p-4 sm:p-6 shadow-sm border border-slate-50"
          >
            <div class="flex items-center justify-between mb-4 sm:mb-6">
              <h3 class="text-base sm:text-lg font-semibold text-slate-800">Revenue Overview</h3>
              <select
                class="px-2 sm:px-3 py-1.5 sm:py-2 bg-gray-100 rounded-lg text-xs sm:text-sm border-0 focus:ring-2 focus:ring-orange-500"
              >
                <option>This Year</option>
              </select>
            </div>

            <div
              class="h-48 sm:h-56 md:h-64 bg-gradient-to-t from-orange-50/50 to-white rounded-xl flex items-end justify-around px-2 sm:px-4 pb-3 sm:pb-4 overflow-hidden border border-orange-50/50"
            >
              <div
                v-for="data in dashboardStore.salesChart"
                :key="data.month"
                class="group relative w-4 sm:w-6 md:w-8 bg-orange-400 hover:bg-orange-600 rounded-t flex-shrink-0 transition-all duration-500 ease-out"
                :style="{ height: getBarHeight(data.total) }"
              >
                <div
                  class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10"
                >
                  ${{ parseFloat(data.total).toLocaleString() }}
                </div>
              </div>

              <div
                v-if="dashboardStore.salesChart.length === 0"
                class="w-full text-center text-slate-400 text-sm pb-20"
              >
                No sales data available for this period.
              </div>
            </div>

            <div
              class="flex justify-between sm:justify-around mt-2 sm:mt-3 text-[10px] sm:text-xs text-slate-500 gap-1"
            >
              <span
                v-for="(month, index) in monthNames"
                :key="index"
                :class="{ 'hidden sm:inline': index > 5 }"
              >
                {{ month }}
              </span>
            </div>
          </div>

          <!-- Recent Orders -->
          <div class="bg-white rounded-xl p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4 sm:mb-6">
              <h3 class="text-base sm:text-lg font-semibold text-slate-800">Recent Orders</h3>
              <a
                href="admin/orders"
                class="text-orange-500 text-xs sm:text-sm font-medium hover:underline"
                >View All</a
              >
            </div>

            <template v-if="dashboardStore.isLoading">
              <div v-for="i in 5" :key="i" class="flex items-center gap-2 sm:gap-3 animate-pulse">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-slate-100 flex-shrink-0"></div>

                <div class="flex-1 space-y-2">
                  <div class="h-3 bg-slate-100 rounded w-24 sm:w-32"></div>
                  <div class="h-2 bg-slate-50 rounded w-16 sm:w-20"></div>
                </div>

                <div class="w-12 h-5 bg-slate-50 rounded flex-shrink-0"></div>
              </div>
            </template>

            <div v-else class="space-y-3 sm:space-y-4">
              <div
                v-for="order in dashboardStore.recentOrders"
                class="flex items-center gap-2 sm:gap-3"
              >
                <div
                  class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0"
                >
                  <router-link :to="`/admin/orders/${order.id}`">
                    <img
                      v-if="order.customer_avatar"
                      :src="getImageUrl(order.customer_avatar)"
                      class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0"
                      :alt="order.customer_name"
                    />

                    <span v-else class="text-purple-500 font-bold text-xs sm:text-sm">
                      {{ getInitials(order.customer_name) }}
                    </span>
                  </router-link>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="font-medium text-slate-800 text-xs sm:text-sm truncate">
                    {{ order.customer_name }}
                  </p>
                  <p class="text-[10px] sm:text-xs text-slate-500">
                    {{ order.items_count }} items • $ {{ order.total_amount }}
                  </p>
                </div>
                <span
                  v-if="order.status === 'pending'"
                  class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-yellow-100 text-yellow-600 rounded text-[10px] sm:text-xs flex-shrink-0"
                  >{{ order.status }}</span
                >
                <span
                  v-if="order.status === 'processing'"
                  class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-green-100 text-green-600 rounded text-[10px] sm:text-xs flex-shrink-0"
                  >{{ order.status }}</span
                >
                <span
                  v-if="order.status === 'shipped'"
                  class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-blue-100 text-blue-600 rounded text-[10px] sm:text-xs flex-shrink-0"
                  >{{ order.status }}</span
                >
                <span
                  v-if="order.status === 'delivered'"
                  class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-blue-100 text-blue-600 rounded text-[10px] sm:text-xs flex-shrink-0"
                  >{{ order.status }}</span
                >
                <span
                  v-if="order.status === 'cancelled'"
                  class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-red-100 text-blue-600 rounded text-[10px] sm:text-xs flex-shrink-0"
                  >{{ order.status }}</span
                >
              </div>
            </div>
          </div>
        </div>

        <!-- Top Products -->
        <div class="mt-4 sm:mt-6 bg-white rounded-xl p-4 sm:p-6">
          <div class="flex items-center justify-between mb-4 sm:mb-6">
            <h3 class="text-base sm:text-lg font-semibold text-slate-800">Top Selling Products</h3>
            <a
              href="admin/products"
              class="text-orange-500 text-xs sm:text-sm font-medium hover:underline"
              >View All</a
            >
          </div>
          <template v-if="dashboardStore.isLoading">
            <div
              v-for="i in 5"
              :key="i"
              class="flex flex-col items-center p-3 sm:p-4 bg-gray-50 rounded-xl animate-pulse"
            >
              <div
                class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 rounded-lg bg-slate-200 mb-2 sm:mb-3"
              ></div>

              <div class="h-3 bg-slate-200 rounded w-full mb-2"></div>

              <div class="h-2 bg-slate-100 rounded w-12 mb-2"></div>

              <div class="h-4 bg-orange-100 rounded w-16 mt-1"></div>
            </div>
          </template>

          <div v-else class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
            <!-- Product 1 -->
            <div
              v-for="product in dashboardStore.topProducts"
              class="flex flex-col items-center p-3 sm:p-4 bg-gray-50 rounded-xl"
            >
              <router-link :to="`/admin/products/${product.id}`">
                <img
                  :src="getImageUrl(product.image)"
                  class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 rounded-lg object-cover mb-2 sm:mb-3"
                />
                <p
                  class="font-medium text-slate-800 text-xs sm:text-sm text-center truncate w-full"
                >
                  {{ product.name }}
                </p>
                <p class="text-[10px] sm:text-xs text-slate-500">{{ product.sales_count }}sold</p>
                <p class="text-orange-500 font-bold mt-1 text-sm sm:text-base">
                  ${{ product.final_price }}
                </p>
              </router-link>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>
