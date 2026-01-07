<script setup>
import { useRouter } from "vue-router";
import { useUserStore } from "@/stores/user";
import { computed } from "vue";

const router = useRouter();
const userStore = useUserStore();

const isAuthenticated = computed(() => userStore.isAuthenticated);

function goHome() {
  router.push("/");
}

function goToLogin() {
  router.push("/login");
}

function goBack() {
  router.back();
}
</script>

<template>
  <div
    class="min-h-[calc(100vh-120px)] flex items-center justify-center p-4 bg-gradient-to-br from-red-50 to-orange-50"
  >
    <div class="max-w-2xl w-full text-center">
      <!-- Error Card -->
      <div
        class="bg-white rounded-3xl shadow-2xl overflow-hidden p-8 md:p-12"
        style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px)"
      >
        <!-- Lock Icon -->
        <div class="relative mb-8 flex justify-center">
          <div class="relative">
            <div
              class="w-32 h-32 rounded-full bg-gradient-to-br from-red-100 to-orange-100 flex items-center justify-center"
            >
              <i class="fa-solid fa-shield-halved text-6xl text-red-500"></i>
            </div>
            <div
              class="absolute -bottom-2 -right-2 w-12 h-12 rounded-full bg-red-500 flex items-center justify-center shadow-lg"
            >
              <i class="fa-solid fa-lock text-white text-xl"></i>
            </div>
          </div>
        </div>

        <!-- Error Code -->
        <div
          class="text-7xl md:text-8xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-orange-500 leading-none mb-4"
        >
          403
        </div>

        <!-- Error Message -->
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Access Forbidden</h1>
        <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">
          <template v-if="!isAuthenticated">
            You need to be logged in to access this page. Please sign in to continue.
          </template>
          <template v-else>
            You don't have permission to access this resource. This area is restricted.
          </template>
        </p>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
          <template v-if="!isAuthenticated">
            <button
              @click="goToLogin"
              class="flex items-center justify-center gap-2 px-6 py-3 bg-primary text-white font-bold rounded-xl shadow-lg hover:bg-primary-dark transition-all transform hover:scale-[1.02]"
            >
              <i class="fa-solid fa-right-to-bracket"></i>
              Sign In
            </button>
            <button
              @click="goHome"
              class="flex items-center justify-center gap-2 px-6 py-3 bg-white text-gray-700 font-medium border-2 border-gray-300 rounded-xl hover:bg-gray-50 transition-all"
            >
              <i class="fa-solid fa-home"></i>
              Go Home
            </button>
          </template>
          <template v-else>
            <button
              @click="goHome"
              class="flex items-center justify-center gap-2 px-6 py-3 bg-primary text-white font-bold rounded-xl shadow-lg hover:bg-primary-dark transition-all transform hover:scale-[1.02]"
            >
              <i class="fa-solid fa-home"></i>
              Back to Home
            </button>
            <button
              @click="goBack"
              class="flex items-center justify-center gap-2 px-6 py-3 bg-white text-gray-700 font-medium border-2 border-gray-300 rounded-xl hover:bg-gray-50 transition-all"
            >
              <i class="fa-solid fa-arrow-left"></i>
              Go Back
            </button>
          </template>
        </div>

        <!-- Info Box -->
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
          <div class="flex items-start gap-3">
            <i class="fa-solid fa-circle-info text-amber-600 text-xl mt-0.5"></i>
            <div class="text-left">
              <h3 class="font-semibold text-amber-900 mb-1">Why am I seeing this?</h3>
              <ul class="text-sm text-amber-800 space-y-1">
                <li v-if="!isAuthenticated">• You are not logged in to your account</li>
                <li v-else>• You don't have the required permissions</li>
                <li>• The page is restricted to certain user roles</li>
                <li>• Your session may have expired</li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Helpful Links -->
        <div class="border-t border-gray-200 pt-8">
          <p class="text-sm font-semibold text-gray-700 mb-4">Need help?</p>
          <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            <router-link
              to="/contact"
              class="flex flex-col items-center gap-2 p-4 rounded-xl hover:bg-gray-50 transition-colors group"
            >
              <div
                class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors"
              >
                <i class="fa-solid fa-headset text-primary text-xl"></i>
              </div>
              <span class="text-sm font-medium text-gray-700">Contact Us</span>
            </router-link>

            <router-link
              to="/about"
              class="flex flex-col items-center gap-2 p-4 rounded-xl hover:bg-gray-50 transition-colors group"
            >
              <div
                class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors"
              >
                <i class="fa-solid fa-circle-question text-primary text-xl"></i>
              </div>
              <span class="text-sm font-medium text-gray-700">Help Center</span>
            </router-link>

            <router-link
              to="/shop"
              class="flex flex-col items-center gap-2 p-4 rounded-xl hover:bg-gray-50 transition-colors group"
            >
              <div
                class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors"
              >
                <i class="fa-solid fa-shopping-bag text-primary text-xl"></i>
              </div>
              <span class="text-sm font-medium text-gray-700">Shop Now</span>
            </router-link>
          </div>
        </div>
      </div>

      <!-- Additional Help Text -->
      <p class="mt-6 text-sm text-gray-500">
        Error Code: 403 | Need assistance?
        <router-link to="/contact" class="text-primary hover:text-primary-dark font-medium"
          >Contact our support team</router-link
        >
      </p>
    </div>
  </div>
</template>
