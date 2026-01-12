<script setup>
import { ref, onMounted } from "vue";
import AdminHeader from "@/components/admin/AdminHeader.vue";
import AdminNav from "@/components/admin/AdminNav.vue";
import { useAdminBrandStore } from "@/stores/admin/AdminBrandStore";
import { useRouter, useRoute } from "vue-router";

const sidebarOpen = ref(false);
const router = useRouter();
const route = useRoute();

const brandStore = useAdminBrandStore();
const form = ref({ name: "" });

const brandId = route.params.id;

onMounted(async () => {
  if (brandStore.brands.length === 0) {
    await brandStore.fetchBrands();
  }

  const brand = brandStore.brands.find((b) => b.id == brandId);

  if (brand) {
    form.value.name = brand.name;
  } else {
    router.push("/admin/brand");
  }
});

const handleSubmit = async () => {
  try {
    await brandStore.updateBrand(form.value, brandId);
    router.push("/admin/brand");
  } catch (err) {
    console.error("Failed to update brand:", err);
  }
};
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex">
    <AdminNav :isOpen="sidebarOpen" @close="sidebarOpen = false" />

    <div class="flex-1 lg:ml-64">
      <AdminHeader title="EDIT BRAND" @toggle-sidebar="sidebarOpen = true">
        <div class="relative hidden md:block">
          <input
            type="text"
            placeholder="Search..."
            class="w-32 lg:w-48 pl-10 pr-4 py-2 bg-gray-100 rounded-lg text-sm"
          />
          <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
      </AdminHeader>

      <main class="p-4 md:p-6">
        <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
          <router-link to="/admin/brand" class="hover:text-orange-500">Brand</router-link>
          <i class="fa-solid fa-chevron-right text-xs"></i>
          <span class="text-slate-800 font-medium">Edit Brand</span>
        </div>

        <div class="max-w-2xl">
          <div class="bg-white rounded-lg md:rounded-xl p-4 md:p-6 shadow-sm">
            <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4 md:mb-6">
              Brand Details
            </h3>

            <div v-if="brandStore.isLoading" class="text-center py-6">
              <i class="fa-solid fa-spinner fa-spin text-orange-500 text-2xl"></i>
            </div>

            <form v-else @submit.prevent="handleSubmit" class="space-y-4">
              <div class="space-y-5">
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">
                    Brand Name <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="form.name"
                    type="text"
                    placeholder="Enter brand name"
                    class="w-full px-3 md:px-4 py-2.5 md:py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm md:text-base"
                  />
                </div>
              </div>

              <div
                class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 mt-6 md:mt-8 pt-4 md:pt-6 border-t border-gray-100"
              >
                <router-link
                  to="/admin/brand"
                  class="w-full sm:w-auto px-4 md:px-6 py-2.5 md:py-3 border border-gray-200 rounded-lg font-medium text-slate-600 hover:bg-gray-50 text-center text-sm md:text-base"
                >
                  Cancel
                </router-link>
                <button
                  type="submit"
                  :disabled="brandStore.isLoading"
                  class="w-full sm:w-auto px-4 md:px-6 py-2.5 md:py-3 bg-orange-500 text-white rounded-lg font-medium hover:bg-orange-600 text-sm md:text-base disabled:opacity-50"
                >
                  <i v-if="brandStore.isLoading" class="fa-solid fa-spinner fa-spin mr-2"></i>
                  <i v-else class="fa-solid fa-save mr-2"></i> Update Brand
                </button>
              </div>
            </form>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>
