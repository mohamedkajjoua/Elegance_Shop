<script setup>
import { ref } from 'vue'
const categoryOpen = ref(true)
const sidebarOpen = ref(false)
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
            <router-link to="/admin/category" class="block px-12 py-2 text-sm text-orange-500 font-medium">Category List</router-link>
            <router-link to="/admin/category/create" class="block px-12 py-2 text-sm text-slate-300 hover:text-orange-500">Create Category</router-link>
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
        
        <router-link to="/admin/profile" class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
          <i class="fa-solid fa-user w-5"></i>
          <span>Profile</span>
        </router-link>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64">
      <header class="bg-white h-16 flex items-center justify-between px-4 md:px-6 sticky top-0 z-10 shadow-sm">
        <button @click="sidebarOpen = true" class="lg:hidden w-10 h-10 rounded-full flex items-center justify-center text-slate-500 hover:bg-gray-100">
          <i class="fa-solid fa-bars"></i>
        </button>
        <h1 class="text-base md:text-lg font-bold text-slate-800">CATEGORY LIST</h1>
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

      <main class="p-3 md:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 md:mb-6">
          <h2 class="text-lg md:text-xl font-semibold text-slate-800">All Categories</h2>
          <div class="flex items-center gap-2 md:gap-3">
            <router-link to="/admin/category/create" class="px-3 md:px-4 py-2 bg-orange-500 text-white rounded-lg font-medium hover:bg-orange-600 text-sm md:text-base">
              <i class="fa-solid fa-plus mr-1 md:mr-2"></i> <span class="hidden sm:inline">Add</span> Category
            </router-link>
          </div>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 md:gap-4">
          <!-- Category Card 1 -->
          <div class="bg-white rounded-lg md:rounded-xl p-3 md:p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2 md:mb-4">
              <div class="w-10 h-10 md:w-14 md:h-14 rounded-lg md:rounded-xl bg-blue-100 flex items-center justify-center">
                <i class="fa-solid fa-shirt text-lg md:text-2xl text-blue-500"></i>
              </div>
              <div class="flex gap-1">
                <router-link to="/admin/category/edit" class="w-6 h-6 md:w-8 md:h-8 rounded-lg text-blue-500 hover:bg-blue-50 flex items-center justify-center">
                  <i class="fa-solid fa-pen text-xs md:text-sm"></i>
                </router-link>
                <button class="w-6 h-6 md:w-8 md:h-8 rounded-lg text-red-500 hover:bg-red-50 flex items-center justify-center">
                  <i class="fa-solid fa-trash text-xs md:text-sm"></i>
                </button>
              </div>
            </div>
            <h4 class="font-semibold text-slate-800 mb-0.5 md:mb-1 text-sm md:text-base">Fashion</h4>
            <p class="text-xs md:text-sm text-slate-500">245 Products</p>
          </div>

          <!-- Category Card 2 -->
          <div class="bg-white rounded-lg md:rounded-xl p-3 md:p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2 md:mb-4">
              <div class="w-10 h-10 md:w-14 md:h-14 rounded-lg md:rounded-xl bg-green-100 flex items-center justify-center">
                <i class="fa-solid fa-mobile text-lg md:text-2xl text-green-500"></i>
              </div>
              <div class="flex gap-1">
                <router-link to="/admin/category/edit" class="w-6 h-6 md:w-8 md:h-8 rounded-lg text-blue-500 hover:bg-blue-50 flex items-center justify-center">
                  <i class="fa-solid fa-pen text-xs md:text-sm"></i>
                </router-link>
                <button class="w-6 h-6 md:w-8 md:h-8 rounded-lg text-red-500 hover:bg-red-50 flex items-center justify-center">
                  <i class="fa-solid fa-trash text-xs md:text-sm"></i>
                </button>
              </div>
            </div>
            <h4 class="font-semibold text-slate-800 mb-0.5 md:mb-1 text-sm md:text-base">Electronics</h4>
            <p class="text-xs md:text-sm text-slate-500">189 Products</p>
          </div>

          <!-- Category Card 3 -->
          <div class="bg-white rounded-lg md:rounded-xl p-3 md:p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2 md:mb-4">
              <div class="w-10 h-10 md:w-14 md:h-14 rounded-lg md:rounded-xl bg-purple-100 flex items-center justify-center">
                <i class="fa-solid fa-bag-shopping text-lg md:text-2xl text-purple-500"></i>
              </div>
              <div class="flex gap-1">
                <router-link to="/admin/category/edit" class="w-6 h-6 md:w-8 md:h-8 rounded-lg text-blue-500 hover:bg-blue-50 flex items-center justify-center">
                  <i class="fa-solid fa-pen text-xs md:text-sm"></i>
                </router-link>
                <button class="w-6 h-6 md:w-8 md:h-8 rounded-lg text-red-500 hover:bg-red-50 flex items-center justify-center">
                  <i class="fa-solid fa-trash text-xs md:text-sm"></i>
                </button>
              </div>
            </div>
            <h4 class="font-semibold text-slate-800 mb-0.5 md:mb-1 text-sm md:text-base">Bags</h4>
            <p class="text-xs md:text-sm text-slate-500">78 Products</p>
          </div>

          <!-- Category Card 4 -->
          <div class="bg-white rounded-lg md:rounded-xl p-3 md:p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2 md:mb-4">
              <div class="w-10 h-10 md:w-14 md:h-14 rounded-lg md:rounded-xl bg-orange-100 flex items-center justify-center">
                <i class="fa-solid fa-ring text-lg md:text-2xl text-orange-500"></i>
              </div>
              <div class="flex gap-1">
                <router-link to="/admin/category/edit" class="w-6 h-6 md:w-8 md:h-8 rounded-lg text-blue-500 hover:bg-blue-50 flex items-center justify-center">
                  <i class="fa-solid fa-pen text-xs md:text-sm"></i>
                </router-link>
                <button class="w-6 h-6 md:w-8 md:h-8 rounded-lg text-red-500 hover:bg-red-50 flex items-center justify-center">
                  <i class="fa-solid fa-trash text-xs md:text-sm"></i>
                </button>
              </div>
            </div>
            <h4 class="font-semibold text-slate-800 mb-0.5 md:mb-1 text-sm md:text-base">Accessories</h4>
            <p class="text-xs md:text-sm text-slate-500">156 Products</p>
          </div>

          <!-- Category Card 5 -->
          <div class="bg-white rounded-lg md:rounded-xl p-3 md:p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2 md:mb-4">
              <div class="w-10 h-10 md:w-14 md:h-14 rounded-lg md:rounded-xl bg-pink-100 flex items-center justify-center">
                <i class="fa-solid fa-shoe-prints text-lg md:text-2xl text-pink-500"></i>
              </div>
              <div class="flex gap-1">
                <router-link to="/admin/category/edit" class="w-6 h-6 md:w-8 md:h-8 rounded-lg text-blue-500 hover:bg-blue-50 flex items-center justify-center">
                  <i class="fa-solid fa-pen text-xs md:text-sm"></i>
                </router-link>
                <button class="w-6 h-6 md:w-8 md:h-8 rounded-lg text-red-500 hover:bg-red-50 flex items-center justify-center">
                  <i class="fa-solid fa-trash text-xs md:text-sm"></i>
                </button>
              </div>
            </div>
            <h4 class="font-semibold text-slate-800 mb-0.5 md:mb-1 text-sm md:text-base">Footwear</h4>
            <p class="text-xs md:text-sm text-slate-500">92 Products</p>
          </div>

          <!-- Category Card 6 -->
          <div class="bg-white rounded-lg md:rounded-xl p-3 md:p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2 md:mb-4">
              <div class="w-10 h-10 md:w-14 md:h-14 rounded-lg md:rounded-xl bg-teal-100 flex items-center justify-center">
                <i class="fa-solid fa-spa text-lg md:text-2xl text-teal-500"></i>
              </div>
              <div class="flex gap-1">
                <router-link to="/admin/category/edit" class="w-6 h-6 md:w-8 md:h-8 rounded-lg text-blue-500 hover:bg-blue-50 flex items-center justify-center">
                  <i class="fa-solid fa-pen text-xs md:text-sm"></i>
                </router-link>
                <button class="w-6 h-6 md:w-8 md:h-8 rounded-lg text-red-500 hover:bg-red-50 flex items-center justify-center">
                  <i class="fa-solid fa-trash text-xs md:text-sm"></i>
                </button>
              </div>
            </div>
            <h4 class="font-semibold text-slate-800 mb-0.5 md:mb-1 text-sm md:text-base">Beauty</h4>
            <p class="text-xs md:text-sm text-slate-500">67 Products</p>
          </div>

          <!-- Category Card 7 -->
          <div class="bg-white rounded-lg md:rounded-xl p-3 md:p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2 md:mb-4">
              <div class="w-10 h-10 md:w-14 md:h-14 rounded-lg md:rounded-xl bg-amber-100 flex items-center justify-center">
                <i class="fa-solid fa-clock text-lg md:text-2xl text-amber-500"></i>
              </div>
              <div class="flex gap-1">
                <router-link to="/admin/category/edit" class="w-6 h-6 md:w-8 md:h-8 rounded-lg text-blue-500 hover:bg-blue-50 flex items-center justify-center">
                  <i class="fa-solid fa-pen text-xs md:text-sm"></i>
                </router-link>
                <button class="w-6 h-6 md:w-8 md:h-8 rounded-lg text-red-500 hover:bg-red-50 flex items-center justify-center">
                  <i class="fa-solid fa-trash text-xs md:text-sm"></i>
                </button>
              </div>
            </div>
            <h4 class="font-semibold text-slate-800 mb-0.5 md:mb-1 text-sm md:text-base">Watches</h4>
            <p class="text-xs md:text-sm text-slate-500">45 Products</p>
          </div>

          <!-- Add New Category Card -->
          <div class="bg-white rounded-lg md:rounded-xl p-3 md:p-5 shadow-sm border-2 border-dashed border-gray-200 hover:border-orange-500 flex items-center justify-center cursor-pointer transition-colors group min-h-[100px] md:min-h-[140px]">
            <div class="text-center">
              <div class="w-10 h-10 md:w-14 md:h-14 rounded-lg md:rounded-xl bg-gray-100 group-hover:bg-orange-100 flex items-center justify-center mx-auto mb-1 md:mb-2 transition-colors">
                <i class="fa-solid fa-plus text-base md:text-xl text-gray-400 group-hover:text-orange-500 transition-colors"></i>
              </div>
              <p class="text-xs md:text-sm text-slate-500 group-hover:text-orange-500 transition-colors">Add Category</p>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>
