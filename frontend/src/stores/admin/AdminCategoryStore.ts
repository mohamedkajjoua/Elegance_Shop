import AdminCategoryService from "@/services/AdminCategoryService";
import { defineStore } from "pinia";
import { ref } from "vue";

export const useAdminCategoryStore = defineStore("adminCategory", () => {
  const categories = ref([]);
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
      error.value = err.message || "Failed to fetch";
    } finally {
      isLoading.value = false;
    }
  }

  async function updateCategory(data: any, id: number) {
    isLoading.value = true;
    error.value = null;
    try {
      await AdminCategoryService.update(data, id);

      await fetchCategory();
    } catch (err: any) {
      console.error("failed to update category");
      error.value = err.response?.data?.message || err.message;
      throw err;
    } finally {
      isLoading.value = false;
    }
  }

  async function createCategory(data: any) {
    isLoading.value = true;
    error.value = null;
    try {
      await AdminCategoryService.create(data);

      await fetchCategory();
    } catch (err: any) {
      console.error("failed to create category");
      error.value = err.response?.data?.message || err.message;
      throw err;
    } finally {
      isLoading.value = false;
    }
  }

  async function deleteCategory(id: number) {
    isLoading.value = true;
    error.value = null;
    try {
      await AdminCategoryService.delete(id);

      await fetchCategory();
    } catch (err: any) {
      console.error("failed to delete category");
      error.value = err.response?.data?.message || err.message;
      throw err;
    } finally {
      isLoading.value = false;
    }
  }

  return {
    categories,
    isLoading,
    error,

    fetchCategory,
    updateCategory,
    createCategory,
    deleteCategory,
  };
});
