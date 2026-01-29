<template>
  <p v-if="authStore.success" class="text-green-600 text-center">
    {{ authStore.success }}
  </p>

  <p v-if="authStore.error" class="text-red-600 text-center">
    {{ authStore.error }}
  </p>
  <div class="min-h-[calc(100vh-120px)] flex items-center justify-center p-4">
    <div
      class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden"
      style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px)"
    >
      <div class="p-6 sm:p-8">
        <div class="text-center mb-6 sm:mb-8">
          <div
            class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-primary text-white mb-4"
          >
            <i class="fa-solid fa-shapes text-xl"></i>
          </div>
          <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Create Account</h2>
          <p class="text-gray-500 mt-2 text-sm sm:text-base">
            Sign up to get started with Elegance Shop
          </p>
        </div>

        <form @submit.prevent="handleRegister" class="space-y-4 sm:space-y-5">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
              <input
                v-model="formData.first_name"
                :class="{ 'border-red-500': validationErrors.first_name }"
                type="text"
                class="block w-full px-3 py-3 border border-gray-300 rounded-xl focus:ring-primary focus:border-primary transition-colors"
                placeholder="Kif"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
              <input
                v-model="formData.last_name"
                :class="{ 'border-red-500': validationErrors.last_name }"
                type="text"
                class="block w-full px-3 py-3 border border-gray-300 rounded-xl focus:ring-primary focus:border-primary transition-colors"
                placeholder="Mohamed"
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
                :class="{ 'border-red-500': validationErrors.email }"
                type="email"
                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-primary focus:border-primary transition-colors"
                placeholder="john@example.com"
                required
              />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2"> Phone</label>
            <div class="flex">
              <select
                v-model="selectedDialCode"
                class="py-3 border border-gray-300 rounded-xl focus:ring-primary focus:border-primary transition-colors bg-white flex-shrink-0"
                style="min-width: 160px"
                aria-label="Country dial code"
              >
                <option v-for="c in countriesList" :key="c.code" :value="c.dial_code">
                  {{ c.name }} {{ c.dial_code }}
                </option>
              </select>

              <div class="relative flex-1 ml-2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="fa-solid fa-mobile-screen text-gray-400"></i>
                </div>
                <input
                  v-model="formData.phone"
                  :class="{ 'border-red-500': validationErrors.phone }"
                  type="text"
                  class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-primary focus:border-primary transition-colors"
                  placeholder="621000603"
                  required
                />
              </div>
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
                :class="{ 'border-red-500': validationErrors.password }"
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
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-solid fa-lock text-gray-400"></i>
              </div>
              <input
                v-model="formData.password_confirmation"
                :class="{ 'border-red-500': validationErrors.password_confirmation }"
                :type="showPassword ? 'text' : 'password'"
                class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl focus:ring-primary focus:border-primary transition-colors"
                placeholder="••••••••"
                required
              />
            </div>
          </div>

          <div class="flex items-start sm:items-center">
            <input
              v-model="agreeTerms"
              id="terms"
              type="checkbox"
              class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded mt-0.5 sm:mt-0"
            />
            <label for="terms" class="ml-2 block text-xs sm:text-sm text-gray-700">
              I agree to the
              <a href="#" class="text-primary hover:underline">Terms of Service</a> and
              <a href="#" class="text-primary hover:underline">Privacy Policy</a>
            </label>
          </div>

          <button
            type="submit"
            :disabled="authStore.isLoading"
            :class="[
              'w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-primary transition-all transform hover:scale-[1.02]',
              authStore.isLoading ? 'opacity-75 cursor-not-allowed' : 'hover:bg-primary-dark',
            ]"
          >
            <template v-if="isLoading">
              <i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Creating account...
            </template>
            <template v-else> Create Account </template>
          </button>
        </form>

        <p class="mt-6 sm:mt-8 text-center text-sm text-gray-600">
          Already have an account?
          <router-link to="/login" class="font-medium text-primary hover:text-primary-dark"
            >Sign in</router-link
          >
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { IRegisterRequest } from "../types/authTypes";
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth/auth";
import countries from "@/data/countries";

const router = useRouter();
const authStore = useAuthStore();
const showPassword = ref(false);
const agreeTerms = ref(false);
const isLoading = false;

// phone handling: separate dial code selector and local phone input
const countriesList = countries;
const selectedDialCode = ref<string>("+212"); // default to Morocco as requested

const formData = reactive<IRegisterRequest>({
  first_name: "",
  last_name: "",
  email: "",
  phone: "",
  role: "customer",
  password: "",
  password_confirmation: "",
});

// error :coming from the backend
const validationErrors = ref<Record<string, string[]>>({});

function togglePassword() {
  showPassword.value = !showPassword.value;
}

const handleRegister = async () => {
  validationErrors.value = {};

  // If user already entered full phone with +, use it. Otherwise prepend selected dial code.
  let phoneValue = formData.phone || "";
  if (!phoneValue.startsWith("+")) {
    // remove non-digits then prepend dial code (without the '+' to match backend length limits)
    const digits = phoneValue.replace(/[^0-9]/g, "");
    const dial = selectedDialCode.value.replace(/^\+/, "");
    phoneValue = `${dial}${digits}`;

    // client-side validation: backend expects min:7 max:12
    const length = phoneValue.length;
    if (length < 7 || length > 12) {
      validationErrors.value = {
        ...(validationErrors.value || {}),
        phone: ["Phone must be between 7 and 12 digits"],
      } as Record<string, string[]>;
      return;
    }
  }
  const payload: IRegisterRequest = {
    ...formData,
    phone: phoneValue,
  };

  const res = await authStore.register(payload);

  if (res.success) {
    router.push("/login");
  } else if (res.errors) {
    validationErrors.value = res.errors;
  }
};
</script>
