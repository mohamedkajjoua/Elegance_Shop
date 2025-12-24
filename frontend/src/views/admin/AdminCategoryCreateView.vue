<script setup>
import { ref } from 'vue'

const showIconPicker = ref(false)
const selectedIcon = ref('fa-image')
const selectedColor = ref('blue')
const categoryOpen = ref(true)
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
    <div v-if="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>
    
    <!-- Sidebar -->
    <aside :class="[
      'bg-slate-800 text-white flex flex-col fixed h-full z-50 transition-transform duration-300',
      'w-64 lg:translate-x-0',
      sidebarOpen ? 'translate-x-0' : '-translate-x-full'
    ]">
      <div class="p-5 flex items-center gap-3 border-b border-slate-700">
        <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
          <i class="fa-solid fa-bag-shopping text-white"></i>
        </div>
        <span class="text-xl font-bold">LapakBaju</span>
        <button @click="sidebarOpen = false" class="ml-auto text-slate-400 hover:text-white lg:hidden">
          <i class="fa-solid fa-times"></i>
        </button>
      </div>

      <nav class="flex-1 overflow-y-auto py-4">
        <p class="px-5 text-xs text-slate-500 uppercase tracking-wider mb-3">General</p>
        
        <router-link to="/admin" class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
          <i class="fa-solid fa-grid-2 w-5"></i>
          <span>Dashboard</span>
        </router-link>

        <router-link to="/admin/products" class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
          <i class="fa-solid fa-cube w-5"></i>
          <span>Products</span>
        </router-link>

        <!-- Category - Active with Submenu Toggle -->
        <div class="relative">
          <button @click="categoryOpen = !categoryOpen" class="w-full flex items-center gap-3 px-5 py-3 bg-orange-500/10 text-orange-500 border-l-4 border-orange-500">
            <i class="fa-solid fa-layer-group w-5"></i>
            <span>Category</span>
            <i :class="['fa-solid ml-auto text-xs transition-transform', categoryOpen ? 'fa-chevron-up' : 'fa-chevron-down']"></i>
          </button>
          <div v-show="categoryOpen" class="bg-slate-900/50 py-2">
            <router-link to="/admin/category" class="block px-12 py-2 text-sm text-slate-300 hover:text-orange-500">Category List</router-link>
            <router-link to="/admin/category/create" class="block px-12 py-2 text-sm text-orange-500 font-medium">Create Category</router-link>
            <router-link to="/admin/category/edit" class="block px-12 py-2 text-sm text-slate-300 hover:text-orange-500">Edit Category</router-link>
          </div>
        </div>

        <router-link to="/admin/brand" class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
          <i class="fa-solid fa-tag w-5"></i>
          <span>Brand</span>
        </router-link>

        <router-link to="/admin/orders" class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
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
        
        <a href="#" class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
          <i class="fa-solid fa-user w-5"></i>
          <span>Profile</span>
        </a>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64">
      <header class="bg-white h-16 flex items-center justify-between px-4 md:px-6 sticky top-0 z-10 shadow-sm">
        <button @click="sidebarOpen = true" class="lg:hidden w-10 h-10 rounded-full flex items-center justify-center text-slate-500 hover:bg-gray-100">
          <i class="fa-solid fa-bars"></i>
        </button>
        <h1 class="text-base md:text-lg font-bold text-slate-800">CREATE CATEGORY</h1>
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
          <div class="relative hidden md:block">
            <input type="text" placeholder="Search..." class="w-32 lg:w-48 pl-10 pr-4 py-2 bg-gray-100 rounded-lg text-sm">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
          </div>
        </div>
      </header>

      <main class="p-4 md:p-6">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
          <router-link to="/admin/category" class="hover:text-orange-500">Category</router-link>
          <i class="fa-solid fa-chevron-right text-xs"></i>
          <span class="text-slate-800 font-medium">Create Category</span>
        </div>

        <div class="max-w-2xl">
          <!-- Create Form -->
          <div class="bg-white rounded-lg md:rounded-xl p-4 md:p-6 shadow-sm">
            <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4 md:mb-6">New Category Details</h3>
            
            <div class="space-y-5">
              <!-- Category Icon -->
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Category Icon</label>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                  <div class="w-16 h-16 md:w-20 md:h-20 rounded-lg md:rounded-xl bg-blue-100 flex items-center justify-center">
                    <i :class="['fa-solid', selectedIcon, 'text-xl md:text-2xl text-blue-500']"></i>
                  </div>
                  <div class="relative">
                    <button 
                      @click="showIconPicker = !showIconPicker"
                      class="px-4 py-2 text-sm text-orange-500 border border-orange-500 rounded-lg hover:bg-orange-50"
                    >
                      <i class="fa-solid fa-icons mr-2"></i> Choose Icon
                    </button>
                    <p class="text-xs text-slate-400 mt-1">Select from available icons</p>
                    
                    <!-- Icon Picker Dropdown -->
                    <div v-if="showIconPicker" class="absolute top-12 left-0 sm:left-0 right-0 sm:right-auto bg-white border border-gray-200 rounded-xl shadow-lg p-3 md:p-4 z-20 w-auto sm:w-72">
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
              </div>

              <!-- Category Name -->
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Category Name <span class="text-red-500">*</span></label>
                <input 
                  type="text" 
                  placeholder="Enter category name"
                  class="w-full px-3 md:px-4 py-2.5 md:py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm md:text-base"
                >
              </div>

              <!-- Icon Background Color -->
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
                  <label class="relative cursor-pointer">
                    <input type="radio" name="color" class="sr-only">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 hover:ring-2 hover:ring-orange-500 hover:ring-offset-2"></div>
                  </label>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 mt-6 md:mt-8 pt-4 md:pt-6 border-t border-gray-100">
              <router-link to="/admin/category" class="w-full sm:w-auto px-4 md:px-6 py-2.5 md:py-3 border border-gray-200 rounded-lg font-medium text-slate-600 hover:bg-gray-50 text-center text-sm md:text-base">
                Cancel
              </router-link>
              <button class="w-full sm:w-auto px-4 md:px-6 py-2.5 md:py-3 bg-orange-500 text-white rounded-lg font-medium hover:bg-orange-600 text-sm md:text-base">
                <i class="fa-solid fa-plus mr-2"></i> Create Category
              </button>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>
