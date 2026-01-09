import AdminCategoryService from "@/services/AdminCategoryService";
import { defineStore } from "pinia";
import { ref } from "vue";

export const useAdminCategoryStore = defineStore("adminCategory", () => {
  const categories = ref();
  const isLoading = ref(false);
  const error = ref(null);

  async function fetchCategory() {
    isLoading.value = true;
    error.value = null;

    try {
      const res = await AdminCategoryService.getAll();
      categories.value = res.data.data || res.data;
    } catch (err: any) {
      console.error("error to fetch category");
    } finally {
      isLoading.value = false;
    }
  }

  return {
    categories,
    isLoading,
    error,

    fetchCategory,
  };
});
