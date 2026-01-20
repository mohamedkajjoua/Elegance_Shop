import ReviewService from "@/services/ReviewService";
import { defineStore } from "pinia";
import { ref } from "vue";

export const useReviewStore = defineStore("review", () => {
  const reviews = ref([]);
  const isLoading = ref(false);
  const error = ref<any>(null);
  const success = ref(null);

  const fetchReviews = async (productId: number) => {
    isLoading.value = true;

    try {
      const res = await ReviewService.getReviews(productId);
      reviews.value = res.data.data || res.data;
    } catch (err: any) {
      error.value = " failed to get reviews";
    } finally {
      isLoading.value = false;
    }
  };

  const saveReviews = async (data: any) => {
    isLoading.value = true;
    error.value = null;

    try {
      const res = await ReviewService.upsertReviews(data);
      await fetchReviews(data.product_id);
      return { success: true, message: res.data.message };
    } catch (err: any) {
      error.value = err.response?.data?.message || "error to create reviews";
    } finally {
      isLoading.value = false;
    }
  };

  return {
    error,
    success,
    reviews,
    isLoading,
    fetchReviews,
    saveReviews,
  };
});
