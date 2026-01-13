<script setup>
import { ref, onMounted } from 'vue'
import Breadcrumb from '@/components/layout/Breadcrumb.vue'
import api from '@/services/api'

const breadcrumbItems = [{ label: 'My Orders' }]
const activeTab = ref('all')
const orders = ref([])

const statusColors = {
  pending: 'bg-primary/10 text-primary',
  processing: 'bg-primary/10 text-primary',
  shipping: 'bg-warning/10 text-warning',
  delivered: 'bg-success/10 text-success',
  cancelled: 'bg-danger/10 text-danger'
}

/**
 * Fetch orders + details
 */
const fetchOrders = async () => {
  try {
    const { data } = await api.get('/orders')

    const mappedOrders = await Promise.all(
      data.orders.map(async (order) => {
        const detailsRes = await api.get(`/orders/${order.id}`)

        return {
          id: `ORD-${order.id}`,
          date: new Date(order.created_at).toLocaleDateString(),
          status: order.status,
          total: Number(order.total_price),
          items: detailsRes.data.order.items.map(item => ({
            title: item.product_name,
            size: item.variant?.split(' / ')[0] || '',
            color: item.variant?.split(' / ')[1] || '',
            image: 'https://via.placeholder.com/150',
            price: Number(item.price)
          }))
        }
      })
    )

    orders.value = mappedOrders
  } catch (error) {
    console.error('Error fetching orders', error)
  }
}



onMounted(fetchOrders)

const filteredOrders = (status) => {
  if (status === 'all') return orders.value
  return orders.value.filter(o => o.status === status)
}
</script>


<template>
  <div class="pb-12">
    <Breadcrumb :items="breadcrumbItems" />

    <div class="bg-white rounded-xl md:rounded-2xl p-4 sm:p-6 md:p-8">
      <h2 class="text-xl md:text-2xl font-bold mb-4 md:mb-6">Order History</h2>

      <!-- Tabs -->
      <div class="flex gap-2 md:gap-4 mb-6 md:mb-8 border-b border-border overflow-x-auto pb-1">
        <button
          v-for="tab in ['all', 'processing', 'shipping', 'delivered']"
          :key="tab"
          :class="[
            'py-2 sm:py-3 px-3 sm:px-4 font-medium text-xs sm:text-sm border-b-2 transition-all capitalize whitespace-nowrap',
            activeTab === tab ? 'border-primary text-primary' : 'border-transparent text-text-light hover:text-text'
          ]"
          @click="activeTab = tab"
        >
          {{ tab === 'all' ? 'All Orders' : tab }}
        </button>
      </div>

      <!-- Orders List -->
      <div class="space-y-4 md:space-y-6">
        <div
          v-for="order in filteredOrders(activeTab)"
          :key="order.id"
          class="border border-border rounded-xl overflow-hidden"
        >
          <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 p-3 sm:p-4 bg-background"
          >
            <div class="flex flex-wrap items-center gap-3 sm:gap-6">
              <div class="min-w-[100px]">
                <p class="text-xs text-text-light">Order Number</p>
                <p class="font-bold text-sm sm:text-base">{{ order.id }}</p>
              </div>
              <div>
                <p class="text-xs text-text-light">Order Date</p>
                <p class="font-medium text-sm sm:text-base">{{ order.date }}</p>
              </div>
            </div>
            <div class="flex items-center gap-3 sm:gap-4 w-full sm:w-auto justify-between sm:justify-end">
              <span :class="['px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium capitalize', statusColors[order.status]]">
                {{ order.status }}
              </span>
              <span class="font-bold text-base sm:text-lg">${{ order.total.toFixed(2) }}</span>
            </div>
          </div>

          <div class="p-3 sm:p-4 space-y-3 sm:space-y-4">
            <div
              v-for="(item, index) in order.items"
              :key="index"
              class="flex items-center gap-3 sm:gap-4"
            >
              <img :src="item.image" class="w-12 h-12 sm:w-16 sm:h-16 rounded-lg object-cover" :alt="item.title">
              <div class="flex-1 min-w-0">
                <p class="font-medium text-sm sm:text-base truncate">{{ item.title }}</p>
                <p class="text-xs sm:text-sm text-text-light">{{ item.size }} / {{ item.color }}</p>
              </div>
              <span class="font-bold text-sm sm:text-base">${{ item.price }}</span>
            </div>
          </div>

          <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 p-3 sm:p-4 border-t border-border">
    

            <button
              v-if="order.status === 'delivered'"
              class="w-full sm:w-auto px-3 sm:px-4 py-2 bg-primary text-white rounded-lg text-xs sm:text-sm font-medium hover:bg-primary-dark transition-colors"
            >
              Buy Again
            </button>
          </div>
        </div>
      </div>

      <div v-if="filteredOrders(activeTab).length === 0" class="text-center py-12 text-text-light">
        <i class="fa-solid fa-box-open text-5xl mb-4 opacity-30"></i>
        <p>No orders found</p>
      </div>
    </div>
  </div>
</template>
