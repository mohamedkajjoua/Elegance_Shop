<script setup>
import { ref } from 'vue'
import AdminHeader from '@/components/admin/AdminHeader.vue'
import AdminNav from '@/components/admin/AdminNav.vue'

const sidebarOpen = ref(false)
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    <AdminNav :isOpen="sidebarOpen" @close="sidebarOpen = false" />

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64">
      <!-- Header -->
      <!-- Header -->
      <AdminHeader title="PRODUCT LIST" @toggle-sidebar="sidebarOpen = true">
        <div class="relative hidden md:block">
          <input type="text" placeholder="Search..." class="w-32 lg:w-48 pl-10 pr-4 py-2 bg-gray-100 rounded-lg text-sm">
          <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
      </AdminHeader>

      <!-- Page Content -->
      <main class="p-4 md:p-6">
        <!-- Content Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
          <h2 class="text-lg md:text-xl font-semibold text-slate-800">All Product List</h2>
          <div class="flex items-center gap-2 md:gap-3">
            <router-link to="/admin/products/create" class="px-3 md:px-4 py-2 bg-orange-500 text-white rounded-lg font-medium hover:bg-orange-600 transition-colors text-sm md:text-base">
              <i class="fa-solid fa-plus mr-1 md:mr-2"></i> <span class="hidden sm:inline">Add</span> Product
            </router-link>
            <select class="px-2 md:px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
              <option>This Month</option>
              <option>Last Month</option>
              <option>This Year</option>
            </select>
          </div>
        </div>

        <!-- Mobile Product Cards (visible only on mobile) -->
        <div class="md:hidden space-y-3">
          <!-- Product Card 1 -->
          <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex gap-3">
              <img src="https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=100" alt="Product" class="w-20 h-20 rounded-xl object-cover flex-shrink-0">
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                  <div>
                    <h3 class="font-semibold text-slate-800 text-sm">Black T-shirt</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Size: S, M, L, XL</p>
                  </div>
                  <span class="px-2 py-1 bg-orange-100 text-orange-600 text-xs font-medium rounded-full">Fashion</span>
                </div>
                <div class="flex items-center justify-between mt-2">
                  <span class="text-lg font-bold text-orange-500">$80.00</span>
                  <div class="flex items-center gap-1 text-amber-500">
                    <i class="fa-solid fa-star text-xs"></i>
                    <span class="text-sm font-medium">4.5</span>
                  </div>
                </div>
                <div class="flex items-center justify-between mt-2 text-xs">
                  <div class="flex gap-3">
                    <span class="text-slate-600"><span class="font-medium text-slate-800">486</span> Left</span>
                    <span class="text-green-600"><span class="font-medium">155</span> Sold</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="flex items-center justify-end gap-2 mt-3 pt-3 border-t border-gray-100">
              <router-link to="/admin/products/1" class="flex-1 py-2 px-3 rounded-lg border border-gray-200 flex items-center justify-center gap-2 text-slate-600 hover:bg-gray-50 text-sm">
                <i class="fa-solid fa-eye"></i> View
              </router-link>
              <button class="flex-1 py-2 px-3 rounded-lg border border-blue-200 bg-blue-50 flex items-center justify-center gap-2 text-blue-600 hover:bg-blue-100 text-sm">
                <i class="fa-solid fa-pen"></i> Edit
              </button>
              <button class="py-2 px-3 rounded-lg border border-red-200 bg-red-50 flex items-center justify-center text-red-500 hover:bg-red-100">
                <i class="fa-solid fa-trash text-sm"></i>
              </button>
            </div>
          </div>

          <!-- Product Card 2 -->
          <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex gap-3">
              <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?w=100" alt="Product" class="w-20 h-20 rounded-xl object-cover flex-shrink-0">
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                  <div>
                    <h3 class="font-semibold text-slate-800 text-sm">Olive Green Leather Bag</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Size: S, M</p>
                  </div>
                  <span class="px-2 py-1 bg-purple-100 text-purple-600 text-xs font-medium rounded-full">Hand Bag</span>
                </div>
                <div class="flex items-center justify-between mt-2">
                  <span class="text-lg font-bold text-orange-500">$136.00</span>
                  <div class="flex items-center gap-1 text-amber-500">
                    <i class="fa-solid fa-star text-xs"></i>
                    <span class="text-sm font-medium">4.1</span>
                  </div>
                </div>
                <div class="flex items-center justify-between mt-2 text-xs">
                  <div class="flex gap-3">
                    <span class="text-slate-600"><span class="font-medium text-slate-800">784</span> Left</span>
                    <span class="text-green-600"><span class="font-medium">674</span> Sold</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="flex items-center justify-end gap-2 mt-3 pt-3 border-t border-gray-100">
              <button class="flex-1 py-2 px-3 rounded-lg border border-gray-200 flex items-center justify-center gap-2 text-slate-600 hover:bg-gray-50 text-sm">
                <i class="fa-solid fa-eye"></i> View
              </button>
              <button class="flex-1 py-2 px-3 rounded-lg border border-blue-200 bg-blue-50 flex items-center justify-center gap-2 text-blue-600 hover:bg-blue-100 text-sm">
                <i class="fa-solid fa-pen"></i> Edit
              </button>
              <button class="py-2 px-3 rounded-lg border border-red-200 bg-red-50 flex items-center justify-center text-red-500 hover:bg-red-100">
                <i class="fa-solid fa-trash text-sm"></i>
              </button>
            </div>
          </div>

          <!-- Product Card 3 -->
          <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex gap-3">
              <img src="https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=100" alt="Product" class="w-20 h-20 rounded-xl object-cover flex-shrink-0">
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                  <div>
                    <h3 class="font-semibold text-slate-800 text-sm">Women Golden Dress</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Size: S, M</p>
                  </div>
                  <span class="px-2 py-1 bg-orange-100 text-orange-600 text-xs font-medium rounded-full">Fashion</span>
                </div>
                <div class="flex items-center justify-between mt-2">
                  <span class="text-lg font-bold text-orange-500">$219.00</span>
                  <div class="flex items-center gap-1 text-amber-500">
                    <i class="fa-solid fa-star text-xs"></i>
                    <span class="text-sm font-medium">4.4</span>
                  </div>
                </div>
                <div class="flex items-center justify-between mt-2 text-xs">
                  <div class="flex gap-3">
                    <span class="text-slate-600"><span class="font-medium text-slate-800">769</span> Left</span>
                    <span class="text-green-600"><span class="font-medium">100</span> Sold</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="flex items-center justify-end gap-2 mt-3 pt-3 border-t border-gray-100">
              <button class="flex-1 py-2 px-3 rounded-lg border border-gray-200 flex items-center justify-center gap-2 text-slate-600 hover:bg-gray-50 text-sm">
                <i class="fa-solid fa-eye"></i> View
              </button>
              <button class="flex-1 py-2 px-3 rounded-lg border border-blue-200 bg-blue-50 flex items-center justify-center gap-2 text-blue-600 hover:bg-blue-100 text-sm">
                <i class="fa-solid fa-pen"></i> Edit
              </button>
              <button class="py-2 px-3 rounded-lg border border-red-200 bg-red-50 flex items-center justify-center text-red-500 hover:bg-red-100">
                <i class="fa-solid fa-trash text-sm"></i>
              </button>
            </div>
          </div>

          <!-- Product Card 4 -->
          <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex gap-3">
              <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=100" alt="Product" class="w-20 h-20 rounded-xl object-cover flex-shrink-0">
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                  <div>
                    <h3 class="font-semibold text-slate-800 text-sm">Gray Cap For Men</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Size: S, M, L</p>
                  </div>
                  <span class="px-2 py-1 bg-green-100 text-green-600 text-xs font-medium rounded-full">Cap</span>
                </div>
                <div class="flex items-center justify-between mt-2">
                  <span class="text-lg font-bold text-orange-500">$76.00</span>
                  <div class="flex items-center gap-1 text-amber-500">
                    <i class="fa-solid fa-star text-xs"></i>
                    <span class="text-sm font-medium">4.2</span>
                  </div>
                </div>
                <div class="flex items-center justify-between mt-2 text-xs">
                  <div class="flex gap-3">
                    <span class="text-slate-600"><span class="font-medium text-slate-800">571</span> Left</span>
                    <span class="text-green-600"><span class="font-medium">87</span> Sold</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="flex items-center justify-end gap-2 mt-3 pt-3 border-t border-gray-100">
              <button class="flex-1 py-2 px-3 rounded-lg border border-gray-200 flex items-center justify-center gap-2 text-slate-600 hover:bg-gray-50 text-sm">
                <i class="fa-solid fa-eye"></i> View
              </button>
              <button class="flex-1 py-2 px-3 rounded-lg border border-blue-200 bg-blue-50 flex items-center justify-center gap-2 text-blue-600 hover:bg-blue-100 text-sm">
                <i class="fa-solid fa-pen"></i> Edit
              </button>
              <button class="py-2 px-3 rounded-lg border border-red-200 bg-red-50 flex items-center justify-center text-red-500 hover:bg-red-100">
                <i class="fa-solid fa-trash text-sm"></i>
              </button>
            </div>
          </div>

          <!-- Product Card 5 -->
          <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex gap-3">
              <img src="https://images.unsplash.com/photo-1544022613-e87ca75a784a?w=100" alt="Product" class="w-20 h-20 rounded-xl object-cover flex-shrink-0">
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                  <div>
                    <h3 class="font-semibold text-slate-800 text-sm">Dark Green Cargo Pant</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Size: S, M, L, XL</p>
                  </div>
                  <span class="px-2 py-1 bg-orange-100 text-orange-600 text-xs font-medium rounded-full">Fashion</span>
                </div>
                <div class="flex items-center justify-between mt-2">
                  <span class="text-lg font-bold text-orange-500">$110.00</span>
                  <div class="flex items-center gap-1 text-amber-500">
                    <i class="fa-solid fa-star text-xs"></i>
                    <span class="text-sm font-medium">4.4</span>
                  </div>
                </div>
                <div class="flex items-center justify-between mt-2 text-xs">
                  <div class="flex gap-3">
                    <span class="text-slate-600"><span class="font-medium text-slate-800">241</span> Left</span>
                    <span class="text-green-600"><span class="font-medium">342</span> Sold</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="flex items-center justify-end gap-2 mt-3 pt-3 border-t border-gray-100">
              <button class="flex-1 py-2 px-3 rounded-lg border border-gray-200 flex items-center justify-center gap-2 text-slate-600 hover:bg-gray-50 text-sm">
                <i class="fa-solid fa-eye"></i> View
              </button>
              <button class="flex-1 py-2 px-3 rounded-lg border border-blue-200 bg-blue-50 flex items-center justify-center gap-2 text-blue-600 hover:bg-blue-100 text-sm">
                <i class="fa-solid fa-pen"></i> Edit
              </button>
              <button class="py-2 px-3 rounded-lg border border-red-200 bg-red-50 flex items-center justify-center text-red-500 hover:bg-red-100">
                <i class="fa-solid fa-trash text-sm"></i>
              </button>
            </div>
          </div>

          <!-- Mobile Pagination -->
          <div class="bg-white rounded-xl shadow-sm p-4 flex flex-col items-center gap-3">
            <p class="text-sm text-slate-500">Showing 1 to 5 of 50 entries</p>
            <div class="flex items-center gap-1">
              <button class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">
                <i class="fa-solid fa-chevron-left text-xs"></i>
              </button>
              <button class="w-9 h-9 rounded-lg bg-orange-500 text-white flex items-center justify-center font-medium">1</button>
              <button class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">2</button>
              <button class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">3</button>
              <button class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">...</button>
              <button class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">
                <i class="fa-solid fa-chevron-right text-xs"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Desktop/Tablet Products Table (hidden on mobile) -->
        <div class="hidden md:block bg-white rounded-xl shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full min-w-[800px]">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="px-6 py-4 text-left">
                  <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-500">
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Product Name & Size</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Price</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Stock</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Category</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Rating</th>
                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">Action</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <!-- Product Row 1 -->
              <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                  <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-500">
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <img src="https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=100" alt="Product" class="w-12 h-12 rounded-lg object-cover">
                    <div>
                      <p class="font-medium text-slate-800">Black T-shirt</p>
                      <p class="text-sm text-slate-500">Size: S, M, L, XL</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 font-medium text-slate-800">$80.00</td>
                <td class="px-6 py-4">
                  <div>
                    <span class="text-slate-800">486 Item</span>
                    <span class="text-orange-500 text-sm"> Left</span>
                  </div>
                  <span class="text-green-500 text-sm">155 Sold</span>
                </td>
                <td class="px-6 py-4 text-slate-600">Fashion</td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <span class="flex items-center gap-1 text-amber-500">
                      <i class="fa-solid fa-star text-xs"></i> 4.5
                    </span>
                    <span class="text-slate-500 text-sm">55 Review</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-center gap-2">
                    <router-link to="/admin/products/1" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100 hover:text-slate-700">
                      <i class="fa-solid fa-eye text-sm"></i>
                    </router-link>
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-blue-500 hover:bg-blue-50">
                      <i class="fa-solid fa-pen text-sm"></i>
                    </button>
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-red-500 hover:bg-red-50">
                      <i class="fa-solid fa-trash text-sm"></i>
                    </button>
                  </div>
                </td>
              </tr>

              <!-- Product Row 2 -->
              <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                  <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-500">
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?w=100" alt="Product" class="w-12 h-12 rounded-lg object-cover">
                    <div>
                      <p class="font-medium text-slate-800">Olive Green Leather Bag</p>
                      <p class="text-sm text-slate-500">Size: S, M</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 font-medium text-slate-800 line-through text-slate-400">$136.00</td>
                <td class="px-6 py-4">
                  <div>
                    <span class="text-slate-800">784 Item</span>
                    <span class="text-orange-500 text-sm"> Left</span>
                  </div>
                  <span class="text-green-500 text-sm">674 Sold</span>
                </td>
                <td class="px-6 py-4 text-slate-600">Hand Bag</td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <span class="flex items-center gap-1 text-amber-500">
                      <i class="fa-solid fa-star text-xs"></i> 4.1
                    </span>
                    <span class="text-slate-500 text-sm">141 Review</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-center gap-2">
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100 hover:text-slate-700">
                      <i class="fa-solid fa-eye text-sm"></i>
                    </button>
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-blue-500 hover:bg-blue-50">
                      <i class="fa-solid fa-pen text-sm"></i>
                    </button>
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-red-500 hover:bg-red-50">
                      <i class="fa-solid fa-trash text-sm"></i>
                    </button>
                  </div>
                </td>
              </tr>

              <!-- Product Row 3 -->
              <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                  <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-500">
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <img src="https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=100" alt="Product" class="w-12 h-12 rounded-lg object-cover">
                    <div>
                      <p class="font-medium text-slate-800">Women Golden Dress</p>
                      <p class="text-sm text-slate-500">Size: S, M</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 font-medium text-slate-800 line-through text-slate-400">$219.00</td>
                <td class="px-6 py-4">
                  <div>
                    <span class="text-slate-800">769 Item</span>
                    <span class="text-orange-500 text-sm"> Left</span>
                  </div>
                  <span class="text-green-500 text-sm">100 Sold</span>
                </td>
                <td class="px-6 py-4 text-slate-600">Fashion</td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <span class="flex items-center gap-1 text-amber-500">
                      <i class="fa-solid fa-star text-xs"></i> 4.4
                    </span>
                    <span class="text-slate-500 text-sm">174 Review</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-center gap-2">
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100 hover:text-slate-700">
                      <i class="fa-solid fa-eye text-sm"></i>
                    </button>
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-blue-500 hover:bg-blue-50">
                      <i class="fa-solid fa-pen text-sm"></i>
                    </button>
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-red-500 hover:bg-red-50">
                      <i class="fa-solid fa-trash text-sm"></i>
                    </button>
                  </div>
                </td>
              </tr>

              <!-- Product Row 4 -->
              <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                  <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-500">
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=100" alt="Product" class="w-12 h-12 rounded-lg object-cover">
                    <div>
                      <p class="font-medium text-slate-800">Gray Cap For Men</p>
                      <p class="text-sm text-slate-500">Size: S, M, L</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 font-medium text-slate-800">$76.00</td>
                <td class="px-6 py-4">
                  <div>
                    <span class="text-slate-800">571 Item</span>
                    <span class="text-orange-500 text-sm"> Left</span>
                  </div>
                  <span class="text-green-500 text-sm">87 Sold</span>
                </td>
                <td class="px-6 py-4 text-slate-600">Cap</td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <span class="flex items-center gap-1 text-amber-500">
                      <i class="fa-solid fa-star text-xs"></i> 4.2
                    </span>
                    <span class="text-slate-500 text-sm">23 Review</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-center gap-2">
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100 hover:text-slate-700">
                      <i class="fa-solid fa-eye text-sm"></i>
                    </button>
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-blue-500 hover:bg-blue-50">
                      <i class="fa-solid fa-pen text-sm"></i>
                    </button>
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-red-500 hover:bg-red-50">
                      <i class="fa-solid fa-trash text-sm"></i>
                    </button>
                  </div>
                </td>
              </tr>

              <!-- Product Row 5 -->
              <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                  <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-500">
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <img src="https://images.unsplash.com/photo-1544022613-e87ca75a784a?w=100" alt="Product" class="w-12 h-12 rounded-lg object-cover">
                    <div>
                      <p class="font-medium text-slate-800">Dark Green Cargo Pant</p>
                      <p class="text-sm text-slate-500">Size: S, M, L, XL</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 font-medium text-slate-800">$110.00</td>
                <td class="px-6 py-4">
                  <div>
                    <span class="text-slate-800">241 Item</span>
                    <span class="text-orange-500 text-sm"> Left</span>
                  </div>
                  <span class="text-green-500 text-sm">342 Sold</span>
                </td>
                <td class="px-6 py-4 text-slate-600">Fashion</td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <span class="flex items-center gap-1 text-amber-500">
                      <i class="fa-solid fa-star text-xs"></i> 4.4
                    </span>
                    <span class="text-slate-500 text-sm">109 Review</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-center gap-2">
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100 hover:text-slate-700">
                      <i class="fa-solid fa-eye text-sm"></i>
                    </button>
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-blue-500 hover:bg-blue-50">
                      <i class="fa-solid fa-pen text-sm"></i>
                    </button>
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-red-500 hover:bg-red-50">
                      <i class="fa-solid fa-trash text-sm"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          </div>

          <!-- Pagination -->
          <div class="px-4 md:px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-gray-100">
            <p class="text-sm text-slate-500">Showing 1 to 5 of 50 entries</p>
            <div class="flex items-center gap-1 md:gap-2">
              <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">
                <i class="fa-solid fa-chevron-left text-xs"></i>
              </button>
              <button class="w-8 h-8 rounded-lg bg-orange-500 text-white flex items-center justify-center">1</button>
              <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">2</button>
              <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100 hidden sm:flex">3</button>
              <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">...</button>
              <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">10</button>
              <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">
                <i class="fa-solid fa-chevron-right text-xs"></i>
              </button>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<style scoped>
