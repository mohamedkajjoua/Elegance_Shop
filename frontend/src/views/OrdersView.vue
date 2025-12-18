<script setup>
import { ref } from 'vue'
import Breadcrumb from '@/components/layout/Breadcrumb.vue'

const breadcrumbItems = [{ label: 'My Orders' }]

const activeTab = ref('all')

const orders = ref([
  {
    id: 'ORD-ABC123',
    date: 'Dec 15, 2024',
    status: 'delivered',
    total: 350,
    items: [
      { title: 'Brooklyn-NYC Sweatshirt', size: 'L', color: 'Black', image: 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=200', price: 200 },
      { title: 'Basic Plain Shirt', size: 'M', color: 'White', image: 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=200', price: 150 }
    ]
  },
  {
    id: 'ORD-DEF456',
    date: 'Dec 10, 2024',
    status: 'shipping',
    total: 200,
    items: [
      { title: 'Orange Sweatshirt', size: 'XL', color: 'Orange', image: 'https://images.unsplash.com/photo-1620799140408-ed5341cd2431?w=200', price: 200 }
    ]
  },
  {
    id: 'ORD-GHI789',
    date: 'Dec 5, 2024',
    status: 'processing',
    total: 425,
    items: [
      { title: 'Flowers Printed Sweatshirt', size: 'M', color: 'Multi', image: 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=200', price: 225 },
      { title: 'Line Pattern Zipper', size: 'L', color: 'Black', image: 'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?w=200', price: 200 }
    ]
  }
])

const statusColors = {
  delivered: 'bg-success/10 text-success',
  shipping: 'bg-warning/10 text-warning',
  processing: 'bg-primary/10 text-primary',
  cancelled: 'bg-danger/10 text-danger'
}

const filteredOrders = (status) => {
  if (status === 'all') return orders.value
  return orders.value.filter(o => o.status === status)
}
</script>

<template>
  <div class="pb-12">
    <Breadcrumb :items="breadcrumbItems" />
    
    <div class="bg-white rounded-2xl p-8">
      <h2 class="text-2xl font-bold mb-6">Order History</h2>
      
      <!-- Tabs -->
      <div class="flex gap-4 mb-8 border-b border-border">
        <button 
          v-for="tab in ['all', 'processing', 'shipping', 'delivered']"
          :key="tab"
          :class="[
            'py-3 px-4 font-medium text-sm border-b-2 transition-all capitalize',
            activeTab === tab ? 'border-primary text-primary' : 'border-transparent text-text-light hover:text-text'
          ]"
          @click="activeTab = tab"
        >
          {{ tab === 'all' ? 'All Orders' : tab }}
        </button>
      </div>

      <!-- Orders List -->
      <div class="space-y-6">
        <div 
          v-for="order in filteredOrders(activeTab)"
          :key="order.id"
          class="border border-border rounded-xl overflow-hidden"
        >
          <div class="flex justify-between items-center p-4 bg-background">
            <div class="flex items-center gap-6">
              <div>
                <p class="text-sm text-text-light">Order Number</p>
                <p class="font-bold">{{ order.id }}</p>
              </div>
              <div>
                <p class="text-sm text-text-light">Order Date</p>
                <p class="font-medium">{{ order.date }}</p>
              </div>
            </div>
            <div class="flex items-center gap-4">
              <span :class="['px-3 py-1 rounded-full text-sm font-medium capitalize', statusColors[order.status]]">
                {{ order.status }}
              </span>
              <span class="font-bold text-lg">${{ order.total.toFixed(2) }}</span>
            </div>
          </div>
          
          <div class="p-4 space-y-4">
            <div 
              v-for="(item, index) in order.items"
              :key="index"
              class="flex items-center gap-4"
            >
              <img :src="item.image" class="w-16 h-16 rounded-lg object-cover" :alt="item.title">
              <div class="flex-1">
                <p class="font-medium">{{ item.title }}</p>
                <p class="text-sm text-text-light">{{ item.size }} / {{ item.color }}</p>
              </div>
              <span class="font-bold">${{ item.price }}</span>
            </div>
          </div>
          
          <div class="flex justify-end gap-3 p-4 border-t border-border">
            <button class="px-4 py-2 border border-border rounded-lg text-sm font-medium hover:border-primary hover:text-primary transition-colors">
              View Details
            </button>
            <button 
              v-if="order.status === 'delivered'"
              class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors"
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
