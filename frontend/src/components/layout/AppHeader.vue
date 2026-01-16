<script setup lang="ts">
import { onMounted, ref, watch } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useCartStore } from "@/stores/cart";
import { useAuthStore } from "@/stores/auth/auth";
import { useWishlistStore } from "@/stores/user/WishlistStore";

const router = useRouter();
const route = useRoute();
const cartStore = useCartStore();
const userStore = useAuthStore();
const wishlistStore = useWishlistStore();

const searchQuery = ref("");
let searchTimeout: any = null;
const showProfileDropdown = ref(false);
const showMobileMenu = ref(false);
const showMobileSearch = ref(false);

onMounted(async () => {
  if (userStore.token) {
    await userStore.loadCurrentUser();
    await cartStore.fetchCart();
    await wishlistStore.fetchWishlist();
  }
});

function toggleProfileDropdown() {
  showProfileDropdown.value = !showProfileDropdown.value;
}

function toggleMobileMenu() {
  showMobileMenu.value = !showMobileMenu.value;
}

function toggleMobileSearch() {
  showMobileSearch.value = !showMobileSearch.value;
}

function closeDropdown() {
  showProfileDropdown.value = false;
}

const handleLogout = async () => {
  if (confirm("Are you sure you want to logout?")) {
    await userStore.logout();
    router.push("/login");
  }
};

function clearSearch() {
  searchQuery.value = "";
  if (route.path === "/shop") {
    router.push({ path: "/shop" });
  }
}

const handleSearch = () => {
  if (searchQuery.value.length > 0 && searchQuery.value.length < 2) {
    return;
  }

  if (!searchQuery.value && route.path === "/shop") {
    router.push({ path: "/shop" });
    return;
  }

  if (searchQuery.value.length >= 2) {
    router.push({
      path: "/shop",
      query: { q: searchQuery.value },
    });
    showMobileSearch.value = false;
  }
};

watch(
  () => route.query.q,
  (newQ) => {
    searchQuery.value = (newQ as string) || "";
  },
  { immediate: true }
);

watch(searchQuery, (newVal) => {
  clearTimeout(searchTimeout);
  if (newVal.length >= 2) {
    searchTimeout = setTimeout(() => {
      handleSearch();
    }, 500);
  }
});
</script>

