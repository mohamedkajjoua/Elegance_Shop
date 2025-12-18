<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cart'
import { useUserStore } from '@/stores/user'

const router = useRouter()
const cartStore = useCartStore()
const userStore = useUserStore()

const searchQuery = ref('')
const showProfileDropdown = ref(false)

function toggleProfileDropdown() {
  showProfileDropdown.value = !showProfileDropdown.value
}

function closeDropdown() {
  showProfileDropdown.value = false
}

function handleLogout() {
  if (confirm('Are you sure you want to logout?')) {
    userStore.logout()
    router.push('/')
  }
}

function clearSearch() {
  searchQuery.value = ''
}
</script>

<template>
  <header class="header flex items-center justify-between py-5 px-8 my-5 bg-card-bg rounded-[20px] shadow-sm">
    <router-link to="/" class="logo flex items-center gap-3 group">
      <div class="logo-icon w-9 h-9 bg-primary text-white rounded-[10px] flex items-center justify-center text-base group-hover:scale-105 transition-transform">
        <i class="fa-solid fa-shapes"></i>
      </div>
      <span class="font-bold text-xl text-text">lapakbaju</span>
    </router-link>

    <div class="search-bar flex-1 max-w-[400px] mx-10 bg-background px-5 py-3 rounded-xl flex items-center gap-3 border border-transparent focus-within:border-primary transition-colors">
      <i class="fa-solid fa-magnifying-glass text-text-light"></i>
      <input 
        v-model="searchQuery"
        type="text" 
        placeholder="Search among 100+ products"
        class="bg-transparent flex-1 outline-none text-sm text-text placeholder-text-light"
      >
      <i 
        v-show="searchQuery"
        class="fa-solid fa-xmark text-text-light cursor-pointer hover:text-danger transition-colors"
        @click="clearSearch"
      ></i>
    </div>

    <div class="header-actions flex items-center gap-4">
      <button class="lang-btn flex items-center gap-1.5 px-3.5 py-2 text-[13px] font-medium text-[#6E7191] hover:text-primary transition-colors">
        ENG <i class="fa-solid fa-globe"></i>
      </button>
      
      <router-link 
        to="/wishlist"
        class="action-btn flex items-center gap-2 px-[18px] py-2.5 bg-[#F0EBFF] text-primary rounded-[10px] text-[13px] font-semibold hover:bg-[#E5DEFF] transition-colors"
      >
        Wishlist <i class="fa-regular fa-heart"></i>
      </router-link>
      
      <router-link 
        to="/cart"
        class="action-btn cart-btn flex items-center gap-2 px-[18px] py-2.5 bg-[#F0EBFF] text-primary rounded-[10px] text-[13px] font-semibold hover:bg-[#E5DEFF] transition-colors relative"
      >
        Your Cart <i class="fa-solid fa-cart-shopping"></i>
        <span 
          v-show="cartStore.totalItems > 0"
          class="cart-badge absolute -top-1.5 -right-1.5 bg-danger text-white text-[10px] font-bold min-w-[18px] h-[18px] rounded-full flex items-center justify-center"
        >
          {{ cartStore.totalItems }}
        </span>
      </router-link>

      <div class="user-profile-wrapper relative" @click.stop>
        <div class="user-profile cursor-pointer" @click="toggleProfileDropdown">
          <img 
            :src="userStore.profile.avatar" 
            :alt="userStore.profile.name"
            class="user-avatar w-10 h-10 rounded-full border-2 border-transparent hover:border-primary transition-colors"
          >
        </div>
        
        <!-- Profile Dropdown -->
        <div 
          :class="['profile-dropdown absolute top-[calc(100%+12px)] right-0 bg-card-bg rounded-xl shadow-md min-w-[220px] transition-all duration-200 z-50 border border-border', showProfileDropdown ? 'active' : 'opacity-0 invisible -translate-y-2.5']"
        >
          <div class="profile-dropdown-header p-4 border-b border-border">
            <h4 class="text-sm font-bold mb-1">{{ userStore.profile.name }}</h4>
            <p class="text-xs text-text-light">{{ userStore.profile.email }}</p>
          </div>
          <div class="profile-dropdown-menu p-2 flex flex-col gap-1">
            <router-link 
              to="/profile"
              class="profile-dropdown-item flex items-center gap-3 p-3 rounded-lg text-sm text-text hover:bg-background transition-colors w-full text-left"
              @click="closeDropdown"
            >
              <i class="fa-solid fa-user w-4 text-center"></i> My Account
            </router-link>
            <router-link 
              to="/orders"
              class="profile-dropdown-item flex items-center gap-3 p-3 rounded-lg text-sm text-text hover:bg-background transition-colors w-full text-left"
              @click="closeDropdown"
            >
              <i class="fa-solid fa-box w-4 text-center"></i> My Orders
            </router-link>
            <hr class="my-2 border-t border-border">
            <button 
              @click="handleLogout"
              class="profile-dropdown-item danger flex items-center gap-3 p-3 rounded-lg text-sm text-danger hover:bg-[#FFF0F0] transition-colors w-full text-left"
            >
              <i class="fa-solid fa-right-from-bracket w-4 text-center"></i> Logout
            </button>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>
