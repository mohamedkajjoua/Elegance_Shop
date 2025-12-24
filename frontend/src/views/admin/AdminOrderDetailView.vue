<script setup>
import { ref } from 'vue'

// Toggle state for submenu
const productsOpen = ref(false)
// Mobile sidebar toggle
const sidebarOpen = ref(false)

// Sample order data
const order = ref({
  id: '#583488/80',
  createdAt: 'Apr 23, 2024',
  updatedAt: 'Apr 24, 2024',
  priority: 'Normal',
  paymentStatus: 'Paid',
  orderStatus: 'Shipped',
  customer: {
    name: 'Gail C. Anderson',
    email: 'gail.anderson@email.com',
    phone: '+1 (555) 123-4567',
    avatar: 'https://ui-avatars.com/api/?name=Gail+Anderson&background=F97316&color=fff'
  },
  shippingAddress: {
    street: '123 Main Street',
    city: 'Los Angeles',
    state: 'California',
    zip: '90001',
    country: 'United States'
  },
  billingAddress: {
    street: '123 Main Street',
    city: 'Los Angeles',
    state: 'California',
    zip: '90001',
    country: 'United States'
  },
  items: [
    {
      id: 1,
      name: 'Black T-shirt',
      image: 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=100',
      sku: 'TSH-BLK-001',
      size: 'M',
      color: 'Black',
      quantity: 2,
      price: 80.00
    },
    {
      id: 2,
      name: 'Blue Denim Jeans',
      image: 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=100',
      sku: 'JNS-BLU-002',
      size: 'L',
      color: 'Blue',
      quantity: 1,
      price: 120.00
    },
    {
      id: 3,
      name: 'White Sneakers',
      image: 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=100',
      sku: 'SHO-WHT-003',
      size: '42',
      color: 'White',
      quantity: 1,
      price: 150.00
    }
  ],
  subtotal: 430.00,
  shipping: 15.00,
  tax: 34.40,
  discount: 50.00,
  total: 429.40,
  paymentMethod: 'Credit Card',
  paymentDetails: '**** **** **** 4242',
  notes: 'Please leave package at front door if no one is home.'
})

