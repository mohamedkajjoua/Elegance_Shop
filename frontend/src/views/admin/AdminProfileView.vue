<script setup>
import { ref, reactive } from 'vue'

// Toggle state for submenu
const productsOpen = ref(false)
// Mobile sidebar toggle
const sidebarOpen = ref(false)
// Edit mode
const isEditing = ref(false)

// Admin profile data
const profile = reactive({
  firstName: 'John',
  lastName: 'Admin',
  email: 'admin@lapakbaju.com',
  phone: '+1 (555) 123-4567',
  role: 'Super Admin',
  department: 'Management',
  avatar: 'https://ui-avatars.com/api/?name=John+Admin&background=F97316&color=fff&size=200',
  bio: 'Experienced e-commerce administrator with over 5 years of experience managing online retail platforms.',
  address: {
    street: '123 Admin Street',
    city: 'San Francisco',
    state: 'California',
    zip: '94102',
    country: 'United States'
  },
  social: {
    facebook: 'john.admin',
    twitter: '@johnadmin',
    linkedin: 'john-admin'
  },
  notifications: {
    email: true,
    push: true,
    sms: false,
    orderAlerts: true,
    stockAlerts: true,
    newsletter: false
  },
  joinedAt: 'January 15, 2023',
  lastLogin: 'December 22, 2024 - 2:30 PM'
})

// Editable copy of profile (deep copy for nested objects)
const editForm = reactive({
  ...profile,
  address: { ...profile.address },
  social: { ...profile.social },
  notifications: { ...profile.notifications }
})

// Toggle edit mode
const toggleEdit = () => {
  if (isEditing.value) {
    // Save changes - deep copy address back
    profile.firstName = editForm.firstName
    profile.lastName = editForm.lastName
    profile.email = editForm.email
    profile.phone = editForm.phone
    profile.bio = editForm.bio
    profile.address.street = editForm.address.street
    profile.address.city = editForm.address.city
    profile.address.state = editForm.address.state
    profile.address.zip = editForm.address.zip
    profile.address.country = editForm.address.country
    alert('Profile updated successfully!')
  }
  isEditing.value = !isEditing.value
}

// Cancel editing
const cancelEdit = () => {
  editForm.firstName = profile.firstName
  editForm.lastName = profile.lastName
  editForm.email = profile.email
  editForm.phone = profile.phone
  editForm.bio = profile.bio
  editForm.address.street = profile.address.street
  editForm.address.city = profile.address.city
  editForm.address.state = profile.address.state
  editForm.address.zip = profile.address.zip
  editForm.address.country = profile.address.country
  isEditing.value = false
}

// Change password modal
const showPasswordModal = ref(false)
const passwordForm = reactive({
  current: '',
  new: '',
  confirm: ''
})