/* ========================================
   BASE STYLES (Mobile First Approach)
   ======================================== */

.admin-products-container {
  min-height: 100vh;
}

/* Product Image */
.product-image {
  width: 48px;
  height: 48px;
  border-radius: 8px;
  object-fit: cover;
}

/* Action Buttons */
.action-btn {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}

/* ========================================
   MOBILE STYLES (max-width: 639px)
   ======================================== */
@media (max-width: 639px) {
  /* Header adjustments */
  .admin-header {
    padding: 0.5rem 1rem;
  }

  .admin-header h1 {
    font-size: 0.875rem;
    font-weight: 600;
  }

  /* Main content padding */
  .admin-main {
    padding: 1rem;
  }

  /* Content header - stack on mobile */
  .content-header {
    flex-direction: column;
    gap: 1rem;
  }

  .content-header h2 {
    font-size: 1rem;
  }

  /* Buttons group */
  .button-group {
    width: 100%;
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
  }

  .button-group .add-btn {
    flex: 1;
    min-width: 120px;
    padding: 0.625rem 1rem;
    font-size: 0.875rem;
  }

  .button-group select {
    flex: 1;
    min-width: 100px;
  }

  /* Table container - horizontal scroll */
  .table-container {
    margin: 0 -1rem;
    padding: 0;
    border-radius: 0;
  }

  .table-wrapper {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }

  /* Table cells */
  .products-table th,
  .products-table td {
    padding: 0.75rem 0.5rem;
    font-size: 0.75rem;
  }

  .products-table th:first-child,
  .products-table td:first-child {
    padding-left: 1rem;
  }

  .products-table th:last-child,
  .products-table td:last-child {
    padding-right: 1rem;
  }

  /* Product info cell */
  .product-info {
    min-width: 200px;
  }

  .product-image {
    width: 40px;
    height: 40px;
  }

  .product-name {
    font-size: 0.8125rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 150px;
  }

  .product-size {
    font-size: 0.6875rem;
  }

  /* Action buttons - smaller on mobile */
  .action-btn {
    width: 28px;
    height: 28px;
  }

  .action-btn i {
    font-size: 0.6875rem;
  }

  /* Pagination */
  .pagination-container {
    flex-direction: column;
    gap: 0.75rem;
    padding: 1rem;
  }

  .pagination-info {
    font-size: 0.75rem;
    text-align: center;
  }

  .pagination-buttons {
    gap: 0.25rem;
  }

  .page-btn {
    width: 28px;
    height: 28px;
    font-size: 0.75rem;
  }

  /* Hide some columns on mobile */
  .hide-mobile {
    display: none !important;
  }
}

