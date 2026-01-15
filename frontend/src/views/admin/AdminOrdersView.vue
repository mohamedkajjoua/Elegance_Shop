<script setup>
import { ref, onMounted } from 'vue'
import AdminHeader from '@/components/admin/AdminHeader.vue'
import AdminNav from '@/components/admin/AdminNav.vue'
import api from '@/services/api'

const sidebarOpen = ref(false)
const orders = ref([])
const pagination = ref({})
const loading = ref(true)
const currentPage = ref(1)

// Fetch orders from backend
const fetchOrders = async (page = 1) => {
  try {
    loading.value = true
    const res = await api.get(`/admin/orders?page=${page}`)
    orders.value = res.data.data
    console.log(orders.value)
    pagination.value = {
      current_page: res.data.current_page,
      last_page: res.data.last_page,
      total: res.data.total,
      per_page: res.data.per_page

    }
    currentPage.value = page
  } catch (error) {
    console.error('Error fetching orders:', error)
  } finally {
    loading.value = false
  }
}

// Fetch order stats (you need to create this endpoint)
const stats = ref({
  refunded: 0,
  cancelled: 0,
  shipped: 0,
  delivered: 0,
  delivering: 0,
  pending: 0,
  paid: 0,
  processing: 0
})

const fetchStats = async () => {
  try {
    const res = await api.get('/admin/stats')
    stats.value = res.data

  } catch (error) {
    console.error('Error fetching stats:', error)
  }
}

// Update order status
const updateOrderStatus = async (orderId, status) => {
  try {
    await api.put(`/admin/orders/${orderId}/status`, { status })
    await fetchOrders(currentPage.value)
  } catch (error) {
    console.error('Error updating status:', error)
  }
}

// Cancel order
const cancelOrder = async (orderId) => {
    if (!confirm('Are you sure you want to cancel this order?'))return
  try {
    await api.post(`/admin/orders/${orderId}/cancel`)
    await fetchOrders(currentPage.value)
  } catch (error) {
    console.error('Error cancelling order:', error)
  }
}

// Refund order
const refundOrder = async (orderId) => {
    if (!confirm('Are you sure you want to refund this order?')) return
  try {
    await api.post(`/admin/orders/${orderId}/refund`)
    await fetchOrders(currentPage.value)
  } catch (error) {
    console.error('Error refunding order:', error)
  }
}

// Pagination
const goToPage = (page) => {
  if (page >= 1 && page <= pagination.value.last_page) {
    fetchOrders(page)
  }
}

// Format date
const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

// Status classes
const statusClass = (status) => {
  switch (status?.toLowerCase()) {
    case 'pending': return 'border-slate-300 text-slate-600'
    case 'processing':
    case 'packaging': return 'border-blue-300 text-blue-600 bg-blue-50'
    case 'shipped': return 'border-green-300 text-green-600 bg-green-50'
    case 'delivered': return 'border-teal-300 text-teal-600 bg-teal-50'
    case 'cancelled': return 'border-red-300 text-red-600 bg-red-50'
    default: return 'border-gray-300 text-gray-600'
  }
}

const paymentClass = (status) => {
  return status === 'paid' || status === 'refunded'
    ? 'bg-green-100 text-green-600'
    : 'bg-red-100 text-red-600'
}

