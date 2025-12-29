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
      <AdminHeader title="SETTINGS" @toggle-sidebar="sidebarOpen = true" >
          <!-- Search - Hidden on mobile -->
        <div class="relative hidden md:block">
          <input type="text" placeholder="Search..." class="w-32 lg:w-48 pl-9 pr-3 py-2 bg-gray-100 rounded-lg text-sm">
          <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
        </div>
      </AdminHeader>

      <main class="p-3 sm:p-4 md:p-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
          <h2 class="text-xl font-bold text-slate-800 mb-4">General Settings</h2>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Site Name</label>
              <input type="text" value="LapakBaju" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Site Description</label>
              <textarea rows="3" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">The best place to buy clothes online.</textarea>
            </div>
            <div class="flex items-center gap-2">
               <input type="checkbox" id="maintenance" class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
               <label for="maintenance" class="text-sm text-slate-700">Maintenance Mode</label>
            </div>
            <button class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">Save Changes</button>
          </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
          <h2 class="text-xl font-bold text-slate-800 mb-4">Contact Information</h2>
          <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Call Us</label>
                <div class="relative">
                  <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <i class="fa-solid fa-phone"></i>
                  </span>
                  <input type="text" placeholder="+1 (555) 000-0000" class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email Us</label>
                <div class="relative">
                  <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <i class="fa-solid fa-envelope"></i>
                  </span>
                  <input type="email" placeholder="support@lapakbaju.com" class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Visit Us</label>
              <div class="relative">
                <span class="absolute left-4 top-3 text-slate-400">
                  <i class="fa-solid fa-location-dot"></i>
                </span>
                <textarea rows="3" placeholder="123 Fashion Street, New York, NY 10001" class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
              </div>
            </div>
            <button class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">Save Changes</button>
          </div>
        </div>

        <!-- Social Media Links -->
        <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
          <h2 class="text-xl font-bold text-slate-800 mb-4">Social Media Links</h2>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Instagram</label>
              <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                  <i class="fa-brands fa-instagram"></i>
                </span>
                <input type="text" placeholder="https://instagram.com/lapakbaju" class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Facebook</label>
              <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                  <i class="fa-brands fa-facebook-f"></i>
                </span>
                <input type="text" placeholder="https://facebook.com/lapakbaju" class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">X (Twitter)</label>
              <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                  <i class="fa-brands fa-x-twitter"></i>
                </span>
                <input type="text" placeholder="https://x.com/lapakbaju" class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
              </div>
            </div>
            <button class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">Save Changes</button>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>
