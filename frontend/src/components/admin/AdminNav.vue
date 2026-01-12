<script setup>
import { ref, watch, onMounted } from "vue";
import { useRoute } from "vue-router";

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(["close"]);

const route = useRoute();

// Submenu states
const productsOpen = ref(false);
const categoryOpen = ref(false);
const brandOpen = ref(false);

// Function to check active route and open corresponding submenu
const checkActiveMenu = () => {
  if (route.path.includes("/admin/products")) productsOpen.value = true;
  if (route.path.includes("/admin/category")) categoryOpen.value = true;
  if (route.path.includes("/admin/brand")) brandOpen.value = true;
};

// Watch for route changes
watch(() => route.path, checkActiveMenu, { immediate: true });
</script>

<template>
  <div>
    <!-- Mobile Overlay -->
    <div
      v-if="isOpen"
      @click="$emit('close')"
      class="fixed inset-0 bg-black/50 z-40 lg:hidden"
    ></div>

    <!-- Sidebar -->
    <aside
      :class="[
        'bg-slate-800 text-white flex flex-col fixed h-full z-50 transition-transform duration-300',
        'w-64 lg:translate-x-0',
        isOpen ? 'translate-x-0' : '-translate-x-full',
      ]"
    >
      <!-- Logo -->
      <div class="p-5 flex items-center gap-3 border-b border-slate-700">
        <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
          <i class="fa-solid fa-bag-shopping text-white"></i>
        </div>
        <span class="text-xl font-bold">Admin</span>
        <button @click="$emit('close')" class="ml-auto text-slate-400 hover:text-white lg:hidden">
          <i class="fa-solid fa-times"></i>
        </button>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 overflow-y-auto py-4">
        <!-- General Section -->
        <p class="px-5 text-xs text-slate-500 uppercase tracking-wider mb-3">General</p>

        <!-- Dashboard -->
        <router-link
          to="/admin"
          exact-active-class="bg-orange-500/10 text-orange-500 border-orange-500"
          class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors border-l-4 border-transparent"
        >
          <i class="fa-solid fa-grid-2 w-5"></i>
          <span>Dashboard</span>
        </router-link>

        <!-- Products Submenu -->
        <div class="relative">
          <button
            @click="productsOpen = !productsOpen"
            :class="[
              'w-full flex items-center gap-3 px-5 py-3 border-l-4 transition-colors',
              productsOpen || route.path.includes('/admin/products')
                ? 'bg-orange-500/10 text-orange-500 border-orange-500'
                : 'text-slate-300 hover:bg-slate-700 hover:text-white border-transparent',
            ]"
          >
            <i class="fa-solid fa-cube w-5"></i>
            <span>Products</span>
            <i
              :class="[
                'fa-solid ml-auto text-xs transition-transform',
                productsOpen ? 'fa-chevron-up' : 'fa-chevron-down',
              ]"
            ></i>
          </button>
          <div v-show="productsOpen" class="bg-slate-900/50 py-2">
            <router-link
              to="/admin/products"
              exact-active-class="text-orange-500"
              class="block px-12 py-2 text-sm text-slate-300 hover:text-orange-500 transition-colors"
              >Product List</router-link
            >
            <router-link
              to="/admin/products/create"
              exact-active-class="text-orange-500"
              class="block px-12 py-2 text-sm text-slate-300 hover:text-orange-500 transition-colors"
              >Create Product</router-link
            >
          </div>
        </div>

        <!-- Category Submenu -->
        <div class="relative">
          <button
            @click="categoryOpen = !categoryOpen"
            :class="[
              'w-full flex items-center gap-3 px-5 py-3 border-l-4 transition-colors',
              categoryOpen || route.path.includes('/admin/category')
                ? 'bg-orange-500/10 text-orange-500 border-orange-500'
                : 'text-slate-300 hover:bg-slate-700 hover:text-white border-transparent',
            ]"
          >
            <i class="fa-solid fa-layer-group w-5"></i>
            <span>Category</span>
            <i
              :class="[
                'fa-solid ml-auto text-xs transition-transform',
                categoryOpen ? 'fa-chevron-up' : 'fa-chevron-down',
              ]"
            ></i>
          </button>
          <div v-show="categoryOpen" class="bg-slate-900/50 py-2">
            <router-link
              to="/admin/category"
              exact-active-class="text-orange-500"
              class="block px-12 py-2 text-sm text-slate-300 hover:text-orange-500 transition-colors"
              >Category List</router-link
            >
            <router-link
              to="/admin/category/create"
              exact-active-class="text-orange-500"
              class="block px-12 py-2 text-sm text-slate-300 hover:text-orange-500 transition-colors"
              >Create Category</router-link
            >
          </div>
        </div>

        <!-- Brand Submenu -->
        <div class="relative">
          <button
            @click="brandOpen = !brandOpen"
            :class="[
              'w-full flex items-center gap-3 px-5 py-3 border-l-4 transition-colors',
              brandOpen || route.path.includes('/admin/brand')
                ? 'bg-orange-500/10 text-orange-500 border-orange-500'
                : 'text-slate-300 hover:bg-slate-700 hover:text-white border-transparent',
            ]"
          >
            <i class="fa-solid fa-tag w-5"></i>
            <span>Brand</span>
            <i
              :class="[
                'fa-solid ml-auto text-xs transition-transform',
                brandOpen ? 'fa-chevron-up' : 'fa-chevron-down',
              ]"
            ></i>
          </button>
          <div v-show="brandOpen" class="bg-slate-900/50 py-2">
            <router-link
              to="/admin/brand"
              exact-active-class="text-orange-500"
              class="block px-12 py-2 text-sm text-slate-300 hover:text-orange-500 transition-colors"
              >Brand List</router-link
            >
            <router-link
              to="/admin/brand/create"
              exact-active-class="text-orange-500"
              class="block px-12 py-2 text-sm text-slate-300 hover:text-orange-500 transition-colors"
              >Create Brand</router-link
            >
          </div>
        </div>

        <!-- Orders -->
        <router-link
          to="/admin/orders"
          active-class="bg-orange-500/10 text-orange-500 border-orange-500"
          class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors border-l-4 border-transparent"
        >
          <i class="fa-solid fa-cart-shopping w-5"></i>
          <span>Orders</span>
        </router-link>

        <!-- Settings -->
        <router-link
          to="/admin/settings"
          active-class="bg-orange-500/10 text-orange-500 border-orange-500"
          class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors border-l-4 border-transparent"
        >
          <i class="fa-solid fa-gear w-5"></i>
          <span>Settings</span>
        </router-link>

        <!-- Users Section -->
        <p class="px-5 text-xs text-slate-500 uppercase tracking-wider mt-6 mb-3">Users</p>

        <router-link
          to="/admin/users"
          active-class="bg-orange-500/10 text-orange-500 border-orange-500"
          class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors border-l-4 border-transparent"
        >
          <i class="fa-solid fa-users w-5"></i>
          <span>User List</span>
        </router-link>

        <router-link
          to="/admin/profile"
          active-class="bg-orange-500/10 text-orange-500 border-orange-500"
          class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors border-l-4 border-transparent"
        >
          <i class="fa-solid fa-user w-5"></i>
          <span>Profile</span>
        </router-link>
      </nav>
    </aside>
  </div>
</template>