// Initialize
onMounted(() => {
  fetchOrders()
  fetchStats()
})
</script>
<template>
  <div class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    <AdminNav :isOpen="sidebarOpen" @close="sidebarOpen = false" />

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64">
      <!-- Header -->
      <AdminHeader title="ORDERS LIST" @toggle-sidebar="sidebarOpen = true">
        <div class="relative hidden md:block">
          <input type="text" placeholder="Search..." class="w-32 lg:w-48 pl-10 pr-4 py-2 bg-gray-100 rounded-lg text-sm">
          <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
      </AdminHeader>

      <main class="p-3 md:p-6">
        <!-- Stats Cards - Dynamic from backend -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 md:gap-4 mb-4 md:mb-6">
          <!-- Refunded -->
          <div class="bg-white rounded-lg md:rounded-xl p-2.5 md:p-5">
            <div class="flex items-center gap-2 md:gap-3">
              <div class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-orange-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-dollar-sign text-orange-500 text-sm md:text-lg"></i>
              </div>
              <div class="min-w-0">
                <p class="text-[10px] md:text-sm text-slate-500 truncate">Refunded</p>
                <p class="text-base md:text-2xl font-bold text-slate-800">{{ stats.refunded }}</p>
              </div>
            </div>
          </div>

          <!-- Cancelled -->
          <div class="bg-white rounded-lg md:rounded-xl p-2.5 md:p-5">
            <div class="flex items-center gap-2 md:gap-3">
              <div class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-xmark text-red-500 text-sm md:text-lg"></i>
              </div>
              <div class="min-w-0">
                <p class="text-[10px] md:text-sm text-slate-500 truncate">Cancelled</p>
                <p class="text-base md:text-2xl font-bold text-slate-800">{{ stats.cancelled }}</p>
              </div>
            </div>
          </div>

          <!-- Shipped -->
          <div class="bg-white rounded-lg md:rounded-xl p-2.5 md:p-5">
            <div class="flex items-center gap-2 md:gap-3">
              <div class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-truck text-green-500 text-sm md:text-lg"></i>
              </div>
              <div class="min-w-0">
                <p class="text-[10px] md:text-sm text-slate-500 truncate">Shipped</p>
                <p class="text-base md:text-2xl font-bold text-slate-800">{{ stats.shipped }}</p>
              </div>
            </div>
          </div>

          <!-- Delivered -->
          <div class="bg-white rounded-lg md:rounded-xl p-2.5 md:p-5">
            <div class="flex items-center gap-2 md:gap-3">
              <div class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-teal-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-check text-teal-500 text-sm md:text-lg"></i>
              </div>
              <div class="min-w-0">
                <p class="text-[10px] md:text-sm text-slate-500 truncate">Delivered</p>
                <p class="text-base md:text-2xl font-bold text-slate-800">{{ stats.delivered }}</p>
              </div>
            </div>
          </div>

          <!-- Hidden on mobile, visible on sm+ -->
          <!-- Processing/Delivering -->
          <div class="hidden sm:block bg-white rounded-lg md:rounded-xl p-2.5 md:p-5">
            <div class="flex items-center gap-2 md:gap-3">
              <div class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-box text-blue-500 text-sm md:text-lg"></i>
              </div>
              <div class="min-w-0">
                <p class="text-[10px] md:text-sm text-slate-500 truncate">Processing</p>
                <p class="text-base md:text-2xl font-bold text-slate-800">{{ stats.processing }}</p>
              </div>
            </div>
          </div>

          <!-- Pending -->
          <div class="hidden sm:block bg-white rounded-lg md:rounded-xl p-2.5 md:p-5">
            <div class="flex items-center gap-2 md:gap-3">
              <div class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-clock text-amber-500 text-sm md:text-lg"></i>
              </div>
              <div class="min-w-0">
                <p class="text-[10px] md:text-sm text-slate-500 truncate">Pending</p>
                <p class="text-base md:text-2xl font-bold text-slate-800">{{ stats.pending }}</p>
              </div>
            </div>
          </div>

          <!-- Paid -->
          <div class="hidden sm:block bg-white rounded-lg md:rounded-xl p-2.5 md:p-5">
            <div class="flex items-center gap-2 md:gap-3">
              <div class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-credit-card text-purple-500 text-sm md:text-lg"></i>
              </div>
              <div class="min-w-0">
                <p class="text-[10px] md:text-sm text-slate-500 truncate">Paid</p>
                <p class="text-base md:text-2xl font-bold text-slate-800">{{ stats.paid }}</p>
              </div>
            </div>
          </div>

          <!-- Total Orders -->
          <div class="hidden sm:block bg-white rounded-lg md:rounded-xl p-2.5 md:p-5">
            <div class="flex items-center gap-2 md:gap-3">
              <div class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-list text-indigo-500 text-sm md:text-lg"></i>
              </div>
              <div class="min-w-0">
                <p class="text-[10px] md:text-sm text-slate-500 truncate">Total</p>
                <p class="text-base md:text-2xl font-bold text-slate-800">{{ pagination.total }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="text-center py-8">
          <i class="fa-solid fa-spinner fa-spin text-2xl text-orange-500"></i>
          <p class="mt-2 text-gray-600">Loading orders...</p>
        </div>

        <!-- Orders Content -->
        <div v-else>
          <!-- Header -->
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <h2 class="text-lg md:text-xl font-semibold text-slate-800">
              All Order List ({{ pagination.total }} orders)
            </h2>
            <select class="px-3 md:px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm">
              <option>This Month</option>
              <option>Last Month</option>
            </select>
          </div>

          <!-- Mobile Orders Cards -->
          <div class="md:hidden space-y-3">
            <div v-for="order in orders" :key="order.id" class="bg-white rounded-xl p-4 shadow-sm">
              <div class="flex items-start justify-between mb-3">
                <div>
                  <p class="font-semibold text-slate-800">#{{ order.id }}</p>
                  <p class="text-xs text-slate-400">{{ formatDate(order.created_at) }}</p>
                </div>
                <span :class="paymentClass(order.payment_status)" class="px-2 py-1 rounded text-xs font-medium">
                  {{ order.payment_status }}
                </span>
              </div>
              <div class="space-y-2 text-sm mb-3">
                <div class="flex items-center justify-between">
                  <span class="text-slate-500">Customer</span>
                  <span class="text-orange-500 font-medium">{{ order.user?.first_name }}{{order.user?.last_name}}</span>

                </div>
                <div class="flex items-center justify-between">
                  <span class="text-slate-500">Total</span>
                  <span class="font-bold text-slate-800">${{ order.total_price }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-slate-500">Items</span>
                  <span class="text-slate-600">{{ order.orderItems?.length || 0 }} items</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-slate-500">Status</span>
                  <span :class="statusClass(order.status)" class="px-2 py-1 rounded border text-xs font-medium">
                    {{ order.status }}
                  </span>
                </div>
              </div>
              <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                <router-link :to="`/admin/orders/${order.id}`"
                  class="px-3 py-2 rounded-lg border border-gray-200 text-slate-500 hover:bg-gray-50 flex items-center gap-1 text-sm">
                  <i class="fa-solid fa-eye text-xs"></i>
                  <span>View</span>
                </router-link>
                <button @click="cancelOrder(order.id)"
                  class="px-3 py-2 rounded-lg border border-gray-200 text-red-500 hover:bg-red-50 flex items-center gap-1 text-sm">
                  <i class="fa-solid fa-xmark text-xs"></i>
                  <span>Cancel</span>
                </button>
              </div>
            </div>

            <!-- Mobile Pagination -->
            <div v-if="pagination.last_page > 1" class="bg-white rounded-xl p-4 shadow-sm flex items-center justify-between">
              <p class="text-xs text-slate-500">
                Showing {{ ((currentPage - 1) * pagination.per_page) + 1 }} to
                {{ Math.min(currentPage * pagination.per_page, pagination.total) }} of {{ pagination.total }}
              </p>
              <div class="flex items-center gap-1">
                <button @click="goToPage(currentPage - 1)"
                  :disabled="currentPage === 1"
                  class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100 disabled:opacity-50">
                  <i class="fa-solid fa-chevron-left text-xs"></i>
                </button>
                <button v-for="page in Math.min(3, pagination.last_page)" :key="page"
                  @click="goToPage(page)"
                  :class="{'bg-orange-500 text-white': currentPage === page, 'border-gray-200': currentPage !== page}"
                  class="w-8 h-8 rounded-lg flex items-center justify-center text-sm">
                  {{ page }}
                </button>
                <button @click="goToPage(currentPage + 1)"
                  :disabled="currentPage === pagination.last_page"
                  class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100 disabled:opacity-50">
                  <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Desktop Orders Table -->
          <div class="hidden md:block bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="w-full">
              <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                  <th class="px-4 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Order ID</th>
                  <th class="px-4 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Created at</th>
                  <th class="px-4 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Customer</th>
                  <th class="px-4 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Total</th>
                  <th class="px-4 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Payment Status</th>
                  <th class="px-4 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Order Status</th>
                  <th class="px-4 py-4 text-center text-xs font-semibold text-slate-600 uppercase">Action</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50">
                  <td class="px-4 py-4 text-sm text-slate-600">#{{ order.id }}</td>
                  <td class="px-4 py-4 text-sm text-slate-600">{{ formatDate(order.created_at) }}</td>
                  <td class="px-4 py-4 text-sm text-orange-500 font-medium">{{ order.user?.first_name}}  {{ order.user?.last_name}}</td>
                  <td class="px-4 py-4 text-sm font-medium text-slate-800">${{ order.total_price }}</td>
                  <td class="px-4 py-4">
                    <span :class="paymentClass(order.payment_status)" class="px-3 py-1 rounded text-xs font-medium">
                      {{ order.payment_status }}
                    </span>
                  </td>
                  <td class="px-4 py-4">
                    <span :class="statusClass(order.status)" class="px-3 py-1 rounded border text-xs font-medium">
                      {{ order.status }}
                    </span>
                  </td>
                  <td class="px-4 py-4">
                    <div class="flex items-center justify-center gap-2">
                      <router-link :to="`/admin/orders/${order.id}`"
                        class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">
                        <i class="fa-solid fa-eye text-sm"></i>
                      </router-link>
                      <button @click="cancelOrder(order.id)"
                        class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-red-500 hover:bg-red-50">
                        <i class="fa-solid fa-xmark text-sm"></i>
                      </button>
                      <button @click="refundOrder(order.id)"
                        class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-orange-500 hover:bg-orange-50">
                        <i class="fa-solid fa-dollar-sign text-sm"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>

            <!-- Desktop Pagination -->
            <div v-if="pagination.last_page > 1" class="px-6 py-4 flex items-center justify-between border-t border-gray-100">
              <p class="text-sm text-slate-500">
                Showing {{ ((currentPage - 1) * pagination.per_page) + 1 }} to
                {{ Math.min(currentPage * pagination.per_page, pagination.total) }} of {{ pagination.total }} entries
              </p>
              <div class="flex items-center gap-2">
                <button @click="goToPage(currentPage - 1)"
                  :disabled="currentPage === 1"
                  class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100 disabled:opacity-50">
                  <i class="fa-solid fa-chevron-left text-xs"></i>
                </button>

                <!-- First page -->
                <button @click="goToPage(1)"
                  :class="{'bg-orange-500 text-white': currentPage === 1, 'border-gray-200': currentPage !== 1}"
                  class="w-8 h-8 rounded-lg flex items-center justify-center">
                  1
                </button>

                <!-- Dynamic page numbers -->
                <template v-if="pagination.last_page > 5">
                  <button v-if="currentPage > 3" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500">
                    ...
                  </button>

                  <button v-for="page in getPageRange()" :key="page"
                    @click="goToPage(page)"
                    :class="{'bg-orange-500 text-white': currentPage === page, 'border-gray-200': currentPage !== page}"
                    class="w-8 h-8 rounded-lg flex items-center justify-center">
                    {{ page }}
                  </button>

                  <button v-if="currentPage < pagination.last_page - 2" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500">
                    ...
                  </button>
                </template>

                <!-- Last page button if not shown -->
                <button v-if="pagination.last_page > 1 && currentPage < pagination.last_page - 2"
                  @click="goToPage(pagination.last_page)"
                  :class="{'bg-orange-500 text-white': currentPage === pagination.last_page, 'border-gray-200': currentPage !== pagination.last_page}"
                  class="w-8 h-8 rounded-lg flex items-center justify-center">
                  {{ pagination.last_page }}
                </button>

                <button @click="goToPage(currentPage + 1)"
                  :disabled="currentPage === pagination.last_page"
                  class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100 disabled:opacity-50">
                  <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>
