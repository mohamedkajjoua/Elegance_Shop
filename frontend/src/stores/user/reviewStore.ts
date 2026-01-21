import ReviewService from "@/services/ReviewService";
import { defineStore } from "pinia";
import { ref } from "vue";

export const useReviewStore = defineStore("review", () => {
  const reviews = ref([]);
  const isLoading = ref(false);
  const error = ref<any>(null);
  const success = ref(null);
  const reviewHome = ref([]);

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
  const deleteReview = async (reviewId: any) => {
    if (!confirm("Are you sure you want to delete this review?")) return;

    isLoading.value = true;
    error.value = null;

    try {
      await ReviewService.deleteReview(reviewId);

      reviews.value = reviews.value.filter((r) => r.id !== reviewId);

      return { success: true };
    } catch (err: any) {
      error.value = err.response?.data?.message || "Failed to delete review";
      return { success: false };
    } finally {
      isLoading.value = false;
    }
  };

  const AllReviews = async (data: any = {}) => {
    isLoading.value = true;
    error.value = null;

    try {
      const res = await ReviewService.getAll(data);

      reviewHome.value = res.data.data || res.data;
    } catch (err: any) {
      console.error("Error fetching home reviews:", err);
      error.value = err.response?.data?.message || "Failed to load reviews";
    } finally {
      isLoading.value = false;
    }
  };

  return {
    error,
    success,
    reviews,
    isLoading,
    reviewHome,
    fetchReviews,
    saveReviews,
    deleteReview,
    AllReviews,
  };
});
