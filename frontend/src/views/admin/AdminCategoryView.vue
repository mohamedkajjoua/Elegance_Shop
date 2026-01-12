<script setup>
import { onMounted, ref } from "vue";
import AdminHeader from "@/components/admin/AdminHeader.vue";
import AdminNav from "@/components/admin/AdminNav.vue";
import { useAdminCategoryStore } from "@/stores/admin/AdminCategoryStore";

const sidebarOpen = ref(false);
const categoryStore = useAdminCategoryStore();

const handleDelete = async (id) => {
  if (confirm("Are you sure you want to delete this category?")) {
    await categoryStore.deleteCategory(id);
  }
};

onMounted(async () => {
  await categoryStore.fetchCategory();
});
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex">
    <AdminNav :isOpen="sidebarOpen" @close="sidebarOpen = false" />

    <div class="flex-1 lg:ml-64">
      <AdminHeader title="CATEGORY LIST" @toggle-sidebar="sidebarOpen = true">
        <div class="relative hidden md:block">
          <input
            type="text"
            placeholder="Search..."
            class="w-32 lg:w-48 pl-10 pr-4 py-2 bg-gray-100 rounded-lg text-sm"
          />
          <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
      </AdminHeader>

      <main class="p-3 md:p-6">
        <div v-if="categoryStore.isLoading" class="text-center py-10">
          <i class="fa-solid fa-spinner fa-spin text-3xl text-orange-500"></i>
        </div>

        <div v-else>
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 md:mb-6">
            <h2 class="text-lg md:text-xl font-semibold text-slate-800">All Categories</h2>
            <div class="flex items-center gap-2 md:gap-3">
              <router-link
                to="/admin/category/create"
                class="px-3 md:px-4 py-2 bg-orange-500 text-white rounded-lg font-medium hover:bg-orange-600 text-sm md:text-base"
              >
                <i class="fa-solid fa-plus mr-1 md:mr-2"></i>
                <span class="hidden sm:inline">Add</span> Category
              </router-link>
            </div>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 md:gap-4">
            <div
              v-for="category in categoryStore.categories"
              :key="category.id"
              class="bg-white rounded-lg md:rounded-xl p-3 md:p-5 shadow-sm hover:shadow-md transition-shadow"
            >
              <div class="flex items-center justify-between mb-2 md:mb-4">
                <!--     <div
                  class="w-10 h-10 md:w-14 md:h-14 rounded-lg md:rounded-xl bg-blue-100 flex items-center justify-center overflow-hidden"
                >
                  <img
                    v-if="category.image"
                    :src="category.image"
                    class="w-full h-full object-cover"
                    alt="cat"
                  />
                  <i v-else class="fa-solid fa-shirt text-lg md:text-2xl text-blue-500"></i>
                </div> -->

                <div class="flex gap-1">
                  <router-link
                    :to="`/admin/category/${category.id}/edit`"
                    class="w-6 h-6 md:w-8 md:h-8 rounded-lg text-blue-500 hover:bg-blue-50 flex items-center justify-center"
                  >
                    <i class="fa-solid fa-pen text-xs md:text-sm"></i>
                  </router-link>

                  <button
                    @click="handleDelete(category.id)"
                    class="w-6 h-6 md:w-8 md:h-8 rounded-lg text-red-500 hover:bg-red-50 flex items-center justify-center"
                  >
                    <i v-if="categoryStore.isLoading" class="fa-solid fa-spinner fa-spin"></i>
                    <i v-else class="fa-solid fa-trash text-xs md:text-sm"></i>
                  </button>
                </div>
              </div>

              <h4 class="font-semibold text-slate-800 mb-0.5 md:mb-1 text-sm md:text-base">
                {{ category.name }}
              </h4>
              <p class="text-xs md:text-sm text-slate-500">
                {{ category.products_count || 0 }} Products
              </p>
            </div>

            <router-link
              to="/admin/category/create"
              class="bg-white rounded-lg md:rounded-xl p-3 md:p-5 shadow-sm border-2 border-dashed border-gray-200 hover:border-orange-500 flex items-center justify-center cursor-pointer transition-colors group min-h-[100px] md:min-h-[140px]"
            >
              <div class="text-center">
                <div
                  class="w-10 h-10 md:w-14 md:h-14 rounded-lg md:rounded-xl bg-gray-100 group-hover:bg-orange-100 flex items-center justify-center mx-auto mb-1 md:mb-2 transition-colors"
                >
                  <i
                    class="fa-solid fa-plus text-base md:text-xl text-gray-400 group-hover:text-orange-500 transition-colors"
                  ></i>
                </div>
                <p
                  class="text-xs md:text-sm text-slate-500 group-hover:text-orange-500 transition-colors"
                >
                  Add Category
                </p>
              </div>
            </router-link>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>
