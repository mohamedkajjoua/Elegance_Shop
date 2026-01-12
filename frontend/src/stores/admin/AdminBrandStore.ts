import AdminBrandService from "@/services/AdminBrandService";
import { defineStore } from "pinia";
import { ref } from "vue";

export const useAdminBrandStore = defineStore("adminBrand", () => {
  // ✅ تغيير القيمة الابتدائية إلى مصفوفة فارغة لتجنب أخطاء undefined في v-for
  const brands = ref([]);
  const isLoading = ref(false);
  const error = ref(null);

  async function fetchBrand() {
    isLoading.value = true;
    error.value = null;

    try {
      const res = await AdminBrandService.getAll();
      // دعم للهيكليات المختلفة للـ API (Res.data أو Res.data.data)
      brands.value = res.data.data || res.data;
    } catch (err: any) {
      console.error("error to fetch brand");
      error.value = err.response?.data?.message || err.message;
    } finally {
      isLoading.value = false;
    }
  }

  async function updateBrand(data: any, id: number) {
    isLoading.value = true;
    error.value = null;
    try {
      await AdminBrandService.update(data, id);

      await fetchBrand();
    } catch (err: any) {
      console.error("failed to update brand");
      error.value = err.response?.data?.message || err.message;
      throw err;
    } finally {
      isLoading.value = false;
    }
  }

  async function createBrand(data: any) {
    isLoading.value = true;
    error.value = null;
    try {
      await AdminBrandService.create(data);

      await fetchBrand();
    } catch (err: any) {
      console.error("failed to create brand");
      error.value = err.response?.data?.message || err.message;
      throw err;
    } finally {
      isLoading.value = false;
    }
  }

  async function deleteBrand(id: number) {
    isLoading.value = true;
    error.value = null;
    try {
      await AdminBrandService.delete(id);

      await fetchBrand();
    } catch (err: any) {
      console.error("failed to delete brand");
      error.value = err.response?.data?.message || err.message;
      throw err;
    } finally {
      isLoading.value = false;
    }
  }

  return {
    brands,
    isLoading,
    error,

    fetchBrand,
    updateBrand,
    createBrand,
    deleteBrand,
  };
});
