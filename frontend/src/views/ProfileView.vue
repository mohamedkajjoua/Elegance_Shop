<script setup>
import { ref } from 'vue'
import { useUserStore } from '@/stores/user'
import Breadcrumb from '@/components/layout/Breadcrumb.vue'

const userStore = useUserStore()

const breadcrumbItems = [{ label: 'My Profile' }]

const isEditing = ref(false)
const editForm = ref({
  name: userStore.profile.name,
  email: userStore.profile.email,
  phone: userStore.profile.phone
})

function toggleEdit() {
  if (isEditing.value) {
    // Save changes
    userStore.updateProfile(editForm.value)
    alert('Profile saved!')
  }
  isEditing.value = !isEditing.value
}

function cancelEdit() {
  editForm.value = {
    name: userStore.profile.name,
    email: userStore.profile.email,
    phone: userStore.profile.phone
  }
  isEditing.value = false
}
</script>

<template>
  <div class="pb-12">
    <Breadcrumb :items="breadcrumbItems" />
    
    <div class="grid grid-cols-3 gap-8">
      <!-- Profile Info -->
      <div class="col-span-2">
        <div class="bg-white rounded-2xl p-8 mb-6">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Personal Information</h2>
            <div class="flex gap-2">
              <button 
                v-if="isEditing"
                class="px-4 py-2 border border-border rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors"
                @click="cancelEdit"
              >
                Cancel
              </button>
              <button 
                class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors"
                @click="toggleEdit"
              >
                {{ isEditing ? 'Save' : 'Edit' }}
              </button>
            </div>
          </div>

          <div class="flex items-center gap-6 mb-8">
            <img 
              :src="userStore.profile.avatar" 
              :alt="userStore.profile.name"
              class="w-24 h-24 rounded-full"
            >
            <div>
              <h3 class="text-xl font-bold">{{ userStore.profile.name }}</h3>
              <p class="text-text-light">{{ userStore.profile.email }}</p>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-text-light mb-2">Full Name</label>
              <input 
                v-model="editForm.name"
                type="text"
                :readonly="!isEditing"
                :class="[
                  'w-full py-3 text-text font-medium transition-all',
                  isEditing 
                    ? 'px-4 border border-border rounded-xl focus:border-primary outline-none' 
                    : 'bg-transparent border-none'
                ]"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-text-light mb-2">Email</label>
              <input 
                v-model="editForm.email"
                type="email"
                :readonly="!isEditing"
                :class="[
                  'w-full py-3 text-text font-medium transition-all',
                  isEditing 
                    ? 'px-4 border border-border rounded-xl focus:border-primary outline-none' 
                    : 'bg-transparent border-none'
                ]"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-text-light mb-2">Phone</label>
              <input 
                v-model="editForm.phone"
                type="tel"
                :readonly="!isEditing"
                :class="[
                  'w-full py-3 text-text font-medium transition-all',
                  isEditing 
                    ? 'px-4 border border-border rounded-xl focus:border-primary outline-none' 
                    : 'bg-transparent border-none'
                ]"
              >
            </div>
          </div>
        </div>

        <!-- Addresses -->
        <div class="bg-white rounded-2xl p-8">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Saved Addresses</h2>
            <button class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">
              <i class="fa-solid fa-plus mr-2"></i> Add New
            </button>
          </div>

          <div v-if="userStore.addresses.length > 0" class="space-y-4">
            <div 
              v-for="address in userStore.addresses"
              :key="address.id"
              class="flex items-start gap-4 p-4 border border-border rounded-xl"
            >
              <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center text-primary">
                <i class="fa-solid fa-location-dot"></i>
              </div>
              <div class="flex-1">
                <h4 class="font-bold mb-1">{{ address.label }}</h4>
                <p class="text-sm text-text-light">{{ address.recipient }} - {{ address.text }}</p>
              </div>
              <div class="flex gap-2">
                <button class="w-8 h-8 border border-border rounded-lg flex items-center justify-center text-text-light hover:text-primary hover:border-primary transition-colors">
                  <i class="fa-solid fa-pen text-xs"></i>
                </button>
                <button 
                  class="w-8 h-8 border border-border rounded-lg flex items-center justify-center text-text-light hover:text-danger hover:border-danger transition-colors"
                  @click="userStore.removeAddress(address.id)"
                >
                  <i class="fa-solid fa-trash text-xs"></i>
                </button>
              </div>
            </div>
          </div>
          <div v-else class="text-center py-8 text-text-light">
            <i class="fa-solid fa-location-dot text-4xl mb-4 opacity-30"></i>
            <p>No saved addresses yet</p>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="col-span-1">
        <div class="bg-white rounded-2xl p-6">
          <h3 class="font-bold mb-4">Quick Links</h3>
          <div class="space-y-2">
            <router-link 
              to="/orders"
              class="flex items-center gap-3 p-3 rounded-lg text-text-light hover:bg-background hover:text-text transition-colors"
            >
              <i class="fa-solid fa-box w-5"></i> My Orders
            </router-link>
            <router-link 
              to="/wishlist"
              class="flex items-center gap-3 p-3 rounded-lg text-text-light hover:bg-background hover:text-text transition-colors"
            >
              <i class="fa-solid fa-heart w-5"></i> Wishlist
            </router-link>
            <router-link 
              to="/cart"
              class="flex items-center gap-3 p-3 rounded-lg text-text-light hover:bg-background hover:text-text transition-colors"
            >
              <i class="fa-solid fa-cart-shopping w-5"></i> Shopping Cart
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
