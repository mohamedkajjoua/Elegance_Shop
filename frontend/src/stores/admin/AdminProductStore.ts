import AdminProductService from "@/services/AdminProductService";
import type { IProduct, IProductParams, IProductPayload } from "@/types/ProductType";
import { defineStore } from "pinia";
import { ref } from "vue";

export const useAdminProductStore = defineStore("adminProduct", () => {
  const products = ref<IProduct[]>([]);
  const currentProduct = ref<IProduct | null>(null);
  const isLoading = ref(false);
  const error = ref<string | null>(null);
  const success = ref<string | null>(null);

  const meta = ref({
    current_page: 1,
    last_page: 1,
    total: 0,
    per_page: 10, //default
  });

  async function createProduct(payload: IProductPayload) {
    isLoading.value = true;
    error.value = null;

    try {
      await AdminProductService.create(payload);
    } catch (err: any) {
      error.value = err.response?.data?.message || "Failed to create product";
      throw err;
    } finally {
      isLoading.value = false;
    }
  }
  async function updateProduct(id: number, payload: IProductPayload) {
    // Changed payload type to FormData
    isLoading.value = true;
    error.value = null;
    try {
      await AdminProductService.update(id, payload);
      await fetchAllProduct();
    } catch (err: any) {
      error.value = err.response?.data?.message || "Failed to update product";
      throw err;
    } finally {
      isLoading.value = false;
    }
  }

  async function fetchAllProduct(params: IProductParams = {}) {
    isLoading.value = true;
    error.value = null;

    try {
      const res = await AdminProductService.getAll(params);

      products.value = res.data.data;

      const paginationInfo = res.data.pagination;
      if (paginationInfo) {
        meta.value = {
          current_page: paginationInfo.current_page,
          last_page: paginationInfo.last_page,
          total: paginationInfo.total,
          per_page: paginationInfo.per_page,
        };
      }
    } catch (err: any) {
      error.value = err.response?.data?.message || "Failed to fetch product";
    } finally {
      isLoading.value = false;
    }
  }

  async function deleteProduct(id: number) {
    isLoading.value = true;
    error.value = null;
    success.value = null;
    try {
      const res = await AdminProductService.delete(id);
      products.value = products.value.filter((p) => p.id !== id);
      meta.value.total--;
      success.value = res.request?.data?.message || "Product delete successfully";
    } catch (err: any) {
      console.error("Failed to delete product");
    } finally {
      isLoading.value = false;
    }
  }
  async function fetchProductById(id: number) {
    isLoading.value = true;
    error.value = null;
    success.value = null;
    try {
      const res = await AdminProductService.getById(id);
      currentProduct.value = res.data.data;

      return res.data;
    } catch (err: any) {
      error.value = err.response?.data?.data || "Failed to fetch product";
    } finally {
      isLoading.value = false;
    }
  }
  return {
    products,
    isLoading,
    error,
    success,
    currentProduct,
    meta,
    createProduct,
    fetchAllProduct,
    deleteProduct,
    fetchProductById,
    updateProduct,
  };
});