// Calculate totals
const itemsTotal = ref(order.value.items.reduce((sum, item) => sum + (item.price * item.quantity), 0))
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex">
    <!-- Mobile Overlay -->
    <div v-if="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>
    
    <!-- Sidebar -->
    <aside :class="[
      'bg-slate-800 text-white flex flex-col fixed h-full z-50 transition-transform duration-300',
      'w-64 lg:translate-x-0',
      sidebarOpen ? 'translate-x-0' : '-translate-x-full'
    ]">
      <!-- Logo -->
      <div class="p-5 flex items-center gap-3 border-b border-slate-700">
        <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
          <i class="fa-solid fa-bag-shopping text-white"></i>
        </div>
        <span class="text-xl font-bold">LapakBaju</span>
        <button @click="sidebarOpen = false" class="ml-auto text-slate-400 hover:text-white lg:hidden">
          <i class="fa-solid fa-times"></i>
        </button>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 overflow-y-auto py-4">
        <p class="px-5 text-xs text-slate-500 uppercase tracking-wider mb-3">General</p>
        
        <router-link to="/admin" class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
          <i class="fa-solid fa-grid-2 w-5"></i>
          <span>Dashboard</span>
        </router-link>

        <!-- Products -->
        <div class="relative">
          <button @click="productsOpen = !productsOpen" class="w-full flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
            <i class="fa-solid fa-cube w-5"></i>
            <span>Products</span>
            <i :class="['fa-solid ml-auto text-xs transition-transform', productsOpen ? 'fa-chevron-up' : 'fa-chevron-down']"></i>
          </button>
          <div v-show="productsOpen" class="bg-slate-900/50 py-2">
            <router-link to="/admin/products" class="block px-12 py-2 text-sm text-slate-300 hover:text-orange-500">Product List</router-link>
            <router-link to="/admin/products/create" class="block px-12 py-2 text-sm text-slate-300 hover:text-orange-500">Create Product</router-link>
          </div>
        </div>

        <!-- Category -->
        <router-link to="/admin/category" class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
          <i class="fa-solid fa-layer-group w-5"></i>
          <span>Category</span>
        </router-link>

        <!-- Brand -->
        <router-link to="/admin/brand" class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
          <i class="fa-solid fa-tag w-5"></i>
          <span>Brand</span>
        </router-link>

        <!-- Orders - Active -->
        <router-link to="/admin/orders" class="flex items-center gap-3 px-5 py-3 bg-orange-500/10 text-orange-500 border-l-4 border-orange-500">
          <i class="fa-solid fa-cart-shopping w-5"></i>
          <span>Orders</span>
        </router-link>

        <a href="#" class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
          <i class="fa-solid fa-gear w-5"></i>
          <span>Settings</span>
        </a>

        <p class="px-5 text-xs text-slate-500 uppercase tracking-wider mt-6 mb-3">Users</p>
        
        <router-link to="/admin/users" class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
          <i class="fa-solid fa-users w-5"></i>
          <span>User List</span>
        </router-link>
        
        <router-link to="/admin/profile" class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
          <i class="fa-solid fa-user w-5"></i>
          <span>Profile</span>
        </router-link>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64">
      <!-- Header -->
      <header class="bg-white h-16 flex items-center justify-between px-4 md:px-6 sticky top-0 z-10 shadow-sm">
        <button @click="sidebarOpen = true" class="lg:hidden w-10 h-10 rounded-full flex items-center justify-center text-slate-500 hover:bg-gray-100">
          <i class="fa-solid fa-bars"></i>
        </button>
        <h1 class="text-base md:text-lg font-bold text-slate-800">ORDER DETAIL</h1>
        
        <div class="flex items-center gap-2 md:gap-4">
          <button class="w-10 h-10 rounded-full flex items-center justify-center text-slate-500 hover:bg-gray-100 hidden md:flex">
            <i class="fa-solid fa-moon"></i>
          </button>
          <button class="w-10 h-10 rounded-full flex items-center justify-center text-slate-500 hover:bg-gray-100 relative">
            <i class="fa-solid fa-bell"></i>
            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
          </button>
          <div class="w-10 h-10 rounded-full bg-orange-500 overflow-hidden">
            <img src="https://ui-avatars.com/api/?name=Admin&background=F97316&color=fff" alt="Admin">
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="p-4 md:p-6">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
          <router-link to="/admin/orders" class="hover:text-orange-500">Orders</router-link>
          <i class="fa-solid fa-chevron-right text-xs"></i>
          <span class="text-slate-800 font-medium">{{ order.id }}</span>
        </div>

        <!-- Back Button & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
          <router-link to="/admin/orders" class="inline-flex items-center gap-2 text-slate-600 hover:text-orange-500">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Orders</span>
          </router-link>
          <div class="flex items-center gap-2">
            <button class="px-4 py-2 border border-gray-200 rounded-lg font-medium text-slate-600 hover:bg-gray-50 flex items-center gap-2">
              <i class="fa-solid fa-print text-sm"></i>
              <span class="hidden sm:inline">Print</span>
            </button>
            <button class="px-4 py-2 border border-blue-200 bg-blue-50 rounded-lg font-medium text-blue-600 hover:bg-blue-100 flex items-center gap-2">
              <i class="fa-solid fa-pen text-sm"></i>
              <span class="hidden sm:inline">Edit</span>
            </button>
            <button class="px-4 py-2 border border-red-200 bg-red-50 rounded-lg font-medium text-red-500 hover:bg-red-100 flex items-center gap-2">
              <i class="fa-solid fa-trash text-sm"></i>
              <span class="hidden sm:inline">Delete</span>
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
                  <h2 class="text-xl md:text-2xl font-bold text-slate-800">{{ order.id }}</h2>
                  <p class="text-sm text-slate-500 mt-1">Placed on {{ order.createdAt }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                  <!-- Payment Status -->
                  <span :class="[
                    'px-3 py-1 rounded-full text-sm font-medium',
                    order.paymentStatus === 'Paid' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'
                  ]">
                    {{ order.paymentStatus }}
                  </span>
                  <!-- Order Status -->
                  <span :class="[
                    'px-3 py-1 rounded-full text-sm font-medium',
                    order.orderStatus === 'Shipped' ? 'bg-blue-100 text-blue-600' :
                    order.orderStatus === 'Delivered' ? 'bg-green-100 text-green-600' :
                    order.orderStatus === 'Cancelled' ? 'bg-red-100 text-red-600' :
                    'bg-yellow-100 text-yellow-600'
                  ]">
                    {{ order.orderStatus }}
                  </span>
                  <!-- Priority -->
                  <span :class="[
                    'px-3 py-1 rounded-full text-sm font-medium',
                    order.priority === 'Urgent' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600'
                  ]">
                    {{ order.priority }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
              <div class="p-4 md:p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-slate-800">Order Items ({{ order.items.length }})</h3>
              </div>
              
              <div class="divide-y divide-gray-100">
                <div v-for="item in order.items" :key="item.id" class="p-4 md:p-6 flex gap-4">
                  <img :src="item.image" :alt="item.name" class="w-16 h-16 md:w-20 md:h-20 rounded-lg object-cover flex-shrink-0">
                  <div class="flex-1 min-w-0">
                    <h4 class="font-medium text-slate-800 truncate">{{ item.name }}</h4>
                    <p class="text-sm text-slate-500">SKU: {{ item.sku }}</p>
                    <div class="flex flex-wrap gap-2 mt-1 text-sm text-slate-500">
                      <span>Size: {{ item.size }}</span>
                      <span>•</span>
                      <span>Color: {{ item.color }}</span>
                    </div>
                  </div>
                  <div class="text-right">
                    <p class="font-medium text-slate-800">${{ (item.price * item.quantity).toFixed(2) }}</p>
                    <p class="text-sm text-slate-500">${{ item.price.toFixed(2) }} × {{ item.quantity }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Order Notes -->
            <div v-if="order.notes" class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
              <h3 class="text-lg font-semibold text-slate-800 mb-3">Order Notes</h3>
              <p class="text-slate-600 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                <i class="fa-solid fa-note-sticky text-yellow-500 mr-2"></i>
                {{ order.notes }}
              </p>
            </div>
          </div>

          <!-- Right Column - Customer & Payment -->
          <div class="space-y-4">
            <!-- Customer Info -->
            <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
              <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4">Customer</h3>
              <div class="flex items-center gap-3 mb-4">
                <img :src="order.customer.avatar" :alt="order.customer.name" class="w-12 h-12 rounded-full">
                <div>
                  <p class="font-medium text-slate-800">{{ order.customer.name }}</p>
                  <p class="text-sm text-slate-500">{{ order.customer.email }}</p>
                </div>
              </div>
              <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2 text-slate-600">
                  <i class="fa-solid fa-phone w-4 text-slate-400"></i>
                  <span>{{ order.customer.phone }}</span>
                </div>
                <div class="flex items-center gap-2 text-slate-600">
                  <i class="fa-solid fa-envelope w-4 text-slate-400"></i>
                  <span>{{ order.customer.email }}</span>
                </div>
              </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
              <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4">Shipping Address</h3>
              <div class="text-sm text-slate-600 space-y-1">
                <p>{{ order.shippingAddress.street }}</p>
                <p>{{ order.shippingAddress.city }}, {{ order.shippingAddress.state }} {{ order.shippingAddress.zip }}</p>
                <p>{{ order.shippingAddress.country }}</p>
              </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
              <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4">Payment</h3>
              <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg mb-4">
                <i class="fa-solid fa-credit-card text-slate-400 text-xl"></i>
                <div>
                  <p class="font-medium text-slate-800">{{ order.paymentMethod }}</p>
                  <p class="text-sm text-slate-500">{{ order.paymentDetails }}</p>
                </div>
              </div>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-slate-500">Subtotal</span>
                  <span class="text-slate-800">${{ itemsTotal.toFixed(2) }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-500">Shipping</span>
                  <span class="text-slate-800">${{ order.shipping.toFixed(2) }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-500">Tax (8%)</span>
                  <span class="text-slate-800">${{ order.tax.toFixed(2) }}</span>
                </div>
                <div v-if="order.discount > 0" class="flex justify-between text-green-600">
                  <span>Discount</span>
                  <span>-${{ order.discount.toFixed(2) }}</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-gray-200 font-semibold">
                  <span class="text-slate-800">Total</span>
                  <span class="text-orange-500 text-lg">${{ order.total.toFixed(2) }}</span>
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
                    <p class="font-medium text-slate-800">Order Shipped</p>
                    <p class="text-sm text-slate-500">Apr 24, 2024 - 10:30 AM</p>
                  </div>
                </div>
                <div class="flex gap-3">
                  <div class="flex flex-col items-center">
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    <div class="w-0.5 h-full bg-gray-200 mt-1"></div>
                  </div>
                  <div class="pb-4">
                    <p class="font-medium text-slate-800">Payment Confirmed</p>
                    <p class="text-sm text-slate-500">Apr 23, 2024 - 2:15 PM</p>
                  </div>
                </div>
                <div class="flex gap-3">
                  <div class="flex flex-col items-center">
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                  </div>
                  <div>
                    <p class="font-medium text-slate-800">Order Placed</p>
                    <p class="text-sm text-slate-500">Apr 23, 2024 - 2:00 PM</p>
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
</style>
