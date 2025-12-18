<template>
  <div class="min-h-screen bg-background text-text font-sans antialiased">
    <div class="max-w-[1440px] mx-auto px-6">
      <router-view />
    </div>
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
input[type="range"]::-webkit-slider-thumb {
  -webkit-appearance: none;
  height: 16px;
  width: 16px;
  border-radius: 50%;
  background: #ffffff;
  border: 2px solid #5f2eea;
  cursor: pointer;
  margin-top: -6px;
}

input[type="range"]::-webkit-slider-runnable-track {
  width: 100%;
  height: 4px;
  cursor: pointer;
  background: #e4e4e4;
  border-radius: 2px;
}

/* Profile dropdown active state */
.profile-dropdown.active {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}
</style>
