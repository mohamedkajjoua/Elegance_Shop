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
      <AdminHeader title="ORDERS LIST" @toggle-sidebar="sidebarOpen = true">
        <div class="relative hidden md:block">
          <input type="text" placeholder="Search..." class="w-32 lg:w-48 pl-10 pr-4 py-2 bg-gray-100 rounded-lg text-sm">
          <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
      </AdminHeader>

      <main class="p-3 md:p-6">
        <!-- Stats Cards - Show 4 on mobile, 8 on desktop -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 md:gap-4 mb-4 md:mb-6">
          <!-- Payment Refund -->
          <div class="bg-white rounded-lg md:rounded-xl p-2.5 md:p-5">
            <div class="flex items-center gap-2 md:gap-3">
              <div class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-orange-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-dollar-sign text-orange-500 text-sm md:text-lg"></i>
              </div>
              <div class="min-w-0">
                <p class="text-[10px] md:text-sm text-slate-500 truncate">Refund</p>
                <p class="text-base md:text-2xl font-bold text-slate-800">490</p>
              </div>
            </div>
          </div>

          <!-- Order Cancel -->
          <div class="bg-white rounded-lg md:rounded-xl p-2.5 md:p-5">
            <div class="flex items-center gap-2 md:gap-3">
              <div class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-xmark text-red-500 text-sm md:text-lg"></i>
              </div>
              <div class="min-w-0">
                <p class="text-[10px] md:text-sm text-slate-500 truncate">Cancel</p>
                <p class="text-base md:text-2xl font-bold text-slate-800">241</p>
              </div>
            </div>
          </div>

          <!-- Order Shipped -->
          <div class="bg-white rounded-lg md:rounded-xl p-2.5 md:p-5">
            <div class="flex items-center gap-2 md:gap-3">
              <div class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-truck text-green-500 text-sm md:text-lg"></i>
              </div>
              <div class="min-w-0">
                <p class="text-[10px] md:text-sm text-slate-500 truncate">Shipped</p>
                <p class="text-base md:text-2xl font-bold text-slate-800">630</p>
              </div>
            </div>
          </div>

          <!-- Delivered -->
          <div class="bg-white rounded-lg md:rounded-xl p-2.5 md:p-5">
            <div class="flex items-center gap-2 md:gap-3">
              <div class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-teal-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-check text-teal-500 text-sm md:text-lg"></i>
              </div>
              <div class="min-w-0">
                <p class="text-[10px] md:text-sm text-slate-500 truncate">Delivered</p>
                <p class="text-base md:text-2xl font-bold text-slate-800">200</p>
              </div>
            </div>
          </div>

          <!-- Hidden on mobile, visible on sm+ -->
          <!-- Delivering -->
          <div class="hidden sm:block bg-white rounded-lg md:rounded-xl p-2.5 md:p-5">
            <div class="flex items-center gap-2 md:gap-3">
              <div class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-box text-blue-500 text-sm md:text-lg"></i>
              </div>
              <div class="min-w-0">
                <p class="text-[10px] md:text-sm text-slate-500 truncate">Delivering</p>
                <p class="text-base md:text-2xl font-bold text-slate-800">170</p>
              </div>
            </div>
          </div>

          <!-- Pending -->
          <div class="hidden sm:block bg-white rounded-lg md:rounded-xl p-2.5 md:p-5">
            <div class="flex items-center gap-2 md:gap-3">
              <div class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-clock text-amber-500 text-sm md:text-lg"></i>
              </div>
              <div class="min-w-0">
                <p class="text-[10px] md:text-sm text-slate-500 truncate">Pending</p>
                <p class="text-base md:text-2xl font-bold text-slate-800">210</p>
              </div>
            </div>
          </div>

          <!-- Payment -->
          <div class="hidden sm:block bg-white rounded-lg md:rounded-xl p-2.5 md:p-5">
            <div class="flex items-center gap-2 md:gap-3">
              <div class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-credit-card text-purple-500 text-sm md:text-lg"></i>
              </div>
              <div class="min-w-0">
                <p class="text-[10px] md:text-sm text-slate-500 truncate">Payment</p>
                <p class="text-base md:text-2xl font-bold text-slate-800">608</p>
              </div>
            </div>
          </div>

          <!-- In Progress -->
          <div class="hidden sm:block bg-white rounded-lg md:rounded-xl p-2.5 md:p-5">
            <div class="flex items-center gap-2 md:gap-3">
              <div class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-spinner text-indigo-500 text-sm md:text-lg"></i>
              </div>
              <div class="min-w-0">
                <p class="text-[10px] md:text-sm text-slate-500 truncate">Progress</p>
                <p class="text-base md:text-2xl font-bold text-slate-800">656</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Orders Table -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
          <h2 class="text-lg md:text-xl font-semibold text-slate-800">All Order List</h2>
          <select class="px-3 md:px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm">
            <option>This Month</option>
            <option>Last Month</option>
          </select>
        </div>

        <!-- Mobile Orders Cards -->
        <div class="md:hidden space-y-3">
          <!-- Order Card 1 -->
          <div class="bg-white rounded-xl p-4 shadow-sm">
            <div class="flex items-start justify-between mb-3">
              <div>
                <p class="font-semibold text-slate-800">#583488/80</p>
                <p class="text-xs text-slate-400">Apr 23, 2024</p>
              </div>
              <span class="px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-600">Unpaid</span>
            </div>
            <div class="space-y-2 text-sm mb-3">
              <div class="flex items-center justify-between">
                <span class="text-slate-500">Customer</span>
                <span class="text-orange-500 font-medium">Gail C. Anderson</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-slate-500">Total</span>
                <span class="font-bold text-slate-800">$1,240.00</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-slate-500">Items</span>
                <span class="text-slate-600">4 items</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-slate-500">Status</span>
                <span class="px-2 py-1 rounded border text-xs font-medium border-slate-300 text-slate-600">Draft</span>
              </div>
            </div>
            <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
              <router-link to="/admin/orders/1" class="px-3 py-2 rounded-lg border border-gray-200 text-slate-500 hover:bg-gray-50 flex items-center gap-1 text-sm">
                <i class="fa-solid fa-eye text-xs"></i>
                <span>View</span>
              </router-link>
              <button class="px-3 py-2 rounded-lg border border-gray-200 text-blue-500 hover:bg-blue-50 flex items-center gap-1 text-sm">
                <i class="fa-solid fa-pen text-xs"></i>
                <span>Edit</span>
              </button>
            </div>
          </div>

          <!-- Order Card 2 -->
          <div class="bg-white rounded-xl p-4 shadow-sm">
            <div class="flex items-start justify-between mb-3">
              <div>
                <p class="font-semibold text-slate-800">#456754/80</p>
                <p class="text-xs text-slate-400">Apr 20, 2024</p>
              </div>
              <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-600">Paid</span>
            </div>
            <div class="space-y-2 text-sm mb-3">
              <div class="flex items-center justify-between">
                <span class="text-slate-500">Customer</span>
                <span class="text-orange-500 font-medium">Jung S. Ayala</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-slate-500">Total</span>
                <span class="font-bold text-slate-800">$987.00</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-slate-500">Items</span>
                <span class="text-slate-600">2 items</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-slate-500">Status</span>
                <span class="px-2 py-1 rounded border text-xs font-medium border-blue-300 text-blue-600 bg-blue-50">Packaging</span>
              </div>
            </div>
            <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
              <router-link to="/admin/orders/2" class="px-3 py-2 rounded-lg border border-gray-200 text-slate-500 hover:bg-gray-50 flex items-center gap-1 text-sm">
                <i class="fa-solid fa-eye text-xs"></i>
                <span>View</span>
              </router-link>
              <button class="px-3 py-2 rounded-lg border border-gray-200 text-blue-500 hover:bg-blue-50 flex items-center gap-1 text-sm">
                <i class="fa-solid fa-pen text-xs"></i>
                <span>Edit</span>
              </button>
            </div>
          </div>

          <!-- Order Card 3 -->
          <div class="bg-white rounded-xl p-4 shadow-sm">
            <div class="flex items-start justify-between mb-3">
              <div>
                <p class="font-semibold text-slate-800">#789123/45</p>
                <p class="text-xs text-slate-400">Apr 18, 2024</p>
              </div>
              <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-600">Paid</span>
            </div>
            <div class="space-y-2 text-sm mb-3">
              <div class="flex items-center justify-between">
                <span class="text-slate-500">Customer</span>
                <span class="text-orange-500 font-medium">Maria L. Santos</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-slate-500">Total</span>
                <span class="font-bold text-slate-800">$2,150.00</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-slate-500">Items</span>
                <span class="text-slate-600">6 items</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-slate-500">Priority</span>
                <span class="text-red-500 font-medium">Urgent</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-slate-500">Status</span>
                <span class="px-2 py-1 rounded border text-xs font-medium border-green-300 text-green-600 bg-green-50">Shipped</span>
              </div>
            </div>
            <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
              <router-link to="/admin/orders/3" class="px-3 py-2 rounded-lg border border-gray-200 text-slate-500 hover:bg-gray-50 flex items-center gap-1 text-sm">
                <i class="fa-solid fa-eye text-xs"></i>
                <span>View</span>
              </router-link>
              <button class="px-3 py-2 rounded-lg border border-gray-200 text-blue-500 hover:bg-blue-50 flex items-center gap-1 text-sm">
                <i class="fa-solid fa-pen text-xs"></i>
                <span>Edit</span>
              </button>
            </div>
          </div>

          <!-- Order Card 4 -->
          <div class="bg-white rounded-xl p-4 shadow-sm">
            <div class="flex items-start justify-between mb-3">
              <div>
                <p class="font-semibold text-slate-800">#321654/78</p>
                <p class="text-xs text-slate-400">Apr 15, 2024</p>
              </div>
              <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-600">Paid</span>
            </div>
            <div class="space-y-2 text-sm mb-3">
              <div class="flex items-center justify-between">
                <span class="text-slate-500">Customer</span>
                <span class="text-orange-500 font-medium">Robert K. Brown</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-slate-500">Total</span>
                <span class="font-bold text-slate-800">$654.00</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-slate-500">Items</span>
                <span class="text-slate-600">3 items</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-slate-500">Status</span>
                <span class="px-2 py-1 rounded border text-xs font-medium border-teal-300 text-teal-600 bg-teal-50">Delivered</span>
              </div>
            </div>
            <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
              <router-link to="/admin/orders/4" class="px-3 py-2 rounded-lg border border-gray-200 text-slate-500 hover:bg-gray-50 flex items-center gap-1 text-sm">
                <i class="fa-solid fa-eye text-xs"></i>
                <span>View</span>
              </router-link>
              <button class="px-3 py-2 rounded-lg border border-gray-200 text-blue-500 hover:bg-blue-50 flex items-center gap-1 text-sm">
                <i class="fa-solid fa-pen text-xs"></i>
                <span>Edit</span>
              </button>
            </div>
          </div>

          <!-- Mobile Pagination -->
          <div class="bg-white rounded-xl p-4 shadow-sm flex items-center justify-between">
            <p class="text-xs text-slate-500">1-4 of 128</p>
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

        <!-- Desktop Orders Table -->
        <div class="hidden md:block bg-white rounded-xl shadow-sm overflow-hidden">
          <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="px-4 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Order ID</th>
                <th class="px-4 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Created at</th>
                <th class="px-4 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Customer</th>
                <th class="px-4 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Priority</th>
                <th class="px-4 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Total</th>
                <th class="px-4 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Payment Status</th>
                <th class="px-4 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Items</th>
                <th class="px-4 py-4 text-left text-xs font-semibold text-slate-600 uppercase">Order Status</th>
                <th class="px-4 py-4 text-center text-xs font-semibold text-slate-600 uppercase">Action</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-4 text-sm text-slate-600">#583488/80</td>
                <td class="px-4 py-4 text-sm text-slate-600">Apr 23, 2024</td>
                <td class="px-4 py-4 text-sm text-orange-500 font-medium">Gail C. Anderson</td>
                <td class="px-4 py-4 text-sm text-slate-600">Normal</td>
                <td class="px-4 py-4 text-sm font-medium text-slate-800">$1,240.00</td>
                <td class="px-4 py-4">
                  <span class="px-3 py-1 rounded text-xs font-medium bg-red-100 text-red-600">Unpaid</span>
                </td>
                <td class="px-4 py-4 text-sm text-slate-600">4</td>
                <td class="px-4 py-4">
                  <span class="px-3 py-1 rounded border text-xs font-medium border-slate-300 text-slate-600">Draft</span>
                </td>
                <td class="px-4 py-4">
                  <div class="flex items-center justify-center gap-2">
                    <router-link to="/admin/orders/1" class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">
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

              <tr class="hover:bg-gray-50">
                <td class="px-4 py-4 text-sm text-slate-600">#456754/80</td>
                <td class="px-4 py-4 text-sm text-slate-600">Apr 20, 2024</td>
                <td class="px-4 py-4 text-sm text-orange-500 font-medium">Jung S. Ayala</td>
                <td class="px-4 py-4 text-sm text-slate-600">Normal</td>
                <td class="px-4 py-4 text-sm font-medium text-slate-800">$987.00</td>
                <td class="px-4 py-4">
                  <span class="px-3 py-1 rounded text-xs font-medium bg-green-100 text-green-600">Paid</span>
                </td>
                <td class="px-4 py-4 text-sm text-slate-600">2</td>
                <td class="px-4 py-4">
                  <span class="px-3 py-1 rounded border text-xs font-medium border-blue-300 text-blue-600 bg-blue-50">Packaging</span>
                </td>
                <td class="px-4 py-4">
                  <div class="flex items-center justify-center gap-2">
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">
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

              <tr class="hover:bg-gray-50">
                <td class="px-4 py-4 text-sm text-slate-600">#789123/45</td>
                <td class="px-4 py-4 text-sm text-slate-600">Apr 18, 2024</td>
                <td class="px-4 py-4 text-sm text-orange-500 font-medium">Maria L. Santos</td>
                <td class="px-4 py-4 text-sm text-red-500 font-medium">Urgent</td>
                <td class="px-4 py-4 text-sm font-medium text-slate-800">$2,150.00</td>
                <td class="px-4 py-4">
                  <span class="px-3 py-1 rounded text-xs font-medium bg-green-100 text-green-600">Paid</span>
                </td>
                <td class="px-4 py-4 text-sm text-slate-600">6</td>
                <td class="px-4 py-4">
                  <span class="px-3 py-1 rounded border text-xs font-medium border-green-300 text-green-600 bg-green-50">Shipped</span>
                </td>
                <td class="px-4 py-4">
                  <div class="flex items-center justify-center gap-2">
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">
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

              <tr class="hover:bg-gray-50">
                <td class="px-4 py-4 text-sm text-slate-600">#321654/78</td>
                <td class="px-4 py-4 text-sm text-slate-600">Apr 15, 2024</td>
                <td class="px-4 py-4 text-sm text-orange-500 font-medium">Robert K. Brown</td>
                <td class="px-4 py-4 text-sm text-slate-600">Normal</td>
                <td class="px-4 py-4 text-sm font-medium text-slate-800">$654.00</td>
                <td class="px-4 py-4">
                  <span class="px-3 py-1 rounded text-xs font-medium bg-green-100 text-green-600">Paid</span>
                </td>
                <td class="px-4 py-4 text-sm text-slate-600">3</td>
                <td class="px-4 py-4">
                  <span class="px-3 py-1 rounded border text-xs font-medium border-teal-300 text-teal-600 bg-teal-50">Delivered</span>
                </td>
                <td class="px-4 py-4">
                  <div class="flex items-center justify-center gap-2">
                    <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">
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

          <!-- Desktop Pagination -->
          <div class="px-6 py-4 flex items-center justify-between border-t border-gray-100">
            <p class="text-sm text-slate-500">Showing 1 to 4 of 128 entries</p>
            <div class="flex items-center gap-2">
              <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">
                <i class="fa-solid fa-chevron-left text-xs"></i>
              </button>
              <button class="w-8 h-8 rounded-lg bg-orange-500 text-white flex items-center justify-center">1</button>
              <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">2</button>
              <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">3</button>
              <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">...</button>
              <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100">32</button>
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
