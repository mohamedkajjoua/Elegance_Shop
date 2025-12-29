<script setup>
import { ref } from 'vue'
import AdminHeader from '@/components/admin/AdminHeader.vue'
import AdminNav from '@/components/admin/AdminNav.vue'
const sidebarOpen = ref(false)
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
          <input type="text" placeholder="Search..." class="w-32 lg:w-48 pl-9 pr-3 py-2 bg-gray-100 rounded-lg text-sm">
          <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
        </div>
      </AdminHeader>

      <main class="p-3 sm:p-4 md:p-6">
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-orange-500 to-pink-500 rounded-xl sm:rounded-2xl p-4 sm:p-6 text-white mb-4 sm:mb-6">
          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div>
              <h2 class="text-lg sm:text-xl md:text-2xl font-bold mb-1 sm:mb-2">Welcome back, Admin! 👋</h2>
              <p class="text-white/80 text-sm sm:text-base">Here's what's happening with your store today.</p>
            </div>
            <button class="px-3 sm:px-4 py-2 bg-white/20 rounded-lg font-medium hover:bg-white/30 transition-colors text-sm whitespace-nowrap">
              View Reports
            </button>
          </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
          <!-- Total Revenue -->
          <div class="bg-white rounded-xl p-3 sm:p-4 md:p-5">
            <div class="flex items-center justify-between mb-2 sm:mb-4">
              <div class="w-9 h-9 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-green-100 flex items-center justify-center">
                <i class="fa-solid fa-dollar-sign text-green-500 text-sm sm:text-base md:text-xl"></i>
              </div>
              <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-green-100 text-green-600 rounded text-[10px] sm:text-xs font-medium">+12.5%</span>
            </div>
            <p class="text-lg sm:text-xl md:text-2xl font-bold text-slate-800">$48,574</p>
            <p class="text-xs sm:text-sm text-slate-500">Total Revenue</p>
          </div>

          <!-- Total Orders -->
          <div class="bg-white rounded-xl p-3 sm:p-4 md:p-5">
            <div class="flex items-center justify-between mb-2 sm:mb-4">
              <div class="w-9 h-9 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-blue-100 flex items-center justify-center">
                <i class="fa-solid fa-cart-shopping text-blue-500 text-sm sm:text-base md:text-xl"></i>
              </div>
              <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-blue-100 text-blue-600 rounded text-[10px] sm:text-xs font-medium">+8.2%</span>
            </div>
            <p class="text-lg sm:text-xl md:text-2xl font-bold text-slate-800">1,245</p>
            <p class="text-xs sm:text-sm text-slate-500">Total Orders</p>
          </div>

          <!-- Total Products -->
          <div class="bg-white rounded-xl p-3 sm:p-4 md:p-5">
            <div class="flex items-center justify-between mb-2 sm:mb-4">
              <div class="w-9 h-9 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-purple-100 flex items-center justify-center">
                <i class="fa-solid fa-cube text-purple-500 text-sm sm:text-base md:text-xl"></i>
              </div>
              <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-purple-100 text-purple-600 rounded text-[10px] sm:text-xs font-medium">+5.4%</span>
            </div>
            <p class="text-lg sm:text-xl md:text-2xl font-bold text-slate-800">856</p>
            <p class="text-xs sm:text-sm text-slate-500">Total Products</p>
          </div>

          <!-- Total Customers -->
          <div class="bg-white rounded-xl p-3 sm:p-4 md:p-5">
            <div class="flex items-center justify-between mb-2 sm:mb-4">
              <div class="w-9 h-9 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-lg sm:rounded-xl bg-orange-100 flex items-center justify-center">
                <i class="fa-solid fa-users text-orange-500 text-sm sm:text-base md:text-xl"></i>
              </div>
              <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-orange-100 text-orange-600 rounded text-[10px] sm:text-xs font-medium">+15.3%</span>
            </div>
            <p class="text-lg sm:text-xl md:text-2xl font-bold text-slate-800">5,420</p>
            <p class="text-xs sm:text-sm text-slate-500">Total Customers</p>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
          <!-- Chart Area -->
          <div class="lg:col-span-2 bg-white rounded-xl p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4 sm:mb-6">
              <h3 class="text-base sm:text-lg font-semibold text-slate-800">Revenue Overview</h3>
              <select class="px-2 sm:px-3 py-1.5 sm:py-2 bg-gray-100 rounded-lg text-xs sm:text-sm border-0">
                <option>This Year</option>
                <option>Last Year</option>
              </select>
            </div>
            
            <!-- Chart Placeholder -->
            <div class="h-48 sm:h-56 md:h-64 bg-gradient-to-t from-orange-50 to-white rounded-xl flex items-end justify-around px-2 sm:px-4 pb-3 sm:pb-4 overflow-x-auto">
              <div class="w-4 sm:w-6 md:w-8 bg-orange-200 rounded-t flex-shrink-0" style="height: 40%"></div>
              <div class="w-4 sm:w-6 md:w-8 bg-orange-300 rounded-t flex-shrink-0" style="height: 60%"></div>
              <div class="w-4 sm:w-6 md:w-8 bg-orange-400 rounded-t flex-shrink-0" style="height: 45%"></div>
              <div class="w-4 sm:w-6 md:w-8 bg-orange-500 rounded-t flex-shrink-0" style="height: 80%"></div>
              <div class="w-4 sm:w-6 md:w-8 bg-orange-400 rounded-t flex-shrink-0" style="height: 65%"></div>
              <div class="w-4 sm:w-6 md:w-8 bg-orange-300 rounded-t flex-shrink-0" style="height: 55%"></div>
              <div class="w-4 sm:w-6 md:w-8 bg-orange-500 rounded-t flex-shrink-0" style="height: 90%"></div>
              <div class="w-4 sm:w-6 md:w-8 bg-orange-400 rounded-t flex-shrink-0" style="height: 70%"></div>
              <div class="w-4 sm:w-6 md:w-8 bg-orange-300 rounded-t flex-shrink-0" style="height: 50%"></div>
              <div class="w-4 sm:w-6 md:w-8 bg-orange-200 rounded-t flex-shrink-0" style="height: 35%"></div>
              <div class="w-4 sm:w-6 md:w-8 bg-orange-400 rounded-t flex-shrink-0" style="height: 75%"></div>
              <div class="w-4 sm:w-6 md:w-8 bg-orange-500 rounded-t flex-shrink-0" style="height: 85%"></div>
            </div>
            <div class="flex justify-between sm:justify-around mt-2 sm:mt-3 text-[10px] sm:text-xs text-slate-500 overflow-x-auto gap-1">
              <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span>
              <span class="hidden sm:inline">Jul</span><span class="hidden sm:inline">Aug</span><span class="hidden sm:inline">Sep</span><span class="hidden sm:inline">Oct</span><span class="hidden sm:inline">Nov</span><span class="hidden sm:inline">Dec</span>
            </div>
          </div>

          <!-- Recent Orders -->
          <div class="bg-white rounded-xl p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4 sm:mb-6">
              <h3 class="text-base sm:text-lg font-semibold text-slate-800">Recent Orders</h3>
              <a href="#" class="text-orange-500 text-xs sm:text-sm font-medium hover:underline">View All</a>
            </div>
            
            <div class="space-y-3 sm:space-y-4">
              <div class="flex items-center gap-2 sm:gap-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                  <span class="text-blue-500 font-medium text-xs sm:text-sm">JD</span>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="font-medium text-slate-800 text-xs sm:text-sm truncate">John Doe</p>
                  <p class="text-[10px] sm:text-xs text-slate-500">2 items • $124.00</p>
                </div>
                <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-green-100 text-green-600 rounded text-[10px] sm:text-xs flex-shrink-0">Paid</span>
              </div>

              <div class="flex items-center gap-2 sm:gap-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                  <span class="text-purple-500 font-medium text-xs sm:text-sm">SM</span>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="font-medium text-slate-800 text-xs sm:text-sm truncate">Sarah Miller</p>
                  <p class="text-[10px] sm:text-xs text-slate-500">5 items • $342.00</p>
                </div>
                <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-yellow-100 text-yellow-600 rounded text-[10px] sm:text-xs flex-shrink-0">Pending</span>
              </div>

              <div class="flex items-center gap-2 sm:gap-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                  <span class="text-green-500 font-medium text-xs sm:text-sm">RJ</span>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="font-medium text-slate-800 text-xs sm:text-sm truncate">Robert Johnson</p>
                  <p class="text-[10px] sm:text-xs text-slate-500">1 item • $89.00</p>
                </div>
                <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-green-100 text-green-600 rounded text-[10px] sm:text-xs flex-shrink-0">Paid</span>
              </div>

              <div class="flex items-center gap-2 sm:gap-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0">
                  <span class="text-orange-500 font-medium text-xs sm:text-sm">EW</span>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="font-medium text-slate-800 text-xs sm:text-sm truncate">Emily Watson</p>
                  <p class="text-[10px] sm:text-xs text-slate-500">3 items • $256.00</p>
                </div>
                <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-blue-100 text-blue-600 rounded text-[10px] sm:text-xs flex-shrink-0">Shipped</span>
              </div>

              <div class="flex items-center gap-2 sm:gap-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                  <span class="text-red-500 font-medium text-xs sm:text-sm">MC</span>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="font-medium text-slate-800 text-xs sm:text-sm truncate">Michael Chen</p>
                  <p class="text-[10px] sm:text-xs text-slate-500">4 items • $189.00</p>
                </div>
                <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-green-100 text-green-600 rounded text-[10px] sm:text-xs flex-shrink-0">Paid</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Top Products -->
        <div class="mt-4 sm:mt-6 bg-white rounded-xl p-4 sm:p-6">
          <div class="flex items-center justify-between mb-4 sm:mb-6">
            <h3 class="text-base sm:text-lg font-semibold text-slate-800">Top Selling Products</h3>
            <a href="#" class="text-orange-500 text-xs sm:text-sm font-medium hover:underline">View All</a>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
            <!-- Product 1 -->
            <div class="flex flex-col items-center p-3 sm:p-4 bg-gray-50 rounded-xl">
              <img src="https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=100" class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 rounded-lg object-cover mb-2 sm:mb-3">
              <p class="font-medium text-slate-800 text-xs sm:text-sm text-center truncate w-full">Black T-shirt</p>
              <p class="text-[10px] sm:text-xs text-slate-500">1,234 sold</p>
              <p class="text-orange-500 font-bold mt-1 text-sm sm:text-base">$80.00</p>
            </div>

            <!-- Product 2 -->
            <div class="flex flex-col items-center p-3 sm:p-4 bg-gray-50 rounded-xl">
              <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?w=100" class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 rounded-lg object-cover mb-2 sm:mb-3">
              <p class="font-medium text-slate-800 text-xs sm:text-sm text-center truncate w-full">Leather Bag</p>
              <p class="text-[10px] sm:text-xs text-slate-500">986 sold</p>
              <p class="text-orange-500 font-bold mt-1 text-sm sm:text-base">$136.00</p>
            </div>

            <!-- Product 3 -->
            <div class="flex flex-col items-center p-3 sm:p-4 bg-gray-50 rounded-xl">
              <img src="https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=100" class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 rounded-lg object-cover mb-2 sm:mb-3">
              <p class="font-medium text-slate-800 text-xs sm:text-sm text-center truncate w-full">Golden Dress</p>
              <p class="text-[10px] sm:text-xs text-slate-500">875 sold</p>
              <p class="text-orange-500 font-bold mt-1 text-sm sm:text-base">$219.00</p>
            </div>

            <!-- Product 4 -->
            <div class="flex flex-col items-center p-3 sm:p-4 bg-gray-50 rounded-xl">
              <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=100" class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 rounded-lg object-cover mb-2 sm:mb-3">
              <p class="font-medium text-slate-800 text-xs sm:text-sm text-center truncate w-full">Gray Cap</p>
              <p class="text-[10px] sm:text-xs text-slate-500">654 sold</p>
              <p class="text-orange-500 font-bold mt-1 text-sm sm:text-base">$76.00</p>
            </div>

            <!-- Product 5 -->
            <div class="flex flex-col items-center p-3 sm:p-4 bg-gray-50 rounded-xl col-span-2 sm:col-span-1">
              <img src="https://images.unsplash.com/photo-1544022613-e87ca75a784a?w=100" class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 rounded-lg object-cover mb-2 sm:mb-3">
              <p class="font-medium text-slate-800 text-xs sm:text-sm text-center truncate w-full">Cargo Pant</p>
              <p class="text-[10px] sm:text-xs text-slate-500">543 sold</p>
              <p class="text-orange-500 font-bold mt-1 text-sm sm:text-base">$110.00</p>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>
