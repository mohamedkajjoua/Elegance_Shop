import AdminBrandService from "@/services/AdminBrandService";
import AdminCategoryService from "@/services/AdminCategoryService";
import { defineStore } from "pinia";
import { ref } from "vue";

export const useAdminBrandStore = defineStore("adminBrand", () => {
  const brands = ref();
  const isLoading = ref(false);
  const error = ref(null);

  async function fetchBrand() {
    isLoading.value = true;
    error.value = null;

    try {
      const res = await AdminBrandService.getAll();
      brands.value = res.data.data || res.data;
    } catch (err: any) {
      console.error("error to fetch brand");
    } finally {
      isLoading.value = false;
    }
  }

  return {
    brands,
    isLoading,
    error,

    fetchBrand,
  };
});
