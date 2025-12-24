<script setup>
import { ref } from 'vue'
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
        
        <!-- Users - Active -->
        <router-link to="/admin/users" class="flex items-center gap-3 px-5 py-3 bg-orange-500/10 text-orange-500 border-l-4 border-orange-500">
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
        <h1 class="text-base md:text-lg font-bold text-slate-800">USER MANAGEMENT</h1>
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
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 md:mb-6">
          <div>
            <h2 class="text-lg md:text-2xl font-bold text-slate-800">User List</h2>
            <p class="text-xs md:text-sm text-slate-500 mt-1">Manage all registered users</p>
          </div>
        </div>

        <!-- Mobile Cards View -->
        <div class="md:hidden space-y-3">
          <!-- User Card 1 -->
          <div class="bg-white rounded-xl p-4 shadow-sm">
            <div class="flex items-start justify-between mb-3">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-blue-500 overflow-hidden flex-shrink-0">
                  <img src="https://ui-avatars.com/api/?name=John+Doe&background=3B82F6&color=fff" alt="John Doe">
                </div>
                <div>
                  <p class="font-semibold text-slate-800">John Doe</p>
                  <p class="text-xs text-slate-400">Joined Dec 2024</p>
                </div>
              </div>
              <span class="px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-600">Admin</span>
            </div>
            <div class="space-y-2 text-sm">
              <div class="flex items-center gap-2 text-slate-600">
                <i class="fa-solid fa-envelope w-4 text-slate-400 text-xs"></i>
                <span class="truncate">john.doe@email.com</span>
              </div>
              <div class="flex items-center gap-2 text-slate-600">
                <i class="fa-solid fa-phone w-4 text-slate-400 text-xs"></i>
                <span>+1 234 567 890</span>
              </div>
            </div>
            <div class="flex items-center justify-end gap-2 mt-3 pt-3 border-t border-gray-100">
              <router-link to="/admin/users/edit" class="px-3 py-2 rounded-lg border border-gray-200 text-blue-500 hover:bg-blue-50 flex items-center gap-1 text-sm">
                <i class="fa-solid fa-pen text-xs"></i>
                <span>Edit</span>
              </router-link>
              <button class="px-3 py-2 rounded-lg border border-gray-200 text-red-500 hover:bg-red-50 flex items-center gap-1 text-sm">
                <i class="fa-solid fa-trash text-xs"></i>
                <span>Delete</span>
              </button>
            </div>
          </div>

          <!-- User Card 2 -->
          <div class="bg-white rounded-xl p-4 shadow-sm">
            <div class="flex items-start justify-between mb-3">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-pink-500 overflow-hidden flex-shrink-0">
                  <img src="https://ui-avatars.com/api/?name=Sarah+Wilson&background=EC4899&color=fff" alt="Sarah Wilson">
                </div>
                <div>
                  <p class="font-semibold text-slate-800">Sarah Wilson</p>
                  <p class="text-xs text-slate-400">Joined Nov 2024</p>
                </div>
              </div>
              <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-600">Customer</span>
            </div>
            <div class="space-y-2 text-sm">
              <div class="flex items-center gap-2 text-slate-600">
                <i class="fa-solid fa-envelope w-4 text-slate-400 text-xs"></i>
                <span class="truncate">sarah.wilson@email.com</span>
              </div>
              <div class="flex items-center gap-2 text-slate-600">
                <i class="fa-solid fa-phone w-4 text-slate-400 text-xs"></i>
                <span>+1 345 678 901</span>
              </div>
            </div>
            <div class="flex items-center justify-end gap-2 mt-3 pt-3 border-t border-gray-100">
              <router-link to="/admin/users/edit" class="px-3 py-2 rounded-lg border border-gray-200 text-blue-500 hover:bg-blue-50 flex items-center gap-1 text-sm">
                <i class="fa-solid fa-pen text-xs"></i>
                <span>Edit</span>
              </router-link>
              <button class="px-3 py-2 rounded-lg border border-gray-200 text-red-500 hover:bg-red-50 flex items-center gap-1 text-sm">
                <i class="fa-solid fa-trash text-xs"></i>
                <span>Delete</span>
              </button>
            </div>
          </div>

          <!-- User Card 3 -->
          <div class="bg-white rounded-xl p-4 shadow-sm">
            <div class="flex items-start justify-between mb-3">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-teal-500 overflow-hidden flex-shrink-0">
                  <img src="https://ui-avatars.com/api/?name=Mike+Johnson&background=14B8A6&color=fff" alt="Mike Johnson">
                </div>
                <div>
                  <p class="font-semibold text-slate-800">Mike Johnson</p>
                  <p class="text-xs text-slate-400">Joined Oct 2024</p>
                </div>
              </div>
              <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-600">Manager</span>
            </div>
            <div class="space-y-2 text-sm">
              <div class="flex items-center gap-2 text-slate-600">
                <i class="fa-solid fa-envelope w-4 text-slate-400 text-xs"></i>
                <span class="truncate">mike.johnson@email.com</span>
              </div>
              <div class="flex items-center gap-2 text-slate-600">
                <i class="fa-solid fa-phone w-4 text-slate-400 text-xs"></i>
                <span>+1 456 789 012</span>
              </div>
            </div>
            <div class="flex items-center justify-end gap-2 mt-3 pt-3 border-t border-gray-100">
              <router-link to="/admin/users/edit" class="px-3 py-2 rounded-lg border border-gray-200 text-blue-500 hover:bg-blue-50 flex items-center gap-1 text-sm">
                <i class="fa-solid fa-pen text-xs"></i>
                <span>Edit</span>
              </router-link>
              <button class="px-3 py-2 rounded-lg border border-gray-200 text-red-500 hover:bg-red-50 flex items-center gap-1 text-sm">
                <i class="fa-solid fa-trash text-xs"></i>
                <span>Delete</span>
              </button>
            </div>
          </div>

          <!-- User Card 4 -->
          <div class="bg-white rounded-xl p-4 shadow-sm">
            <div class="flex items-start justify-between mb-3">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-orange-500 overflow-hidden flex-shrink-0">
                  <img src="https://ui-avatars.com/api/?name=Emily+Brown&background=F97316&color=fff" alt="Emily Brown">
                </div>
                <div>
                  <p class="font-semibold text-slate-800">Emily Brown</p>
                  <p class="text-xs text-slate-400">Joined Sep 2024</p>
                </div>
              </div>
              <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-600">Customer</span>
            </div>
            <div class="space-y-2 text-sm">
              <div class="flex items-center gap-2 text-slate-600">
                <i class="fa-solid fa-envelope w-4 text-slate-400 text-xs"></i>
                <span class="truncate">emily.brown@email.com</span>
              </div>
              <div class="flex items-center gap-2 text-slate-600">
                <i class="fa-solid fa-phone w-4 text-slate-400 text-xs"></i>
                <span>+1 567 890 123</span>
              </div>
            </div>
            <div class="flex items-center justify-end gap-2 mt-3 pt-3 border-t border-gray-100">
              <router-link to="/admin/users/edit" class="px-3 py-2 rounded-lg border border-gray-200 text-blue-500 hover:bg-blue-50 flex items-center gap-1 text-sm">
                <i class="fa-solid fa-pen text-xs"></i>
                <span>Edit</span>
              </router-link>
              <button class="px-3 py-2 rounded-lg border border-gray-200 text-red-500 hover:bg-red-50 flex items-center gap-1 text-sm">
                <i class="fa-solid fa-trash text-xs"></i>
                <span>Delete</span>
              </button>
            </div>
          </div>

          <!-- Mobile Pagination -->
          <div class="bg-white rounded-xl p-4 shadow-sm flex items-center justify-between">
            <p class="text-xs text-slate-500">Showing 1-4 of 24</p>
            <div class="flex items-center gap-1">
              <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">
                <i class="fa-solid fa-chevron-left text-xs"></i>
              </button>
              <button class="w-8 h-8 rounded-lg bg-orange-500 text-white flex items-center justify-center text-sm">1</button>
              <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100 text-sm">2</button>
              <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">
                <i class="fa-solid fa-chevron-right text-xs"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block bg-white rounded-xl shadow-sm overflow-hidden">
          <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="px-6 py-4 text-left">
                  <input type="checkbox" class="w-4 h-4 rounded border-gray-300">
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">User</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Email</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Phone</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Role</th>
                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase">Action</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <!-- User 1 -->
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                  <input type="checkbox" class="w-4 h-4 rounded border-gray-300">
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-500 overflow-hidden flex-shrink-0">
                      <img src="https://ui-avatars.com/api/?name=John+Doe&background=3B82F6&color=fff" alt="John Doe">
                    </div>
                    <div>
                      <p class="font-medium text-slate-800">John Doe</p>
                      <p class="text-xs text-slate-400">Dec 2024</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">john.doe@email.com</td>
                <td class="px-6 py-4 text-sm text-slate-600">+1 234 567 890</td>
                <td class="px-6 py-4">
                  <span class="px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-600">Admin</span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-center gap-2">
                    <router-link to="/admin/users/edit" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-blue-500 hover:bg-blue-50">
                      <i class="fa-solid fa-pen text-sm"></i>
                    </router-link>
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-red-500 hover:bg-red-50">
                      <i class="fa-solid fa-trash text-sm"></i>
                    </button>
                  </div>
                </td>
              </tr>

              <!-- User 2 -->
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                  <input type="checkbox" class="w-4 h-4 rounded border-gray-300">
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-pink-500 overflow-hidden">
                      <img src="https://ui-avatars.com/api/?name=Sarah+Wilson&background=EC4899&color=fff" alt="Sarah Wilson">
                    </div>
                    <div>
                      <p class="font-medium text-slate-800">Sarah Wilson</p>
                      <p class="text-xs text-slate-400">Nov 2024</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">sarah.wilson@email.com</td>
                <td class="px-6 py-4 text-sm text-slate-600">+1 345 678 901</td>
                <td class="px-6 py-4">
                  <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-600">Customer</span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-center gap-2">
                    <router-link to="/admin/users/edit" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-blue-500 hover:bg-blue-50">
                      <i class="fa-solid fa-pen text-sm"></i>
                    </router-link>
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-red-500 hover:bg-red-50">
                      <i class="fa-solid fa-trash text-sm"></i>
                    </button>
                  </div>
                </td>
              </tr>

              <!-- User 3 -->
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                  <input type="checkbox" class="w-4 h-4 rounded border-gray-300">
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-teal-500 overflow-hidden">
                      <img src="https://ui-avatars.com/api/?name=Mike+Johnson&background=14B8A6&color=fff" alt="Mike Johnson">
                    </div>
                    <div>
                      <p class="font-medium text-slate-800">Mike Johnson</p>
                      <p class="text-xs text-slate-400">Oct 2024</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">mike.johnson@email.com</td>
                <td class="px-6 py-4 text-sm text-slate-600">+1 456 789 012</td>
                <td class="px-6 py-4">
                  <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-600">Manager</span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-center gap-2">
                    <router-link to="/admin/users/edit" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-blue-500 hover:bg-blue-50">
                      <i class="fa-solid fa-pen text-sm"></i>
                    </router-link>
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-red-500 hover:bg-red-50">
                      <i class="fa-solid fa-trash text-sm"></i>
                    </button>
                  </div>
                </td>
              </tr>

              <!-- User 4 -->
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                  <input type="checkbox" class="w-4 h-4 rounded border-gray-300">
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-orange-500 overflow-hidden">
                      <img src="https://ui-avatars.com/api/?name=Emily+Brown&background=F97316&color=fff" alt="Emily Brown">
                    </div>
                    <div>
                      <p class="font-medium text-slate-800">Emily Brown</p>
                      <p class="text-xs text-slate-400">Sep 2024</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">emily.brown@email.com</td>
                <td class="px-6 py-4 text-sm text-slate-600">+1 567 890 123</td>
                <td class="px-6 py-4">
                  <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-600">Customer</span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-center gap-2">
                    <router-link to="/admin/users/edit" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-blue-500 hover:bg-blue-50">
                      <i class="fa-solid fa-pen text-sm"></i>
                    </router-link>
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-red-500 hover:bg-red-50">
                      <i class="fa-solid fa-trash text-sm"></i>
                    </button>
                  </div>
                </td>
              </tr>

              <!-- User 5 -->
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                  <input type="checkbox" class="w-4 h-4 rounded border-gray-300">
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-500 overflow-hidden">
                      <img src="https://ui-avatars.com/api/?name=David+Lee&background=6366F1&color=fff" alt="David Lee">
                    </div>
                    <div>
                      <p class="font-medium text-slate-800">David Lee</p>
                      <p class="text-xs text-slate-400">Aug 2024</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">david.lee@email.com</td>
                <td class="px-6 py-4 text-sm text-slate-600">+1 678 901 234</td>
                <td class="px-6 py-4">
                  <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-600">Customer</span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-center gap-2">
                    <router-link to="/admin/users/edit" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-blue-500 hover:bg-blue-50">
                      <i class="fa-solid fa-pen text-sm"></i>
                    </router-link>
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-red-500 hover:bg-red-50">
                      <i class="fa-solid fa-trash text-sm"></i>
                    </button>
                  </div>
                </td>
              </tr>

              <!-- User 6 -->
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                  <input type="checkbox" class="w-4 h-4 rounded border-gray-300">
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-red-500 overflow-hidden">
                      <img src="https://ui-avatars.com/api/?name=Anna+Smith&background=EF4444&color=fff" alt="Anna Smith">
                    </div>
                    <div>
                      <p class="font-medium text-slate-800">Anna Smith</p>
                      <p class="text-xs text-slate-400">Jul 2024</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">anna.smith@email.com</td>
                <td class="px-6 py-4 text-sm text-slate-600">+1 789 012 345</td>
                <td class="px-6 py-4">
                  <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-600">Staff</span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-center gap-2">
                    <router-link to="/admin/users/edit" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-blue-500 hover:bg-blue-50">
                      <i class="fa-solid fa-pen text-sm"></i>
                    </router-link>
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-red-500 hover:bg-red-50">
                      <i class="fa-solid fa-trash text-sm"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Desktop Pagination -->
          <div class="px-6 py-4 flex items-center justify-between border-t border-gray-100">
            <p class="text-sm text-slate-500">Showing 1 to 6 of 24 users</p>
            <div class="flex items-center gap-2">
              <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">
                <i class="fa-solid fa-chevron-left text-xs"></i>
              </button>
              <button class="w-8 h-8 rounded-lg bg-orange-500 text-white flex items-center justify-center">1</button>
              <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">2</button>
              <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">3</button>
              <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">4</button>
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