/* ========================================
   TABLET STYLES (640px - 1023px)
   ======================================== */
@media (min-width: 640px) and (max-width: 1023px) {
  /* Header */
  .admin-header {
    padding: 0.75rem 1.5rem;
  }

  .admin-header h1 {
    font-size: 1rem;
  }

  /* Main content */
  .admin-main {
    padding: 1.5rem;
  }

  /* Content header */
  .content-header {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
  }

  .content-header h2 {
    font-size: 1.125rem;
  }

  /* Button group */
  .button-group {
    display: flex;
    gap: 0.75rem;
  }

  .button-group .add-btn {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
  }

  /* Table */
  .table-container {
    border-radius: 0.75rem;
    overflow: hidden;
  }

  .products-table th,
  .products-table td {
    padding: 1rem 0.75rem;
    font-size: 0.8125rem;
  }

  /* Product info */
  .product-image {
    width: 44px;
    height: 44px;
  }

  .product-name {
    font-size: 0.875rem;
    max-width: 180px;
  }

  /* Action buttons */
  .action-btn {
    width: 30px;
    height: 30px;
  }

  /* Pagination */
  .pagination-container {
    padding: 1rem 1.5rem;
    flex-direction: row;
    justify-content: space-between;
  }

  .page-btn {
    width: 32px;
    height: 32px;
  }

  /* Sidebar - fixed but narrower */
  .admin-sidebar {
    width: 240px;
  }

  .main-content {
    margin-left: 0;
  }
}

