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
      <AdminHeader title="CREATE BRAND" @toggle-sidebar="sidebarOpen = true">
        <div class="relative hidden md:block">
          <input type="text" placeholder="Search..." class="w-32 lg:w-48 pl-10 pr-4 py-2 bg-gray-100 rounded-lg text-sm">
          <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
      </AdminHeader>

      <main class="p-4 md:p-6">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
          <router-link to="/admin/brand" class="hover:text-orange-500">Brand</router-link>
          <i class="fa-solid fa-chevron-right text-xs"></i>
          <span class="text-slate-800 font-medium">Create Brand</span>
        </div>

        <div class="max-w-2xl">
          <!-- Create Form -->
          <div class="bg-white rounded-lg md:rounded-xl p-4 md:p-6 shadow-sm">
            <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4 md:mb-6">New Brand Details</h3>
            
            <div class="space-y-5">
              <!-- Brand Name -->
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Brand Name <span class="text-red-500">*</span></label>
                <input 
                  type="text" 
                  placeholder="Enter brand name"
                  class="w-full px-3 md:px-4 py-2.5 md:py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm md:text-base"
                >
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 mt-6 md:mt-8 pt-4 md:pt-6 border-t border-gray-100">
              <router-link to="/admin/brand" class="w-full sm:w-auto px-4 md:px-6 py-2.5 md:py-3 border border-gray-200 rounded-lg font-medium text-slate-600 hover:bg-gray-50 text-center text-sm md:text-base">
                Cancel
              </router-link>
              <button class="w-full sm:w-auto px-4 md:px-6 py-2.5 md:py-3 bg-orange-500 text-white rounded-lg font-medium hover:bg-orange-600 text-sm md:text-base">
                <i class="fa-solid fa-plus mr-2"></i> Create Brand
              </button>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>
