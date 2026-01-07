import homeService from "@/services/homeService";
import { defineStore } from "pinia";
import { ref } from "vue";

export const useHomeStore = defineStore("homeStore", () => {
  const categories = ref([]);
  const featuredProducts = ref([]);
  const BestSellersProduct = ref([]);
  const isLoading = ref(false);
  const error = ref<string | null>(null);

  async function fetchHomeCategories() {
    isLoading.value = true;
    error.value = null;

    try {
      const res = await homeService.getHomeCategory();
      if (res.data.success) {
        categories.value = res.data.data;
      }
    } catch (err: any) {
      console.error("error loading home category");
    } finally {
      isLoading.value = false;
    }
  }

  async function fetchHomeFeatured() {
    isLoading.value = true;
    error.value = null;

    try {
      const res = await homeService.getHomeFeatured();

      if (res.data.success) {
        featuredProducts.value = res.data.data;
      }
    } catch (err: any) {
      console.error("featured not fetching");
    } finally {
      isLoading.value = false;
    }
  }

  async function getHomeBestSellers() {
    isLoading.value = true;
    error.value = null;

    try {
      const res = await homeService.getHomeBestSellers();
      if (res.data.success) {
        BestSellersProduct.value = res.data.data;
      }
    } catch (err: any) {
      console.error("error fetching BestSellers");
    } finally {
      isLoading.value = false;
    }
  }

  return {
    featuredProducts,
    BestSellersProduct,
    categories,
    isLoading,
    error,

    fetchHomeCategories,
    fetchHomeFeatured,
    getHomeBestSellers,
  };
});
