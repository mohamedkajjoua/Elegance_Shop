<script setup>
import { onMounted, ref } from "vue";
import AdminHeader from "@/components/admin/AdminHeader.vue";
import AdminNav from "@/components/admin/AdminNav.vue";
import { useSettingStore } from "@/stores/admin/adminSettingStore";

const sidebarOpen = ref(false);
const settingStore = useSettingStore();

onMounted(() => {
  settingStore.fetchSettings();
});

const saveChanges = () => {
  settingStore.saveSettings();
};
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex">
    <AdminNav :isOpen="sidebarOpen" @close="sidebarOpen = false" />

    <div class="flex-1 lg:ml-64">
      <AdminHeader title="SETTINGS" @toggle-sidebar="sidebarOpen = true">
        <div class="relative hidden md:block">
          <input
            type="text"
            placeholder="Search..."
            class="w-32 lg:w-48 pl-9 pr-3 py-2 bg-gray-100 rounded-lg text-sm"
          />
          <i
            class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"
          ></i>
        </div>
      </AdminHeader>

      <main class="p-3 sm:p-4 md:p-6">
        <form @submit.prevent="saveChanges">
          <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-bold text-slate-800 mb-4">General Settings</h2>
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Site Name</label>
                <input
                  v-model="settingStore.settings.site_name"
                  type="text"
                  class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1"
                  >Site Description</label
                >
                <textarea
                  v-model="settingStore.settings.site_description"
                  rows="3"
                  class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                ></textarea>
              </div>
              <div class="flex items-center gap-2">
                <input
                  type="checkbox"
                  id="maintenance"
                  class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500"
                />
                <label for="maintenance" class="text-sm text-slate-700">Maintenance Mode</label>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
            <h2 class="text-xl font-bold text-slate-800 mb-4">Contact Information</h2>
            <div class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-1">Call Us</label>
                  <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                      <i class="fa-solid fa-phone"></i>
                    </span>
                    <input
                      v-model="settingStore.settings.call_us"
                      type="text"
                      placeholder="+1 (555) 000-0000"
                      class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                    />
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-1">Email Us</label>
                  <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                      <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input
                      v-model="settingStore.settings.email_us"
                      type="email"
                      placeholder="support@Elegance Shop.com"
                      class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                    />
                  </div>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Visit Us</label>
                <div class="relative">
                  <span class="absolute left-4 top-3 text-slate-400">
                    <i class="fa-solid fa-location-dot"></i>
                  </span>
                  <textarea
                    v-model="settingStore.settings.visit_us"
                    rows="3"
                    placeholder="123 Fashion Street, New York, NY 10001"
                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                  ></textarea>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
            <h2 class="text-xl font-bold text-slate-800 mb-4">Social Media Links</h2>
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Instagram</label>
                <div class="relative">
                  <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <i class="fa-brands fa-instagram"></i>
                  </span>
                  <input
                    v-model="settingStore.settings.instagram"
                    type="text"
                    placeholder="https://instagram.com/Elegance Shop"
                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                  />
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Facebook</label>
                <div class="relative">
                  <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <i class="fa-brands fa-facebook-f"></i>
                  </span>
                  <input
                    v-model="settingStore.settings.facebook"
                    type="text"
                    placeholder="https://facebook.com/Elegance Shop"
                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                  />
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">X (Twitter)</label>
                <div class="relative">
                  <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <i class="fa-brands fa-x"></i>
                  </span>
                  <input
                    v-model="settingStore.settings.x"
                    type="text"
                    placeholder="https://x.com/Elegance Shop"
                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                  />
                </div>
              </div>
              <button
                type="submit"
                :disabled="settingStore.isLoading"
                class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors disabled:opacity-50"
              >
                {{ settingStore.isLoading ? "Saving..." : "Save Changes" }}
              </button>
            </div>
          </div>
        </form>
      </main>
    </div>
  </div>
</template>