/* ========================================
   DESKTOP / PC STYLES (1024px and above)
   ======================================== */
@media (min-width: 1024px) {
  /* Header */
  .admin-header {
    padding: 0.75rem 1.5rem;
  }

  .admin-header h1 {
    font-size: 1.125rem;
  }

  /* Search input */
  .search-input {
    width: 200px;
    transition: width 0.3s ease;
  }

  .search-input:focus {
    width: 280px;
  }

  /* Main content - sidebar offset */
  .main-content {
    margin-left: 256px;
  }

  .admin-main {
    padding: 1.5rem;
  }

  /* Content header */
  .content-header h2 {
    font-size: 1.25rem;
  }

  /* Button group */
  .button-group {
    gap: 0.75rem;
  }

  .button-group .add-btn {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
  }

  /* Table */
  .table-container {
    border-radius: 0.75rem;
  }

  .products-table {
    min-width: 800px;
  }

  .products-table th,
  .products-table td {
    padding: 1rem 1.5rem;
  }

  /* Product info */
  .product-image {
    width: 48px;
    height: 48px;
  }

  .product-name {
    font-size: 0.9375rem;
    max-width: none;
  }

  /* Action buttons with hover effects */
  .action-btn {
    width: 32px;
    height: 32px;
  }

  .action-btn:hover {
    transform: scale(1.05);
  }

  /* Pagination */
  .pagination-container {
    padding: 1rem 1.5rem;
  }

  .page-btn {
    width: 32px;
    height: 32px;
  }

  .page-btn:hover:not(.active) {
    background-color: #f3f4f6;
  }
}

