<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import AdminHeader from '@/components/admin/AdminHeader.vue'
import AdminNav from '@/components/admin/AdminNav.vue'
import api from '@/services/api'

const route = useRoute()
const sidebarOpen = ref(false)
const order = ref(null)
const loading = ref(true)
const error = ref(null)

// Fetch order details from backend
const fetchOrderDetails = async () => {
  try {
    loading.value = true
    const orderId = route.params.id
    const res = await api.get(`/admin/orders/${orderId}`)
    order.value = res.data
  } catch (err) {
    console.error('Error fetching order details:', err)
    error.value = 'Failed to load order details'
  } finally {
    loading.value = false
  }
}

// Calculate totals
const itemsTotal = computed(() => {
  if (!order.value?.order_items) return 0
  return order.value.order_items.reduce((sum, item) =>
    sum + (parseFloat(item.price) * item.quantity), 0
  )
})

// Format date
const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

// Status classes
const statusClass = (status) => {
  switch (status?.toLowerCase()) {
    case 'pending': return 'bg-yellow-100 text-yellow-600'
    case 'processing':
    case 'packaging': return 'bg-blue-100 text-blue-600'
    case 'shipped': return 'bg-green-100 text-green-600'
    case 'delivered': return 'bg-teal-100 text-teal-600'
    case 'cancelled': return 'bg-red-100 text-red-600'
    default: return 'bg-gray-100 text-gray-600'
  }
}

const paymentClass = (status) => {
  return status === 'paid' || status === 'refunded'
    ? 'bg-green-100 text-green-600'
    : 'bg-red-100 text-red-600'
}

// Format currency
const formatCurrency = (amount) => {
  return parseFloat(amount).toFixed(2)
}


// Refund order
const refundOrder = async () => {
  if (!confirm('Are you sure you want to refund this order?')) return

  try {
    await api.post(`/admin/orders/${order.value.id}/refund`)
    await fetchOrderDetails() // Refresh order data
  } catch (error) {
    console.error('Error refunding order:', error)
    alert(error.response?.data?.message || 'Failed to refund order')
  }
}

// Update order status
const updateOrderStatus = async (status) => {
  if (!confirm(`Change order status to ${status}?`)) return

  try {
    await api.patch(`/admin/orders/${order.value.id}/status`, { status })
    await fetchOrderDetails() // Refresh order data
  } catch (error) {
    console.error('Error updating status:', error)
    alert(error.response?.data?.message || 'Failed to update status')
  }
}


// Initialize
onMounted(() => {
  fetchOrderDetails()
})

// calcule shipping
const calculateShipping = () => {
  if (!order.value?.order_items) return 0

  let totalShipping = 0
  order.value.order_items.forEach(item => {
    if (item.product_variant?.product?.shipping) {

      totalShipping += parseFloat(item.product_variant.product.shipping) * item.quantity
    }
  })
  return totalShipping
}

const totalAmount = computed(() => {
  const subtotal = parseFloat(order.value?.subtotal || 0)
  const shipping = calculateShipping()

  return subtotal + shipping
})



</script>

