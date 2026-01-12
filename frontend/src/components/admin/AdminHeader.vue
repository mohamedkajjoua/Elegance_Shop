<script setup>
import { useAuthStore } from "@/stores/auth/auth";
import { onMounted, ref } from "vue";

const props = defineProps({
  title: {
    type: String,
    required: true,
  },
});

const emit = defineEmits(["toggle-sidebar"]);

const profileDropdownOpen = ref(false);
const isDark = ref(false);

const userStore = useAuthStore();

onMounted(async () => {
  await userStore.loadCurrentUser();
});

// Force light mode on initialization
isDark.value = false;
document.documentElement.classList.remove("dark");
localStorage.removeItem("theme");

const toggleDarkMode = () => {
  isDark.value = !isDark.value;
  if (isDark.value) {
    document.documentElement.classList.add("dark");
    localStorage.setItem("theme", "dark");
  } else {
    document.documentElement.classList.remove("dark");
    localStorage.setItem("theme", "light");
  }
};
</script>

<template>
  <header
    class="bg-white dark:bg-slate-800 h-14 sm:h-16 flex items-center justify-between px-3 sm:px-4 md:px-6 sticky top-0 z-10 shadow-sm gap-2 transition-colors duration-300"
  >
    <!-- Mobile Menu Button -->
    <button
      @click="$emit('toggle-sidebar')"
      class="lg:hidden w-9 h-9 sm:w-10 sm:h-10 rounded-full flex items-center justify-center text-slate-500 hover:bg-gray-100 dark:text-slate-400 dark:hover:bg-slate-700 flex-shrink-0"
    >
      <i class="fa-solid fa-bars"></i>
    </button>

    <!-- Title -->
    <h1 class="text-sm sm:text-base md:text-lg font-bold text-slate-800 dark:text-white truncate">
      {{ title }}
    </h1>

    <!-- Right Actions -->
    <div class="flex items-center gap-1 sm:gap-2 md:gap-3">
      <!-- Slot for extra actions (like search) -->
      <slot></slot>

      <!-- Dark Mode Toggle -->
      <button
        @click="toggleDarkMode"
        class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center text-slate-500 hover:bg-gray-100 dark:text-slate-400 dark:hover:bg-slate-700 transition-colors"
      >
        <i :class="['fa-solid', isDark ? 'fa-sun' : 'fa-moon']"></i>
      </button>

      <!-- Notifications -->
      <button
        class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center text-slate-500 hover:bg-gray-100 dark:text-slate-400 dark:hover:bg-slate-700 relative"
      >
        <i class="fa-solid fa-bell text-sm sm:text-base"></i>
        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
      </button>

      <!-- Profile Dropdown -->
      <div class="relative">
        <button
          @click="profileDropdownOpen = !profileDropdownOpen"
          class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-orange-500 overflow-hidden flex-shrink-0 focus:outline-none"
        >
          <img
            :src="
              userStore.user.avatar
                ? `http://localhost:8000/storage/${userStore.user.avatar}`
                : `https://ui-avatars.com/api/?name=${userStore.user.first_name}`
            "
            class="w-full h-full"
          />
        </button>

        <div
          v-if="profileDropdownOpen"
          class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 border border-gray-100 z-50"
        >
          <div class="profile-dropdown-header p-3 sm:p-4 border-b border-border">
            <h4 class="text-sm font-bold mb-1 truncate">{{ userStore.user.first_name }}</h4>
            <p class="text-xs text-text-light truncate">{{ userStore.user.email }}</p>
          </div>
          <hr />
          <router-link
            to="/admin/settings"
            class="block px-4 py-2 text-sm text-slate-700 hover:bg-orange-50 hover:text-orange-500 transition-colors"
          >
            <i class="fa-solid fa-user-gear mr-2"></i>Settings
          </router-link>
          <div class="border-t border-gray-100 my-1"></div>
          <router-link
            to="/"
            class="block px-4 py-2 text-sm text-slate-700 hover:bg-orange-50 hover:text-orange-500 transition-colors"
          >
            <i class="fa-solid fa-right-from-bracket mr-2"></i> Switch to Admin
          </router-link>
        </div>
      </div>
    </div>
  </header>
</template>
