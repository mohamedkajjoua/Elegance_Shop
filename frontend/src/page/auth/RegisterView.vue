<template>
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
          <h2 class="text-2xl font-bold text-gray-900">Create Account</h2>
          <p class="text-gray-500 mt-2">Sign up to get started with lapakbaju</p>
        </div>

        <form @submit.prevent="handleRegister" class="space-y-5">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
              <input
                v-model="formData.firstName"
                type="text"
                class="block w-full px-3 py-3 border border-gray-300 rounded-xl focus:ring-primary focus:border-primary transition-colors"
                placeholder="John"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
              <input
                v-model="formData.lastName"
                type="text"
                class="block w-full px-3 py-3 border border-gray-300 rounded-xl focus:ring-primary focus:border-primary transition-colors"
                placeholder="Doe"
                required
              />
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-regular fa-envelope text-gray-400"></i>
              </div>
              <input
                v-model="formData.email"
                type="email"
                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-primary focus:border-primary transition-colors"
                placeholder="john@example.com"
                required
              />
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-solid fa-lock text-gray-400"></i>
              </div>
              <input
                v-model="formData.password"
                :type="showPassword ? 'text' : 'password'"
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

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
            <input
              v-model="formData.confirmPassword"
              :type="showPassword ? 'text' : 'password'"
              class="block w-full px-3 py-3 border border-gray-300 rounded-xl focus:ring-primary focus:border-primary transition-colors"
              placeholder="••••••••"
              required
            />
          </div>

          <div class="flex items-center">
            <input
              v-model="agreeTerms"
              id="terms"
              type="checkbox"
              class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
            />
            <label for="terms" class="ml-2 block text-sm text-gray-700">
              I agree to the
              <a href="#" class="text-primary hover:underline">Terms of Service</a> and
              <a href="#" class="text-primary hover:underline">Privacy Policy</a>
            </label>
          </div>

          <button
            type="submit"
            :disabled="isLoading"
            :class="[
              'w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-primary transition-all transform hover:scale-[1.02]',
              isLoading ? 'opacity-75 cursor-not-allowed' : 'hover:bg-primary-dark',
            ]"
          >
            <template v-if="isLoading">
              <i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Creating account...
            </template>
            <template v-else> Create Account </template>
          </button>
        </form>

        <p class="mt-8 text-center text-sm text-gray-600">
          Already have an account?
          <router-link to="/login" class="font-medium text-primary hover:text-primary-dark"
            >Sign in</router-link
          >
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();

const formData = ref({
  firstName: "",
  lastName: "",
  email: "",
  password: "",
  confirmPassword: "",
});
const showPassword = ref(false);
const agreeTerms = ref(false);
const isLoading = ref(false);

function togglePassword() {
  showPassword.value = !showPassword.value;
}

function handleRegister() {
  if (formData.value.password !== formData.value.confirmPassword) {
    alert("Passwords do not match!");
    return;
  }

  if (!agreeTerms.value) {
    alert("Please agree to the terms and conditions");
    return;
  }

  isLoading.value = true;

  // Simulate API call
  setTimeout(() => {
    router.push("/login");
  }, 1500);
}
</script>
