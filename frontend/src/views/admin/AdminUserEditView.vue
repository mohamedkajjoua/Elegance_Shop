<script setup>
import { ref } from 'vue'
const usersOpen = ref(true)
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

        <router-link to="/admin/category" class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
          <i class="fa-solid fa-layer-group w-5"></i>
          <span>Category</span>
        </router-link>

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
        
        <!-- Users - Active with Submenu Toggle -->
        <div class="relative">
          <button @click="usersOpen = !usersOpen" class="w-full flex items-center gap-3 px-5 py-3 bg-orange-500/10 text-orange-500 border-l-4 border-orange-500">
            <i class="fa-solid fa-users w-5"></i>
            <span>User List</span>
            <i :class="['fa-solid ml-auto text-xs transition-transform', usersOpen ? 'fa-chevron-up' : 'fa-chevron-down']"></i>
          </button>
          <div v-show="usersOpen" class="bg-slate-900/50 py-2">
            <router-link to="/admin/users" class="block px-12 py-2 text-sm text-slate-300 hover:text-orange-500">User List</router-link>
            <router-link to="/admin/users/edit" class="block px-12 py-2 text-sm text-orange-500 font-medium">Edit User</router-link>
          </div>
        </div>
        
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
        <h1 class="text-base md:text-lg font-bold text-slate-800">EDIT USER</h1>
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
          <router-link to="/admin/users" class="hover:text-orange-500">Users</router-link>
          <i class="fa-solid fa-chevron-right text-xs"></i>
          <span class="text-slate-800 font-medium">Edit User</span>
        </div>

        <div class="max-w-2xl">
          <!-- User Avatar Section -->
          <div class="bg-white rounded-xl p-6 shadow-sm mb-6">
            <div class="flex items-center gap-6">
              <div class="w-24 h-24 rounded-full bg-blue-500 overflow-hidden">
                <img src="https://ui-avatars.com/api/?name=John+Doe&background=3B82F6&color=fff&size=96" alt="John Doe" class="w-full h-full object-cover">
              </div>
              <div>
                <h3 class="text-xl font-semibold text-slate-800">John Doe</h3>
                <p class="text-slate-500 mt-1">john.doe@email.com</p>
                <button class="mt-3 px-4 py-2 text-sm bg-gray-100 text-slate-600 rounded-lg hover:bg-gray-200">
                  <i class="fa-solid fa-camera mr-2"></i> Change Avatar
                </button>
              </div>
            </div>
          </div>

          <!-- Edit Form -->
          <div class="bg-white rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-800 mb-6">User Information</h3>
            
            <div class="grid grid-cols-2 gap-5">
              <!-- First Name -->
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">First Name</label>
                <input 
                  type="text" 
                  value="John"
                  class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                >
              </div>

              <!-- Last Name -->
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Last Name</label>
                <input 
                  type="text" 
                  value="Doe"
                  class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                >
              </div>

              <!-- Email -->
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                <input 
                  type="email" 
                  value="john.doe@email.com"
                  class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                >
              </div>

              <!-- Phone -->
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Phone Number</label>
                <input 
                  type="tel" 
                  value="+1 234 567 890"
                  class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                >
              </div>

              <!-- Role -->
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Role</label>
                <select class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                  <option value="admin" selected>Admin</option>
                  <option value="manager">Manager</option>
                  <option value="staff">Staff</option>
                  <option value="customer">Customer</option>
                </select>
              </div>

              <!-- Status -->
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                <select class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                  <option value="active" selected>Active</option>
                  <option value="inactive">Inactive</option>
                  <option value="suspended">Suspended</option>
                </select>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-100">
              <button class="px-4 py-2 text-red-500 hover:bg-red-50 rounded-lg font-medium">
                <i class="fa-solid fa-trash mr-2"></i> Delete User
              </button>
              <div class="flex gap-3">
                <router-link to="/admin/users" class="px-6 py-3 border border-gray-200 rounded-lg font-medium text-slate-600 hover:bg-gray-50">
                  Cancel
                </router-link>
                <button class="px-6 py-3 bg-orange-500 text-white rounded-lg font-medium hover:bg-orange-600">
                  <i class="fa-solid fa-save mr-2"></i> Save Changes
                </button>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>