const changePassword = () => {
  if (passwordForm.new !== passwordForm.confirm) {
    alert('New passwords do not match!')
    return
  }
  alert('Password changed successfully!')
  showPasswordModal.value = false
  passwordForm.current = ''
  passwordForm.new = ''
  passwordForm.confirm = ''
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
      <!-- Logo -->
      <div class="p-5 flex items-center gap-3 border-b border-slate-700">
        <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
          <i class="fa-solid fa-bag-shopping text-white"></i>
        </div>
        <span class="text-xl font-bold">LapakBaju</span>
        <button @click="sidebarOpen = false" class="ml-auto text-slate-400 hover:text-white lg:hidden">
          <i class="fa-solid fa-times"></i>
        </button>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 overflow-y-auto py-4">
        <p class="px-5 text-xs text-slate-500 uppercase tracking-wider mb-3">General</p>
        
        <router-link to="/admin" class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
          <i class="fa-solid fa-grid-2 w-5"></i>
          <span>Dashboard</span>
        </router-link>

        <!-- Products -->
        <div class="relative">
          <button @click="productsOpen = !productsOpen" class="w-full flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
            <i class="fa-solid fa-cube w-5"></i>
            <span>Products</span>
            <i :class="['fa-solid ml-auto text-xs transition-transform', productsOpen ? 'fa-chevron-up' : 'fa-chevron-down']"></i>
          </button>
          <div v-show="productsOpen" class="bg-slate-900/50 py-2">
            <router-link to="/admin/products" class="block px-12 py-2 text-sm text-slate-300 hover:text-orange-500">Product List</router-link>
            <router-link to="/admin/products/create" class="block px-12 py-2 text-sm text-slate-300 hover:text-orange-500">Create Product</router-link>
          </div>
        </div>

        <!-- Category -->
        <router-link to="/admin/category" class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
          <i class="fa-solid fa-layer-group w-5"></i>
          <span>Category</span>
        </router-link>

        <!-- Brand -->
        <router-link to="/admin/brand" class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
          <i class="fa-solid fa-tag w-5"></i>
          <span>Brand</span>
        </router-link>

        <!-- Orders -->
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
        
        <!-- Profile - Active -->
        <router-link to="/admin/profile" class="flex items-center gap-3 px-5 py-3 bg-orange-500/10 text-orange-500 border-l-4 border-orange-500">
          <i class="fa-solid fa-user w-5"></i>
          <span>Profile</span>
        </router-link>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64">
      <!-- Header -->
      <header class="bg-white h-16 flex items-center justify-between px-4 md:px-6 sticky top-0 z-10 shadow-sm">
        <button @click="sidebarOpen = true" class="lg:hidden w-10 h-10 rounded-full flex items-center justify-center text-slate-500 hover:bg-gray-100">
          <i class="fa-solid fa-bars"></i>
        </button>
        <h1 class="text-base md:text-lg font-bold text-slate-800">ADMIN PROFILE</h1>
        
        <div class="flex items-center gap-2 md:gap-4">
          <button class="w-10 h-10 rounded-full flex items-center justify-center text-slate-500 hover:bg-gray-100 hidden md:flex">
            <i class="fa-solid fa-moon"></i>
          </button>
          <button class="w-10 h-10 rounded-full flex items-center justify-center text-slate-500 hover:bg-gray-100 relative">
            <i class="fa-solid fa-bell"></i>
            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
          </button>
          <div class="w-10 h-10 rounded-full bg-orange-500 overflow-hidden">
            <img :src="profile.avatar" alt="Admin">
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="p-4 md:p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
          <!-- Left Column - Profile Card -->
          <div class="lg:col-span-1 space-y-4">
            <!-- Profile Avatar Card -->
            <div class="bg-white rounded-lg md:rounded-xl p-4 md:p-6 shadow-sm text-center">
              <div class="relative inline-block mb-3 md:mb-4">
                <img :src="profile.avatar" alt="Profile" class="w-24 h-24 md:w-32 md:h-32 rounded-full mx-auto object-cover border-4 border-orange-100">
                <button class="absolute bottom-0 right-0 w-8 h-8 md:w-10 md:h-10 bg-orange-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-orange-600">
                  <i class="fa-solid fa-camera text-sm md:text-base"></i>
                </button>
              </div>
              <h2 class="text-lg md:text-xl font-bold text-slate-800">{{ profile.firstName }} {{ profile.lastName }}</h2>
              <p class="text-sm md:text-base text-slate-500">{{ profile.email }}</p>
              <span class="inline-block mt-2 px-3 py-1 bg-orange-100 text-orange-600 rounded-full text-xs md:text-sm font-medium">{{ profile.role }}</span>
              
              <div class="mt-6 pt-6 border-t border-gray-100">
                <div class="flex justify-center gap-3">
                  <a href="#" class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-200">
                    <i class="fa-brands fa-facebook-f"></i>
                  </a>
                  <a href="#" class="w-10 h-10 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center hover:bg-sky-200">
                    <i class="fa-brands fa-twitter"></i>
                  </a>
                  <a href="#" class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center hover:bg-blue-200">
                    <i class="fa-brands fa-linkedin-in"></i>
                  </a>
                </div>
              </div>
            </div>

            <!-- Account Info -->
            <div class="bg-white rounded-lg md:rounded-xl p-4 md:p-6 shadow-sm">
              <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-3 md:mb-4">Account Info</h3>
              <div class="space-y-3 text-sm">
                <div class="flex items-center gap-3">
                  <i class="fa-solid fa-briefcase w-5 text-slate-400"></i>
                  <span class="text-slate-600">{{ profile.department }}</span>
                </div>
                <div class="flex items-center gap-3">
                  <i class="fa-solid fa-calendar w-5 text-slate-400"></i>
                  <span class="text-slate-600">Joined {{ profile.joinedAt }}</span>
                </div>
                <div class="flex items-center gap-3">
                  <i class="fa-solid fa-clock w-5 text-slate-400"></i>
                  <span class="text-slate-600">Last login: {{ profile.lastLogin }}</span>
                </div>
              </div>
            </div>

            <!-- Security -->
            <div class="bg-white rounded-lg md:rounded-xl p-4 md:p-6 shadow-sm">
              <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-3 md:mb-4">Security</h3>
              <button @click="showPasswordModal = true" class="w-full px-4 py-3 border border-orange-200 bg-orange-50 text-orange-600 rounded-lg font-medium hover:bg-orange-100 flex items-center justify-center gap-2">
                <i class="fa-solid fa-lock"></i>
                Change Password
              </button>
            </div>
          </div>

          <!-- Right Column - Profile Details -->
          <div class="lg:col-span-2 space-y-4">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg md:rounded-xl p-4 md:p-6 shadow-sm">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 md:mb-6">
                <h3 class="text-base md:text-lg font-semibold text-slate-800">Personal Information</h3>
                <div class="flex gap-2">
                  <button v-if="isEditing" @click="cancelEdit" class="px-4 py-2 border border-gray-200 rounded-lg text-slate-600 hover:bg-gray-50">
                    Cancel
                  </button>
                  <button @click="toggleEdit" :class="[
                    'px-4 py-2 rounded-lg font-medium flex items-center gap-2',
                    isEditing ? 'bg-green-500 text-white hover:bg-green-600' : 'bg-orange-500 text-white hover:bg-orange-600'
                  ]">
                    <i :class="['fa-solid', isEditing ? 'fa-check' : 'fa-pen']"></i>
                    {{ isEditing ? 'Save' : 'Edit' }}
                  </button>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">First Name</label>
                  <input v-if="isEditing" v-model="editForm.firstName" type="text" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                  <p v-else class="px-4 py-3 bg-gray-50 rounded-lg text-slate-800">{{ profile.firstName }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">Last Name</label>
                  <input v-if="isEditing" v-model="editForm.lastName" type="text" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                  <p v-else class="px-4 py-3 bg-gray-50 rounded-lg text-slate-800">{{ profile.lastName }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                  <input v-if="isEditing" v-model="editForm.email" type="email" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                  <p v-else class="px-4 py-3 bg-gray-50 rounded-lg text-slate-800">{{ profile.email }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">Phone Number</label>
                  <input v-if="isEditing" v-model="editForm.phone" type="tel" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                  <p v-else class="px-4 py-3 bg-gray-50 rounded-lg text-slate-800">{{ profile.phone }}</p>
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-slate-700 mb-2">Bio</label>
                  <textarea v-if="isEditing" v-model="editForm.bio" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 resize-none"></textarea>
                  <p v-else class="px-4 py-3 bg-gray-50 rounded-lg text-slate-800">{{ profile.bio }}</p>
                </div>
              </div>
            </div>

            <!-- Address -->
            <div class="bg-white rounded-lg md:rounded-xl p-4 md:p-6 shadow-sm">
              <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4 md:mb-6">Address</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-slate-700 mb-2">Street Address</label>
                  <input v-if="isEditing" v-model="editForm.address.street" type="text" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                  <p v-else class="px-4 py-3 bg-gray-50 rounded-lg text-slate-800">{{ profile.address.street }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">City</label>
                  <input v-if="isEditing" v-model="editForm.address.city" type="text" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                  <p v-else class="px-4 py-3 bg-gray-50 rounded-lg text-slate-800">{{ profile.address.city }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">State</label>
                  <input v-if="isEditing" v-model="editForm.address.state" type="text" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                  <p v-else class="px-4 py-3 bg-gray-50 rounded-lg text-slate-800">{{ profile.address.state }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">ZIP Code</label>
                  <input v-if="isEditing" v-model="editForm.address.zip" type="text" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                  <p v-else class="px-4 py-3 bg-gray-50 rounded-lg text-slate-800">{{ profile.address.zip }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">Country</label>
                  <input v-if="isEditing" v-model="editForm.address.country" type="text" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                  <p v-else class="px-4 py-3 bg-gray-50 rounded-lg text-slate-800">{{ profile.address.country }}</p>
                </div>
              </div>
            </div>

            <!-- Notification Settings -->
            <div class="bg-white rounded-lg md:rounded-xl p-4 md:p-6 shadow-sm">
              <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4 md:mb-6">Notification Settings</h3>
              <div class="space-y-4">
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                  <div>
                    <p class="font-medium text-slate-800">Email Notifications</p>
                    <p class="text-sm text-slate-500">Receive email updates about your account</p>
                  </div>
                  <label class="relative inline-flex items-center cursor-pointer">
                    <input v-model="profile.notifications.email" type="checkbox" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                  </label>
                </div>
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                  <div>
                    <p class="font-medium text-slate-800">Order Alerts</p>
                    <p class="text-sm text-slate-500">Get notified about new orders</p>
                  </div>
                  <label class="relative inline-flex items-center cursor-pointer">
                    <input v-model="profile.notifications.orderAlerts" type="checkbox" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                  </label>
                </div>
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                  <div>
                    <p class="font-medium text-slate-800">Stock Alerts</p>
                    <p class="text-sm text-slate-500">Get notified about low stock products</p>
                  </div>
                  <label class="relative inline-flex items-center cursor-pointer">
                    <input v-model="profile.notifications.stockAlerts" type="checkbox" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                  </label>
                </div>
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                  <div>
                    <p class="font-medium text-slate-800">Push Notifications</p>
                    <p class="text-sm text-slate-500">Receive push notifications on browser</p>
                  </div>
                  <label class="relative inline-flex items-center cursor-pointer">
                    <input v-model="profile.notifications.push" type="checkbox" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Change Password Modal -->
    <div v-if="showPasswordModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-slate-800">Change Password</h3>
          <button @click="showPasswordModal = false" class="text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-times"></i>
          </button>
        </div>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Current Password</label>
            <input v-model="passwordForm.current" type="password" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">New Password</label>
            <input v-model="passwordForm.new" type="password" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Confirm New Password</label>
            <input v-model="passwordForm.confirm" type="password" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
          </div>
        </div>
        <div class="flex gap-3 mt-6">
          <button @click="showPasswordModal = false" class="flex-1 px-4 py-3 border border-gray-200 rounded-lg text-slate-600 hover:bg-gray-50">
            Cancel
          </button>
          <button @click="changePassword" class="flex-1 px-4 py-3 bg-orange-500 text-white rounded-lg font-medium hover:bg-orange-600">
            Change Password
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Smooth transitions */
button, a {
  transition: all 0.2s ease;
}
</style>
