<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth/auth";

const router = useRouter();
const authStore = useAuthStore();

const email = ref("");
const password = ref("");
const showPassword = ref(false);
const rememberMe = ref(false);
const isLoading = ref(false);
const validationErrors = ref<Record<string, string[]>>({});
function togglePassword() {
  showPassword.value = !showPassword.value;
}

async function handleLogin() {
  validationErrors.value = {};

  const res = await authStore.login({
    email: email.value,
    password: password.value,
  });

  if (res.success) {
    if (authStore.isAdmin) {
      router.push("/admin");
    } else {
      router.push("/");
    }
  } else {
    if (res.errors) {
      validationErrors.value = res.errors;
    }
  }
}
</script>

<template>
  <p v-if="authStore.error" class="text-red-600 text-center">
    {{ authStore.error }}
  </p>
  <div class="min-h-[calc(100vh-120px)] flex items-center justify-center p-4">
    <div
      class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden"
      style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px)"
    >
      <div class="p-8">
        <div class="text-center mb-8">
          <div
            class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-primary text-white mb-4"
          >
            <i class="fa-solid fa-shapes text-xl"></i>
          </div>
          <h2 class="text-2xl font-bold text-gray-900">Welcome Back</h2>
          <p class="text-gray-500 mt-2">Please enter your details to sign in</p>
        </div>

        <form @submit.prevent="handleLogin" class="space-y-6">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2"
              >Email Address</label
            >
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-regular fa-envelope text-gray-400"></i>
              </div>
              <input
                v-model="email"
                :class="{ 'border-red-500': validationErrors.email }"
                type="email"
                id="email"
                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-primary focus:border-primary transition-colors"
                placeholder="Enter your email"
                required
              />
            </div>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2"
              >Password</label
            >
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-solid fa-lock text-gray-400"></i>
              </div>
              <input
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                :class="{ 'border-red-500': validationErrors.password }"
                id="password"
                class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl focus:ring-primary focus:border-primary transition-colors"
                placeholder="••••••••"
                required
              />
              <button
                type="button"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                @click="togglePassword"
              >
                <i :class="showPassword ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"></i>
              </button>
            </div>
          </div>

          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input
                v-model="rememberMe"
                id="remember-me"
                type="checkbox"
                class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
              />
              <label for="remember-me" class="ml-2 block text-sm text-gray-700">Remember me</label>
            </div>
            <div class="text-sm">
              <a href="#" class="font-medium text-primary hover:text-primary-dark"
                >Forgot password?</a
              >
            </div>
          </div>

          <button
            type="submit"
            :disabled="authStore.isLoading"
            :class="[
              'w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-primary transition-all transform hover:scale-[1.02]',
              isLoading ? 'opacity-75 cursor-not-allowed' : 'hover:bg-primary-dark',
            ]"
          >
            <template v-if="authStore.isLoading">
              <i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Signing in...
            </template>
            <template v-else> Sign In </template>
          </button>

          <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-2 bg-white text-gray-500">Or continue with</span>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <button
              type="button"
              class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-xl shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors"
            >
              <i class="fa-brands fa-google text-red-500 mr-2"></i> Google
            </button>
            <button
              type="button"
              class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-xl shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors"
            >
              <i class="fa-brands fa-facebook text-blue-600 mr-2"></i> Facebook
            </button>
          </div>
        </form>

        <p class="mt-8 text-center text-sm text-gray-600">
          Don't have an account?
          <router-link to="/register" class="font-medium text-primary hover:text-primary-dark"
            >Sign up now</router-link
          >
        </p>
      </div>
    </div>
  </div>
</template>
