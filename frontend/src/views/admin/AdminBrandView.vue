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
      <AdminHeader title="BRAND LIST" @toggle-sidebar="sidebarOpen = true">
        <div class="relative hidden md:block">
          <input type="text" placeholder="Search..." class="w-32 lg:w-48 pl-10 pr-4 py-2 bg-gray-100 rounded-lg text-sm">
          <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
      </AdminHeader>

      <main class="p-3 md:p-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 md:mb-6">
          <div>
            <h2 class="text-lg md:text-2xl font-bold text-slate-800">Brand Management</h2>
            <p class="text-xs md:text-sm text-slate-500 mt-0.5 md:mt-1">Manage your product brands</p>
          </div>
          <router-link to="/admin/brand/create" class="px-3 md:px-5 py-2 md:py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg md:rounded-xl font-medium hover:from-orange-600 hover:to-orange-700 shadow-lg shadow-orange-500/25 transition-all text-sm md:text-base">
            <i class="fa-solid fa-plus mr-1 md:mr-2"></i> <span class="hidden sm:inline">Add New</span> Brand
          </router-link>
        </div>

        <!-- Brand Cards Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 md:gap-4">
          <!-- Brand Card 1 -->
          <div class="bg-white rounded-lg md:rounded-xl p-3 md:p-5 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 group">
            <div class="flex items-center justify-between">
              <h3 class="text-sm md:text-base font-semibold text-slate-800">Nike</h3>
              <div class="flex gap-1 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                <router-link to="/admin/brand/edit" class="w-6 h-6 md:w-7 md:h-7 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center hover:bg-blue-100">
                  <i class="fa-solid fa-pen text-[10px] md:text-xs"></i>
                </router-link>
                <button class="w-6 h-6 md:w-7 md:h-7 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-100">
                  <i class="fa-solid fa-trash text-[10px] md:text-xs"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Brand Card 2 -->
          <div class="bg-white rounded-lg md:rounded-xl p-3 md:p-5 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 group">
            <div class="flex items-center justify-between">
              <h3 class="text-sm md:text-base font-semibold text-slate-800">Adidas</h3>
              <div class="flex gap-1 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                <router-link to="/admin/brand/edit" class="w-6 h-6 md:w-7 md:h-7 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center hover:bg-blue-100">
                  <i class="fa-solid fa-pen text-[10px] md:text-xs"></i>
                </router-link>
                <button class="w-6 h-6 md:w-7 md:h-7 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-100">
                  <i class="fa-solid fa-trash text-[10px] md:text-xs"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Brand Card 3 -->
          <div class="bg-white rounded-lg md:rounded-xl p-3 md:p-5 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 group">
            <div class="flex items-center justify-between">
              <h3 class="text-sm md:text-base font-semibold text-slate-800">Puma</h3>
              <div class="flex gap-1 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                <router-link to="/admin/brand/edit" class="w-6 h-6 md:w-7 md:h-7 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center hover:bg-blue-100">
                  <i class="fa-solid fa-pen text-[10px] md:text-xs"></i>
                </router-link>
                <button class="w-6 h-6 md:w-7 md:h-7 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-100">
                  <i class="fa-solid fa-trash text-[10px] md:text-xs"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Brand Card 4 -->
          <div class="bg-white rounded-lg md:rounded-xl p-3 md:p-5 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 group">
            <div class="flex items-center justify-between">
              <h3 class="text-sm md:text-base font-semibold text-slate-800">H&M</h3>
              <div class="flex gap-1 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                <router-link to="/admin/brand/edit" class="w-6 h-6 md:w-7 md:h-7 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center hover:bg-blue-100">
                  <i class="fa-solid fa-pen text-[10px] md:text-xs"></i>
                </router-link>
                <button class="w-6 h-6 md:w-7 md:h-7 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-100">
                  <i class="fa-solid fa-trash text-[10px] md:text-xs"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Brand Card 5 -->
          <div class="bg-white rounded-lg md:rounded-xl p-3 md:p-5 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 group">
            <div class="flex items-center justify-between">
              <h3 class="text-sm md:text-base font-semibold text-slate-800">Zara</h3>
              <div class="flex gap-1 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                <router-link to="/admin/brand/edit" class="w-6 h-6 md:w-7 md:h-7 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center hover:bg-blue-100">
                  <i class="fa-solid fa-pen text-[10px] md:text-xs"></i>
                </router-link>
                <button class="w-6 h-6 md:w-7 md:h-7 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-100">
                  <i class="fa-solid fa-trash text-[10px] md:text-xs"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Brand Card 6 -->
          <div class="bg-white rounded-lg md:rounded-xl p-3 md:p-5 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 group">
            <div class="flex items-center justify-between">
              <h3 class="text-sm md:text-base font-semibold text-slate-800">Uniqlo</h3>
              <div class="flex gap-1 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                <router-link to="/admin/brand/edit" class="w-6 h-6 md:w-7 md:h-7 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center hover:bg-blue-100">
                  <i class="fa-solid fa-pen text-[10px] md:text-xs"></i>
                </router-link>
                <button class="w-6 h-6 md:w-7 md:h-7 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-100">
                  <i class="fa-solid fa-trash text-[10px] md:text-xs"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Brand Card 7 -->
          <div class="bg-white rounded-lg md:rounded-xl p-3 md:p-5 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 group">
            <div class="flex items-center justify-between">
              <h3 class="text-sm md:text-base font-semibold text-slate-800">Reebok</h3>
              <div class="flex gap-1 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                <router-link to="/admin/brand/edit" class="w-6 h-6 md:w-7 md:h-7 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center hover:bg-blue-100">
                  <i class="fa-solid fa-pen text-[10px] md:text-xs"></i>
                </router-link>
                <button class="w-6 h-6 md:w-7 md:h-7 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-100">
                  <i class="fa-solid fa-trash text-[10px] md:text-xs"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Brand Card 8 -->
          <div class="bg-white rounded-lg md:rounded-xl p-3 md:p-5 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 group">
            <div class="flex items-center justify-between">
              <h3 class="text-sm md:text-base font-semibold text-slate-800">Levi's</h3>
              <div class="flex gap-1 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                <router-link to="/admin/brand/edit" class="w-6 h-6 md:w-7 md:h-7 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center hover:bg-blue-100">
                  <i class="fa-solid fa-pen text-[10px] md:text-xs"></i>
                </router-link>
                <button class="w-6 h-6 md:w-7 md:h-7 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-100">
                  <i class="fa-solid fa-trash text-[10px] md:text-xs"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Add New Brand Card -->
          <div class="bg-gray-50 rounded-lg md:rounded-xl p-3 md:p-5 border-2 border-dashed border-gray-300 hover:border-orange-400 flex items-center justify-center cursor-pointer transition-all group min-h-[48px] md:min-h-[60px]">
            <router-link to="/admin/brand/create" class="flex items-center gap-1 md:gap-2 text-slate-500 group-hover:text-orange-500 transition-colors text-sm md:text-base">
              <i class="fa-solid fa-plus text-xs md:text-sm"></i>
              <span class="font-medium">Add Brand</span>
            </router-link>
          </div>
        </div>

        <!-- Pagination -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-4 md:mt-8">
          <p class="text-xs md:text-sm text-slate-500">Showing <span class="font-medium">8</span> of <span class="font-medium">12</span> brands</p>
          <div class="flex items-center gap-1 md:gap-2">
            <button class="w-8 h-8 md:w-9 md:h-9 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-50">
              <i class="fa-solid fa-chevron-left text-xs"></i>
            </button>
            <button class="w-8 h-8 md:w-9 md:h-9 rounded-lg bg-orange-500 text-white flex items-center justify-center font-medium text-sm">1</button>
            <button class="w-8 h-8 md:w-9 md:h-9 rounded-lg border border-gray-200 flex items-center justify-center text-slate-600 hover:bg-gray-50 text-sm">2</button>
            <button class="w-8 h-8 md:w-9 md:h-9 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-50">
              <i class="fa-solid fa-chevron-right text-xs"></i>
            </button>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>
