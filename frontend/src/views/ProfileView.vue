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

// Address Form
const showAddressForm = ref(false)
const editingAddressId = ref(null)
const addressForm = ref({
  label: '',
  recipient: '',
  phone: '',
  street: '',
  city: '',
  state: '',
  zipCode: '',
  country: ''
})

function toggleEdit() {
  if (isEditing.value) {
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

// Photo Upload
const photoInput = ref(null)
const isUploadingPhoto = ref(false)

function triggerPhotoUpload() {
  photoInput.value?.click()
}

function handlePhotoChange(event) {
  const file = event.target.files[0]
  if (!file) return
  
  // Validate file type
  if (!file.type.startsWith('image/')) {
    alert('Please select an image file')
    return
  }
  
  // Validate file size (max 5MB)
  if (file.size > 5 * 1024 * 1024) {
    alert('Image size should be less than 5MB')
    return
  }
  
  isUploadingPhoto.value = true
  
  // Read file and convert to base64
  const reader = new FileReader()
  reader.onload = (e) => {
    userStore.updateProfile({ avatar: e.target.result })
    isUploadingPhoto.value = false
    alert('Profile photo updated!')
  }
  reader.onerror = () => {
    isUploadingPhoto.value = false
    alert('Failed to upload photo')
  }
  reader.readAsDataURL(file)
}

function removePhoto() {
  if (confirm('Are you sure you want to remove your profile photo?')) {
    const initials = userStore.profile.name.split(' ').map(n => n[0]).join('')
    userStore.updateProfile({ 
      avatar: `https://ui-avatars.com/api/?name=${encodeURIComponent(userStore.profile.name)}&background=5F2EEA&color=fff` 
    })
    alert('Profile photo removed')
  }
}

function toggleAddressForm() {
  showAddressForm.value = !showAddressForm.value
  if (!showAddressForm.value) {
    resetAddressForm()
  }
}

function resetAddressForm() {
  editingAddressId.value = null
  addressForm.value = {
    label: '',
    recipient: '',
    phone: '',
    street: '',
    city: '',
    state: '',
    zipCode: '',
    country: ''
  }
}

function editAddress(address) {
  editingAddressId.value = address.id
  addressForm.value = {
    label: address.label || '',
    recipient: address.recipient || '',
    phone: address.phone || '',
    street: address.street || '',
    city: address.city || '',
    state: address.state || '',
    zipCode: address.zipCode || '',
    country: address.country || ''
  }
  showAddressForm.value = true
}

function saveAddress() {
  if (!addressForm.value.label || !addressForm.value.street || !addressForm.value.city) {
    alert('Please fill in required fields (Label, Street, City)')
    return
  }
  
  const fullAddress = `${addressForm.value.street}, ${addressForm.value.city}, ${addressForm.value.state} ${addressForm.value.zipCode}, ${addressForm.value.country}`
  
  const addressData = {
    label: addressForm.value.label,
    recipient: addressForm.value.recipient || userStore.profile.name,
    phone: addressForm.value.phone || userStore.profile.phone,
    text: fullAddress,
    street: addressForm.value.street,
    city: addressForm.value.city,
    state: addressForm.value.state,
    zipCode: addressForm.value.zipCode,
    country: addressForm.value.country
  }
  
  if (editingAddressId.value) {
    // Update existing address
    userStore.updateAddress(editingAddressId.value, addressData)
    alert('Address updated successfully!')
  } else {
    // Add new address
    userStore.addAddress(addressData)
    alert('Address added successfully!')
  }
  
  showAddressForm.value = false
  resetAddressForm()
}
</script>

<template>
  <div class="pb-12">
    <Breadcrumb :items="breadcrumbItems" />
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
      <!-- Profile Info -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl p-6 sm:p-8 mb-6">
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h2 class="text-xl sm:text-2xl font-bold">Personal Information</h2>
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

          <!-- Hidden File Input -->
          <input 
            ref="photoInput"
            type="file" 
            accept="image/*"
            class="hidden"
            @change="handlePhotoChange"
          >

          <div class="flex items-center gap-4 sm:gap-6 mb-8">
            <!-- Avatar with Edit Overlay -->
            <div class="relative group">
              <img 
                :src="userStore.profile.avatar" 
                :alt="userStore.profile.name"
                class="w-20 h-20 sm:w-28 sm:h-28 rounded-full object-cover border-4 border-white shadow-lg"
              >
              
              <!-- Loading Overlay -->
              <div 
                v-if="isUploadingPhoto"
                class="absolute inset-0 bg-black/50 rounded-full flex items-center justify-center"
              >
                <i class="fa-solid fa-spinner fa-spin text-white text-xl"></i>
              </div>
              
              <!-- Edit Overlay -->
              <div 
                v-else
                class="absolute inset-0 bg-black/50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2"
              >
                <button 
                  class="w-9 h-9 bg-white rounded-full flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-colors shadow-lg"
                  @click="triggerPhotoUpload"
                  title="Change Photo"
                >
                  <i class="fa-solid fa-camera text-sm"></i>
                </button>
                <button 
                  class="w-9 h-9 bg-white rounded-full flex items-center justify-center text-danger hover:bg-danger hover:text-white transition-colors shadow-lg"
                  @click="removePhoto"
                  title="Remove Photo"
                >
                  <i class="fa-solid fa-trash text-sm"></i>
                </button>
              </div>
              
              <!-- Camera Badge -->
              <button 
                class="absolute bottom-0 right-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center shadow-lg hover:bg-primary-dark transition-colors"
                @click="triggerPhotoUpload"
              >
                <i class="fa-solid fa-pen text-xs"></i>
              </button>
            </div>
            
            <div>
              <h3 class="text-lg sm:text-xl font-bold">{{ userStore.profile.name }}</h3>
              <p class="text-text-light text-sm sm:text-base">{{ userStore.profile.email }}</p>
              <button 
                class="mt-2 text-primary text-sm font-medium hover:underline"
                @click="triggerPhotoUpload"
              >
                <i class="fa-solid fa-camera mr-1"></i> Change Photo
              </button>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
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
        <div class="bg-white rounded-2xl p-6 sm:p-8">
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h2 class="text-xl sm:text-2xl font-bold">Saved Addresses</h2>
            <button 
              class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors"
              @click="toggleAddressForm"
            >
              <i :class="['fa-solid mr-2', showAddressForm ? 'fa-xmark' : 'fa-plus']"></i>
              {{ showAddressForm ? 'Cancel' : 'Add New' }}
            </button>
          </div>

          <!-- Add/Edit Address Form -->
          <div v-if="showAddressForm" class="mb-6 p-4 sm:p-6 bg-background rounded-xl border border-border">
            <h3 class="font-bold mb-4">{{ editingAddressId ? 'Edit Address' : 'Add New Address' }}</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-text-light mb-2">Label *</label>
                <input 
                  v-model="addressForm.label"
                  type="text"
                  placeholder="e.g., Home, Office, Parent's House"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary outline-none text-sm"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-text-light mb-2">Recipient Name</label>
                <input 
                  v-model="addressForm.recipient"
                  type="text"
                  :placeholder="userStore.profile.name"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary outline-none text-sm"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-text-light mb-2">Phone</label>
                <input 
                  v-model="addressForm.phone"
                  type="tel"
                  :placeholder="userStore.profile.phone"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary outline-none text-sm"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-text-light mb-2">Country</label>
                <input 
                  v-model="addressForm.country"
                  type="text"
                  placeholder="e.g., United States"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary outline-none text-sm"
                >
              </div>
              <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-text-light mb-2">Street Address *</label>
                <input 
                  v-model="addressForm.street"
                  type="text"
                  placeholder="e.g., 123 Main Street, Apt 4B"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary outline-none text-sm"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-text-light mb-2">City *</label>
                <input 
                  v-model="addressForm.city"
                  type="text"
                  placeholder="e.g., New York"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary outline-none text-sm"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-text-light mb-2">State / Province</label>
                <input 
                  v-model="addressForm.state"
                  type="text"
                  placeholder="e.g., NY"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary outline-none text-sm"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-text-light mb-2">ZIP / Postal Code</label>
                <input 
                  v-model="addressForm.zipCode"
                  type="text"
                  placeholder="e.g., 10001"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary outline-none text-sm"
                >
              </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
              <button 
                class="px-6 py-2.5 border border-border rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors"
                @click="toggleAddressForm"
              >
                Cancel
              </button>
              <button 
                class="px-6 py-2.5 bg-primary text-white rounded-xl text-sm font-medium hover:bg-primary-dark transition-colors"
                @click="saveAddress"
              >
                <i class="fa-solid fa-check mr-2"></i>{{ editingAddressId ? 'Update Address' : 'Save Address' }}
              </button>
            </div>
          </div>

          <div v-if="userStore.addresses.length > 0" class="space-y-4">
            <div 
              v-for="address in userStore.addresses"
              :key="address.id"
              class="flex flex-col sm:flex-row items-start gap-4 p-4 border border-border rounded-xl"
            >
              <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center text-primary shrink-0">
                <i class="fa-solid fa-location-dot"></i>
              </div>
              <div class="flex-1 min-w-0">
                <h4 class="font-bold mb-1">{{ address.label }}</h4>
                <p class="text-sm text-text-light">{{ address.recipient }} - {{ address.phone }}</p>
                <p class="text-sm text-text-light mt-1 break-words">{{ address.text }}</p>
              </div>
              <div class="flex gap-2 shrink-0">
                <button 
                  class="w-8 h-8 border border-border rounded-lg flex items-center justify-center text-text-light hover:text-primary hover:border-primary transition-colors"
                  @click="editAddress(address)"
                >
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
          <div v-else-if="!showAddressForm" class="text-center py-8 text-text-light">
            <i class="fa-solid fa-location-dot text-4xl mb-4 opacity-30"></i>
            <p>No saved addresses yet</p>
            <button 
              class="mt-4 text-primary hover:underline"
              @click="showAddressForm = true"
            >
              Add your first address
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="lg:col-span-1">
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
