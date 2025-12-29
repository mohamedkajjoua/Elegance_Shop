<script setup>
import { ref } from 'vue'
import AdminHeader from '@/components/admin/AdminHeader.vue'
import AdminNav from '@/components/admin/AdminNav.vue'

const showIconPicker = ref(false)
const selectedIcon = ref('fa-shirt')
const sidebarOpen = ref(false)

const icons = [
  'fa-shirt', 'fa-mobile', 'fa-bag-shopping', 'fa-ring', 'fa-shoe-prints', 
  'fa-spa', 'fa-clock', 'fa-laptop', 'fa-tv', 'fa-headphones',
  'fa-camera', 'fa-gift', 'fa-car', 'fa-plane', 'fa-house',
  'fa-dumbbell', 'fa-futbol', 'fa-book', 'fa-music', 'fa-heart'
]

const selectIcon = (icon) => {
  selectedIcon.value = icon
  showIconPicker.value = false
}
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex">
    <!-- Mobile Overlay -->
    <!-- Sidebar -->
    <AdminNav :isOpen="sidebarOpen" @close="sidebarOpen = false" />

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64">
      <AdminHeader title="EDIT CATEGORY" @toggle-sidebar="sidebarOpen = true">
        <div class="relative hidden md:block">
          <input type="text" placeholder="Search..." class="w-32 lg:w-48 pl-10 pr-4 py-2 bg-gray-100 rounded-lg text-sm">
          <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
      </AdminHeader>

      <main class="p-4 md:p-6">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
          <router-link to="/admin/category" class="hover:text-orange-500">Category</router-link>
          <i class="fa-solid fa-chevron-right text-xs"></i>
          <span class="text-slate-800 font-medium">Edit Category</span>
        </div>

        <div class="max-w-2xl">
          <!-- Edit Form -->
          <div class="bg-white rounded-lg md:rounded-xl p-4 md:p-6 shadow-sm">
            <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4 md:mb-6">Edit Category Details</h3>
            
            <div class="space-y-4 md:space-y-5">
              <!-- Category Icon Preview -->
              <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <div class="w-16 h-16 md:w-20 md:h-20 rounded-lg md:rounded-xl bg-blue-100 flex items-center justify-center">
                  <i :class="['fa-solid', selectedIcon, 'text-2xl md:text-3xl text-blue-500']"></i>
                </div>
                <div class="relative">
                  <p class="text-sm font-medium text-slate-700 mb-1">Category Icon</p>
                  <button 
                    @click="showIconPicker = !showIconPicker"
                    class="px-3 py-1.5 text-sm text-orange-500 border border-orange-500 rounded-lg hover:bg-orange-50"
                  >
                    Change Icon
                  </button>
                  
                  <!-- Icon Picker Dropdown -->
                  <div v-if="showIconPicker" class="absolute top-16 left-0 right-0 sm:right-auto bg-white border border-gray-200 rounded-xl shadow-lg p-3 md:p-4 z-20 w-auto sm:w-72">
                    <p class="text-sm font-medium text-slate-700 mb-3">Select an icon</p>
                    <div class="grid grid-cols-5 gap-2">
                      <button 
                        v-for="icon in icons" 
                        :key="icon"
                        @click="selectIcon(icon)"
                        :class="[
                          'w-10 h-10 rounded-lg flex items-center justify-center hover:bg-orange-100 transition-colors',
                          selectedIcon === icon ? 'bg-orange-100 ring-2 ring-orange-500' : 'bg-gray-50'
                        ]"
                      >
                        <i :class="['fa-solid', icon, 'text-slate-600']"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Category Name -->
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Category Name</label>
                <input 
                  type="text" 
                  value="Fashion"
                  class="w-full px-3 md:px-4 py-2.5 md:py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm md:text-base"
                >
              </div>

              <!-- Icon Color -->
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Icon Background Color</label>
                <div class="flex flex-wrap gap-2 md:gap-3">
                  <label class="relative cursor-pointer">
                    <input type="radio" name="color" class="sr-only" checked>
                    <div class="w-10 h-10 rounded-lg bg-blue-100 ring-2 ring-orange-500 ring-offset-2 flex items-center justify-center">
                      <i class="fa-solid fa-check text-blue-500 text-sm"></i>
                    </div>
                  </label>
                  <label class="relative cursor-pointer">
                    <input type="radio" name="color" class="sr-only">
                    <div class="w-10 h-10 rounded-lg bg-green-100 hover:ring-2 hover:ring-orange-500 hover:ring-offset-2"></div>
                  </label>
                  <label class="relative cursor-pointer">
                    <input type="radio" name="color" class="sr-only">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 hover:ring-2 hover:ring-orange-500 hover:ring-offset-2"></div>
                  </label>
                  <label class="relative cursor-pointer">
                    <input type="radio" name="color" class="sr-only">
                    <div class="w-10 h-10 rounded-lg bg-orange-100 hover:ring-2 hover:ring-orange-500 hover:ring-offset-2"></div>
                  </label>
                  <label class="relative cursor-pointer">
                    <input type="radio" name="color" class="sr-only">
                    <div class="w-10 h-10 rounded-lg bg-pink-100 hover:ring-2 hover:ring-orange-500 hover:ring-offset-2"></div>
                  </label>
                  <label class="relative cursor-pointer">
                    <input type="radio" name="color" class="sr-only">
                    <div class="w-10 h-10 rounded-lg bg-teal-100 hover:ring-2 hover:ring-orange-500 hover:ring-offset-2"></div>
                  </label>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mt-6 md:mt-8 pt-4 md:pt-6 border-t border-gray-100">
              <button class="order-2 sm:order-1 px-4 py-2 text-red-500 hover:bg-red-50 rounded-lg font-medium text-sm md:text-base">
                <i class="fa-solid fa-trash mr-2"></i> Delete Category
              </button>
              <div class="order-1 sm:order-2 flex flex-col sm:flex-row gap-2 sm:gap-3 w-full sm:w-auto">
                <router-link to="/admin/category" class="w-full sm:w-auto px-4 md:px-6 py-2.5 md:py-3 border border-gray-200 rounded-lg font-medium text-slate-600 hover:bg-gray-50 text-center text-sm md:text-base">
                  Cancel
                </router-link>
                <button class="w-full sm:w-auto px-4 md:px-6 py-2.5 md:py-3 bg-orange-500 text-white rounded-lg font-medium hover:bg-orange-600 text-sm md:text-base">
                  <i class="fa-solid fa-save mr-2"></i> Save Changes
                </button>
              </div>
            </div>
          </div>

          <!-- Category Stats -->
          <div class="bg-white rounded-lg md:rounded-xl p-4 md:p-6 shadow-sm mt-4 md:mt-6">
            <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-3 md:mb-4">Category Statistics</h3>
            <div class="grid grid-cols-3 gap-2 md:gap-4">
              <div class="bg-gray-50 rounded-lg p-3 md:p-4 text-center">
                <p class="text-lg md:text-2xl font-bold text-slate-800">245</p>
                <p class="text-xs md:text-sm text-slate-500">Products</p>
              </div>
              <div class="bg-gray-50 rounded-lg p-3 md:p-4 text-center">
                <p class="text-lg md:text-2xl font-bold text-slate-800">1,234</p>
                <p class="text-xs md:text-sm text-slate-500">Total Sales</p>
              </div>
              <div class="bg-gray-50 rounded-lg p-3 md:p-4 text-center">
                <p class="text-lg md:text-2xl font-bold text-slate-800">$12.5K</p>
                <p class="text-xs md:text-sm text-slate-500">Revenue</p>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>
