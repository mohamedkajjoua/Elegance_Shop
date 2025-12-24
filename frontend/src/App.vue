<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import AppHeader from '@/components/layout/AppHeader.vue'
import AppFooter from '@/components/layout/AppFooter.vue'

const route = useRoute()

// Check if current route is an admin page
const isAdminPage = computed(() => route.path.startsWith('/admin'))
</script>

<template>
  <!-- Admin Layout - No header/footer -->
  <div v-if="isAdminPage">
    <router-view />
  </div>
  
  <!-- User Layout - With header/footer -->
  <div v-else class="min-h-screen bg-background text-text font-sans antialiased flex flex-col">
    <div class="flex-1 max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 w-full">
      <AppHeader />
      <router-view />
    </div>
    <AppFooter />
  </div>
</template>

<style>
/* Custom scrollbar for cleaner look */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}

/* Range slider custom styling */
input[type=range]::-webkit-slider-thumb {
  -webkit-appearance: none;
  height: 16px;
  width: 16px;
  border-radius: 50%;
  background: #ffffff;
  border: 2px solid #5F2EEA;
  cursor: pointer;
  margin-top: -6px;
}

input[type=range]::-webkit-slider-runnable-track {
  width: 100%;
  height: 4px;
  cursor: pointer;
  background: #E4E4E4;
  border-radius: 2px;
}

/* Profile dropdown active state */
.profile-dropdown.active {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}
</style>
