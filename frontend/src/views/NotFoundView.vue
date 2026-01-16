<script setup>
import { useRouter } from "vue-router";

const router = useRouter();

// دالة للعودة للصفحة السابقة
const goBack = () => {
  router.back();
};
</script>

<template>
  <div
    class="min-h-screen bg-white flex flex-col items-center justify-center px-4 sm:px-6 lg:px-8 relative overflow-hidden"
  >
    <div class="absolute inset-0 opacity-10 pointer-events-none">
      <div
        class="absolute top-0 left-1/4 w-96 h-96 bg-[#5F2EEA] rounded-full mix-blend-multiply filter blur-3xl animate-blob"
      ></div>
      <div
        class="absolute top-0 right-1/4 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"
      ></div>
    </div>

    <div class="relative text-center z-10 max-w-lg w-full">
      <h1 class="text-9xl font-black text-gray-100 select-none sm:text-[12rem]">404</h1>

      <div class="absolute inset-0 flex flex-col items-center justify-center top-8 sm:top-12">
        <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Page Not Found</h2>
        <p class="mt-4 text-base text-slate-500 max-w-xs sm:max-w-md mx-auto">
          Sorry, we couldn’t find the page you’re looking for. It might have been moved or deleted.
        </p>
      </div>
    </div>

    <div class="mt-10 flex items-center justify-center gap-4 relative z-10">
      <button
        @click="goBack"
        class="rounded-lg px-5 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 shadow-sm hover:bg-slate-50 hover:text-[#5F2EEA] transition-all duration-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#5F2EEA]"
      >
        <span aria-hidden="true">&larr;</span> Go Back
      </button>

      <router-link
        to="/"
        class="rounded-lg px-5 py-2.5 text-sm font-semibold text-white bg-[#5F2EEA] shadow-sm hover:bg-[#7a52f4] transition-all duration-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#5F2EEA]"
      >
        Take me Home
      </router-link>
    </div>
  </div>
</template>

<style scoped>
/* حركة بسيطة للخلفية */
@keyframes blob {
  0% {
    transform: translate(0px, 0px) scale(1);
  }
  33% {
    transform: translate(30px, -50px) scale(1.1);
  }
  66% {
    transform: translate(-20px, 20px) scale(0.9);
  }
  100% {
    transform: translate(0px, 0px) scale(1);
  }
}
.animate-blob {
  animation: blob 7s infinite;
}
.animation-delay-2000 {
  animation-delay: 2s;
}
</style>
