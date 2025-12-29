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
      <AdminHeader title="EDIT USER" @toggle-sidebar="sidebarOpen = true">
        <div class="relative hidden md:block">
          <input type="text" placeholder="Search..." class="w-32 lg:w-48 pl-10 pr-4 py-2 bg-gray-100 rounded-lg text-sm">
          <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
      </AdminHeader>

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
