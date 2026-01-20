import api from "./api";

const ReviewService = {
  async getReviews(productId: number) {
    return await api.get(`/products/${productId}/reviews`);
  },

  async upsertReviews(data: any) {
    return await api.post("/reviews", data);
  },
};

export default ReviewService;