/* ========================================
   LARGE DESKTOP STYLES (1280px and above)
   ======================================== */
@media (min-width: 1280px) {
  .admin-main {
    padding: 2rem;
  }

  .search-input {
    width: 240px;
  }

  .search-input:focus {
    width: 320px;
  }

  .products-table th,
  .products-table td {
    padding: 1.25rem 1.5rem;
  }

  .content-header h2 {
    font-size: 1.375rem;
  }
}

/* ========================================
   EXTRA LARGE DESKTOP (1536px and above)
   ======================================== */
@media (min-width: 1536px) {
  .admin-main {
    padding: 2rem 2.5rem;
  }

  .products-table th,
  .products-table td {
    padding: 1.25rem 2rem;
  }
}

/* ========================================
   UTILITY CLASSES
   ======================================== */

/* Smooth transitions */
.transition-all {
  transition: all 0.2s ease-in-out;
}

/* Custom scrollbar for table */
.table-wrapper::-webkit-scrollbar {
  height: 6px;
}

.table-wrapper::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 3px;
}

.table-wrapper::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 3px;
}

.table-wrapper::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* Focus states for accessibility */
.action-btn:focus,
.page-btn:focus,
.add-btn:focus {
  outline: 2px solid #f97316;
  outline-offset: 2px;
}

/* Animations */
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

.table-container {
  animation: fadeIn 0.3s ease-out;
}

/* Print styles */
@media print {
  .admin-sidebar,
  .mobile-menu-btn,
  .pagination-container,
  .action-btn {
    display: none !important;
  }

  .main-content {
    margin-left: 0 !important;
  }

  .products-table {
    font-size: 10pt;
  }
}
</style>