<template>
  <header
    class="header flex items-center justify-between py-2.5 sm:py-4 md:py-5 px-3 sm:px-6 md:px-8 my-2 sm:my-4 md:my-5 bg-card-bg rounded-xl sm:rounded-2xl shadow-sm gap-2 sm:gap-4"
  >
    <button
      class="mobile-menu-btn lg:hidden w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-text hover:text-primary transition-colors flex-shrink-0"
      @click="toggleMobileMenu"
    >
      <i :class="['fa-solid text-lg sm:text-xl', showMobileMenu ? 'fa-xmark' : 'fa-bars']"></i>
    </button>

    <router-link to="/" class="logo flex items-center gap-2 group flex-shrink-0">
      <div
        class="logo-icon w-8 h-8 sm:w-9 sm:h-9 bg-primary text-white rounded-lg sm:rounded-xl flex items-center justify-center text-sm sm:text-base group-hover:scale-105 transition-transform"
      >
        <i class="fa-solid fa-shapes"></i>
      </div>
      <span class="font-bold text-base sm:text-lg md:text-xl text-text hidden sm:inline"
        >Elegance Shop</span
      >
    </router-link>

    <nav class="hidden lg:flex items-center gap-1">
      <router-link
        to="/"
        class="nav-link px-4 py-2 text-sm font-medium text-text-light hover:text-primary transition-colors rounded-lg"
        active-class="text-primary bg-primary/10"
        >Home</router-link
      >
      <router-link
        to="/shop"
        class="nav-link px-4 py-2 text-sm font-medium text-text-light hover:text-primary transition-colors rounded-lg"
        active-class="text-primary bg-primary/10"
        >Shop</router-link
      >
      <router-link
        to="/about"
        class="nav-link px-4 py-2 text-sm font-medium text-text-light hover:text-primary transition-colors rounded-lg"
        active-class="text-primary bg-primary/10"
        >About</router-link
      >
      <router-link
        to="/contact"
        class="nav-link px-4 py-2 text-sm font-medium text-text-light hover:text-primary transition-colors rounded-lg"
        active-class="text-primary bg-primary/10"
        >Contact</router-link
      >
    </nav>

    <div
      class="search-bar hidden md:flex flex-1 max-w-[400px] mx-4 lg:mx-10 bg-background px-4 py-2.5 rounded-xl items-center gap-3 border border-transparent focus-within:border-primary transition-colors"
    >
      <i class="fa-solid fa-magnifying-glass text-text-light text-sm"></i>
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search among 100+ products"
        class="bg-transparent flex-1 outline-none text-sm text-text placeholder-text-light"
        @keyup.enter="handleSearch"
      />
      <i
        v-show="searchQuery"
        class="fa-solid fa-xmark text-text-light cursor-pointer hover:text-danger"
        @click="clearSearch"
      ></i>
    </div>

    <div class="header-actions flex items-center gap-1 sm:gap-2 md:gap-3 flex-shrink-0">
      <button
        class="md:hidden w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center text-text-light hover:text-primary"
        @click="toggleMobileSearch"
      >
        <i class="fa-solid fa-magnifying-glass text-sm sm:text-base"></i>
      </button>
      <router-link
        to="/wishlist"
        class="relative action-btn hidden sm:flex items-center gap-1.5 px-2.5 sm:px-3 md:px-4 py-2 sm:py-2.5 bg-primary/10 text-primary rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold hover:bg-primary/20 transition-colors"
      >
        <i class="fa-regular fa-heart"></i>
        <span class="hidden lg:inline">Wishlist</span>

        <span
          v-show="wishlistStore.count > 0"
          class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-bold min-w-[18px] h-[18px] rounded-full flex items-center justify-center shadow-sm border border-white"
        >
          {{ wishlistStore.count }}
        </span>
      </router-link>

      <router-link
        to="/cart"
        class="action-btn cart-btn flex items-center gap-1.5 px-2.5 sm:px-3 md:px-4 py-2 sm:py-2.5 bg-primary/10 text-primary rounded-lg relative"
      >
        <i class="fa-solid fa-cart-shopping"></i>
        <span class="hidden lg:inline">Cart</span>
        <span
          v-show="cartStore.totalItems > 0"
          class="cart-badge absolute -top-1 -right-1 bg-danger text-white text-[9px] font-bold min-w-[16px] h-4 rounded-full flex items-center justify-center"
        >
          {{ cartStore.totalItems }}
        </span>
      </router-link>

      <div v-if="userStore.isAuthenticated" class="user-profile-wrapper relative" @click.stop>
        <div class="user-profile cursor-pointer" @click="toggleProfileDropdown">
          <img
            :src="
              userStore.user?.avatar
                ? `http://localhost:8000/storage/${userStore.user.avatar}`
                : `https://ui-avatars.com/api/?name=${userStore.user?.first_name}`
            "
            class="user-avatar w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-full border-2 border-transparent hover:border-primary transition-colors"
          />
        </div>

        <div
          :class="[
            'profile-dropdown absolute top-[calc(100%+8px)] right-0 bg-card-bg rounded-xl shadow-lg min-w-[200px] transition-all duration-200 z-50 border border-border',
            showProfileDropdown
              ? 'opacity-100 visible translate-y-0'
              : 'opacity-0 invisible -translate-y-2.5',
          ]"
        >
          <div
            v-if="userStore.user"
            class="profile-dropdown-header p-3 sm:p-4 border-b border-border"
          >
            <h4 class="text-sm font-bold mb-1 truncate">
              {{ userStore.user.first_name }} {{ userStore.user.last_name }}
            </h4>
            <p class="text-xs text-text-light truncate">{{ userStore.user.email }}</p>
          </div>

          <div class="profile-dropdown-menu p-1.5 sm:p-2 flex flex-col gap-0.5 sm:gap-1">
            <router-link
              :to="`/profile/${userStore.user.id}`"
              class="profile-dropdown-item flex items-center gap-3 p-2.5 rounded-lg text-sm text-text hover:bg-background"
              @click="closeDropdown"
            >
              <i class="fa-solid fa-user w-4"></i> My Account
            </router-link>
            <router-link
              to="/orders"
              class="profile-dropdown-item flex items-center gap-3 p-2.5 rounded-lg text-sm text-text hover:bg-background"
              @click="closeDropdown"
            >
              <i class="fa-solid fa-box w-4"></i> My Orders
            </router-link>

            <router-link
              v-if="userStore.isAdmin"
              to="/admin"
              class="profile-dropdown-item flex items-center gap-3 p-2.5 rounded-lg text-sm text-text hover:bg-background"
            >
              <i class="fa-solid fa-right-from-bracket mr-2"></i> Switch to Admin
            </router-link>

            <hr class="my-1.5 border-t border-border" />
            <button
              @click="handleLogout"
              :disabled="userStore.isLoading"
              class="profile-dropdown-item flex items-center gap-3 p-2.5 rounded-lg text-sm text-danger hover:bg-red-50 w-full text-left"
            >
              <i class="fa-solid fa-right-from-bracket w-4"></i> Logout
            </button>
          </div>
        </div>
      </div>

      <div v-else class="flex items-center gap-2">
        <router-link
          to="/login"
          class="px-3 sm:px-4 py-2 text-sm sm:text-base text-gray-600 font-medium"
          >Login</router-link
        >
        <router-link
          to="/register"
          class="px-3 sm:px-4 py-2 text-sm sm:text-base bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
          >Register</router-link
        >
      </div>
    </div>
  </header>

  <div
    v-show="showMobileSearch"
    class="md:hidden bg-card-bg mx-2 mb-3 rounded-xl p-3 shadow-sm animate-fade-in"
  >
    <div class="flex items-center gap-3 bg-background px-3 py-2.5 rounded-lg">
      <i class="fa-solid fa-magnifying-glass text-text-light text-sm"></i>
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search products..."
        class="bg-transparent flex-1 outline-none text-sm"
        @keyup.enter="handleSearch"
      />
      <button class="text-text-light text-sm" @click="toggleMobileSearch">Cancel</button>
    </div>
  </div>

  <div
    v-show="showMobileMenu"
    class="lg:hidden bg-card-bg mx-2 mb-3 rounded-xl shadow-sm animate-fade-in overflow-hidden"
  >
    <nav class="flex flex-col p-2">
      <router-link
        to="/"
        class="flex items-center gap-3 px-4 py-3 text-sm text-text hover:bg-primary/5 rounded-lg"
        @click="showMobileMenu = false"
        >Home</router-link
      >
      <router-link
        to="/shop"
        class="flex items-center gap-3 px-4 py-3 text-sm text-text hover:bg-primary/5 rounded-lg"
        @click="showMobileMenu = false"
        >Shop</router-link
      >
      <hr class="my-2 border-border" />
      <router-link
        to="/wishlist"
        class="flex items-center gap-3 px-4 py-3 text-sm text-text hover:bg-primary/5 rounded-lg"
        @click="showMobileMenu = false"
        >Wishlist</router-link
      >
    </nav>
  </div>
</template>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.2s ease-out;
}
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-8px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
/* Dropdown animation class */
.profile-dropdown {
  transform: translateY(-10px);
  pointer-events: none;
}
.profile-dropdown.opacity-100 {
  transform: translateY(0);
  pointer-events: auto;
}
</style>
