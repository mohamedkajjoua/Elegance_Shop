import api from "@/services/api";
import ProductShopServices from "@/services/ProductShopServices";
import type { IProduct, IProductParams } from "@/types/ProductType";
import { defineStore } from "pinia";
import { ref } from "vue";

export const useProductShopStore = defineStore("ProductShop", () => {
  const products = ref<IProduct[]>([]);
  const product = ref<IProduct | null>(null);
  const relatedProducts = ref([]);
  const isLoading = ref(false);
  const error = ref<string | null>(null);

  //filter

  const categories = ref([]);
  const brands = ref([]);
  const colors = ref(["Red", "Blue", "Black", "White", "Green"]);
  const sizes = ref(["S", "M", "L", "XL", "XXL"]);

  async function fetchFilterAttributes() {
    try {
      const [catRes, brandRes] = await Promise.all([api.get("/categories"), api.get("/brands")]);
      categories.value = catRes.data.data || catRes.data;
      brands.value = brandRes.data.data || brandRes.data;
    } catch (err: any) {
      console.error("Failed to fetch filter attributes", err);
    }
  }

  //end filter

  // pagination meta
  const meta = ref({
    current_page: 1,
    last_page: 1,
    total: 0,
  });

  // في ProductShop.ts store
  async function fetchProduct(params: any = {}) {
    isLoading.value = true;
    error.value = null;

    try {
      let res;

      const filterKeys = ["size", "color", "category_id", "brand_id", "min_price", "max_price"];

      const hasActiveFilter = filterKeys.some((key) => {
        const value = params[key];
        return value !== undefined && value !== null && value !== "";
      });

      if (hasActiveFilter) {
        res = await ProductShopServices.filters(params);
      } else if (params.q && params.q.length >= 2) {
        res = await ProductShopServices.search(params);
      } else {
        res = await ProductShopServices.getAll(params);
      }

      // ----------------------------------------------------

      products.value = res.data.data;

      const paginationInfo = res.data.pagination;
      if (paginationInfo) {
        meta.value = {
          current_page: paginationInfo.current_page,
          last_page: paginationInfo.last_page,
          total: paginationInfo.total,
        };
      }
    } catch (err: any) {
      console.error(err);
      error.value = "Failed to load products";
    } finally {
      isLoading.value = false;
    }
  }

  async function fetchProductDetails(id: string) {
    isLoading.value = true;
    product.value = null;
    relatedProducts.value = [];

    try {
      const res = await ProductShopServices.getById(id);
      product.value = res.data.data;
      //
      const relatedRes = await ProductShopServices.getRelated(id);

      relatedProducts.value = relatedRes.data.data || relatedRes.data || [];
    } catch (err: any) {
      err.value = "Product not found";
    } finally {
      isLoading.value = false;
    }
  }

  return {
    products,
    product,
    relatedProducts,
    //filter
    categories,
    brands,
    colors,
    sizes,

    isLoading,
    error,
    meta,

    fetchProduct,
    fetchProductDetails,
    fetchFilterAttributes,
  };
});