<template>
  <div class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    <AdminNav :isOpen="sidebarOpen" @close="sidebarOpen = false" />

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64">
      <!-- Header -->
      <AdminHeader title="ORDER DETAIL" @toggle-sidebar="sidebarOpen = true">
        <div class="relative hidden md:block">
          <input type="text" placeholder="Search..." class="w-32 lg:w-48 pl-10 pr-4 py-2 bg-gray-100 rounded-lg text-sm">
          <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
      </AdminHeader>

      <!-- Page Content -->
      <main class="p-4 md:p-6">
        <!-- Loading State -->
        <div v-if="loading" class="text-center py-12">
          <i class="fa-solid fa-spinner fa-spin text-3xl text-orange-500"></i>
          <p class="mt-3 text-gray-600">Loading order details...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-xl p-6 text-center">
          <i class="fa-solid fa-triangle-exclamation text-3xl text-red-500 mb-3"></i>
          <h3 class="text-lg font-semibold text-red-800 mb-2">Error Loading Order</h3>
          <p class="text-red-600 mb-4">{{ error }}</p>
          <button @click="fetchOrderDetails" class="px-4 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200">
            Try Again
          </button>
        </div>

        <!-- Order Content -->
        <div v-else-if="order" class="space-y-6">
          <!-- Breadcrumb -->
          <div class="flex items-center gap-2 text-sm text-slate-500">
            <router-link to="/admin/orders" class="hover:text-orange-500">Orders</router-link>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <span class="text-slate-800 font-medium">#{{ order.id }}</span>
          </div>

          <!-- Back Button & Actions -->
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <router-link to="/admin/orders" class="inline-flex items-center gap-2 text-slate-600 hover:text-orange-500">
              <i class="fa-solid fa-arrow-left"></i>
              <span>Back to Orders</span>
            </router-link>
            <div class="flex flex-wrap gap-2">
              <!-- Status Update Dropdown -->
              <div   class="relative group">
                <button class="px-4 py-2 border border-gray-200 rounded-lg font-medium text-slate-600 hover:bg-gray-50 flex items-center gap-2">
                  <i class="fa-solid fa-pen text-sm"></i>
                  <span>Update Status</span>
                  <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>
                <div class="absolute right-0 mt-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-10 hidden group-hover:block">
                  <button @click="updateOrderStatus('pending')" class="block w-full text-left px-4 py-2 hover:bg-gray-50 text-sm">Pending</button>
                  <button @click="updateOrderStatus('processing')" class="block w-full text-left px-4 py-2 hover:bg-gray-50 text-sm">Processing</button>
                  <button @click="updateOrderStatus('shipped')" class="block w-full text-left px-4 py-2 hover:bg-gray-50 text-sm">Shipped</button>
                  <button @click="updateOrderStatus('delivered')" class="block w-full text-left px-4 py-2 hover:bg-gray-50 text-sm">Delivered</button>
                </div>
              </div>



              <button v-if="order.status === 'cancelled' && order.payment_status === 'paid'"
                @click="refundOrder"
                class="px-4 py-2 border border-orange-200 bg-orange-50 rounded-lg font-medium text-orange-500 hover:bg-orange-100 flex items-center gap-2">
                <i class="fa-solid fa-dollar-sign text-sm"></i>
                <span>Refund</span>
              </button>


            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
            <!-- Left Column - Order Details -->
            <div class="lg:col-span-2 space-y-4">
              <!-- Order Header -->
              <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                  <div>
                    <h2 class="text-xl md:text-2xl font-bold text-slate-800">#{{ order.id }}</h2>
                    <p class="text-sm text-slate-500 mt-1">Placed on {{ formatDate(order.created_at) }}</p>
                    <p class="text-sm text-slate-500">Last updated: {{ formatDate(order.updated_at) }}</p>
                  </div>
                  <div class="flex flex-wrap gap-2">
                    <!-- Payment Status -->
                    <span :class="[
                      'px-3 py-1 rounded-full text-sm font-medium',
                      paymentClass(order.payment_status)
                    ]">
                      {{ order.payment_status }}
                    </span>
                    <!-- Order Status -->
                    <span :class="[
                      'px-3 py-1 rounded-full text-sm font-medium',
                      statusClass(order.status)
                    ]">
                      {{ order.status }}
                    </span>
                    <!-- Priority -->
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">
                      {{ order.payment_method }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- Order Items -->
              <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 md:p-6 border-b border-gray-100">
                  <h3 class="text-lg font-semibold text-slate-800">Order Items ({{ order.order_items?.length || 0 }})</h3>
                </div>

                <div v-if="order.order_items && order.order_items.length > 0" class="divide-y divide-gray-100">
                  <div v-for="item in order.order_items" :key="item.id" class="p-4 md:p-6 flex gap-4">
                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                      <i class="fa-solid fa-box text-2xl text-gray-400"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                      <h4 class="font-medium text-slate-800 truncate">{{ item.product_variant?.product?.name || 'Product' }}</h4>
                      <p class="text-sm text-slate-500">Variant ID: {{ item.product_variant_id }}</p>
                      <div class="flex flex-wrap gap-2 mt-1 text-sm text-slate-500">
                        <span v-if="item.product_variant?.color">Color: {{ item.product_variant.color }}</span>
                        <span v-if="item.product_variant?.size">• Size: {{ item.product_variant.size }}</span>
                      </div>
                    </div>
                    <div class="text-right">
                      <p class="font-medium text-slate-800">${{ formatCurrency(item.subtotal) }}</p>
                      <p class="text-sm text-slate-500">${{ formatCurrency(item.price) }} × {{ item.quantity }}</p>
                    </div>
                  </div>
                </div>
                <div v-else class="p-8 text-center text-gray-500">
                  <i class="fa-solid fa-box-open text-3xl mb-3"></i>
                  <p>No items in this order</p>
                </div>
              </div>

              <!-- Order Summary -->
              <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Order Summary</h3>
                <div class="space-y-3">
                  <div class="flex justify-between">
                    <span class="text-slate-600">Subtotal</span>
                    <span class="text-slate-800 font-medium">{{ formatCurrency(order.subtotal) }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-slate-600">Shipping</span>
                    <span class="text-slate-800 font-medium"> {{ formatCurrency(calculateShipping()) }}</span>
                  </div>
                  <div class="flex justify-between pt-3 border-t border-gray-200 text-lg font-semibold">
                    <span class="text-slate-800">Total</span>
                    <span class="text-orange-500">  ${{ formatCurrency(totalAmount) }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Right Column - Customer & Payment -->
            <div class="space-y-4">
              <!-- Customer Info -->
              <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
                <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4">Customer</h3>
                <div class="flex items-center gap-3 mb-4">
                  <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center">
                    <i class="fa-solid fa-user text-orange-500"></i>
                  </div>
                  <div>
                    <p class="font-medium text-slate-800">{{ order.user?.first_name }} {{ order.user?.last_name }}</p>
                    <p class="text-sm text-slate-500">{{ order.user?.email }}</p>
                  </div>
                </div>
                <div class="space-y-2 text-sm">
                  <div class="flex items-center gap-2 text-slate-600">
                    <i class="fa-solid fa-envelope w-4 text-slate-400"></i>
                    <span>{{ order.user?.email }}</span>
                  </div>
                  <div class="flex items-center gap-2 text-slate-600">
                    <i class="fa-solid fa-phone w-4 text-slate-400"></i>
                    <span>{{ order.user?.phone || 'No phone' }}</span>
                  </div>
                </div>
              </div>

              <!-- Shipping Address -->
              <div v-if="order.shipping_address" class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
                <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4">Shipping Address</h3>
                <div class="text-sm text-slate-600 space-y-1">
                  <p>{{ order.shipping_address.street }}</p>
                  <p>{{ order.shipping_address.city }}, {{ order.shipping_address.country }}</p>
                  <p>Post Code: {{ order.shipping_address.post_code }}</p>
                  <p>Phone: {{ order.shipping_address.phone }}</p>
                </div>
              </div>

              <!-- Payment Info -->
              <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
                <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4">Payment Details</h3>
                <div class="space-y-3 text-sm">
                  <div class="flex justify-between">
                    <span class="text-slate-500">Method</span>
                    <span class="text-slate-800 font-medium">{{ order.payment_method }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-slate-500">Status</span>
                    <span :class="paymentClass(order.payment_status)" class="px-2 py-1 rounded text-xs">
                      {{ order.payment_status }}
                    </span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-slate-500">Transaction</span>
                    <span class="text-slate-800 font-medium">#{{ order.id }}</span>
                  </div>
                </div>
              </div>

              <!-- Order Timeline -->
              <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
                <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4">Order Timeline</h3>
                <div class="space-y-4">
                  <div class="flex gap-3">
                    <div class="flex flex-col items-center">
                      <div class="w-3 h-3 rounded-full bg-green-500"></div>
                      <div class="w-0.5 h-full bg-gray-200 mt-1"></div>
                    </div>
                    <div class="pb-4">
                      <p class="font-medium text-slate-800">Order Created</p>
                      <p class="text-sm text-slate-500">{{ formatDate(order.created_at) }}</p>
                    </div>
                  </div>
                  <div class="flex gap-3">
                    <div class="flex flex-col items-center">
                      <div class="w-3 h-3 rounded-full" :class="{
                        'bg-green-500': order.status === 'cancelled',
                        'bg-blue-500': order.status !== 'cancelled'
                      }"></div>
                      <div class="w-0.5 h-full bg-gray-200 mt-1"></div>
                    </div>
                    <div class="pb-4">
                      <p class="font-medium text-slate-800">Status Updated</p>
                      <p class="text-sm text-slate-500">{{ order.status }} on {{ formatDate(order.updated_at) }}</p>
                    </div>
                  </div>
                  <div class="flex gap-3">
                    <div class="flex flex-col items-center">
                      <div class="w-3 h-3 rounded-full" :class="paymentClass(order.payment_status)"></div>
                    </div>
                    <div>
                      <p class="font-medium text-slate-800">Payment</p>
                      <p class="text-sm text-slate-500">{{ order.payment_status }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<style scoped>
/* Smooth transitions */
button, a {
  transition: all 0.2s ease;
}

/* Hover effect for status dropdown */
.group:hover .group-hover\:block {
  display: block;
}
</style>
