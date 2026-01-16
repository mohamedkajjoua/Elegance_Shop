import api from "./api";

const API_URL = "/wishlist";

export const wishlistService = {
  async getAll() {
    const response = await api.get(API_URL);
    return response.data;
  },

  async toggle(productId: any) {
    const response = await api.post(`${API_URL}/toggle`, { product_id: productId });
    return response.data;
  },

  async remove(productId: any) {
    const response = await api.delete(`${API_URL}/${productId}`);
    return response.data;
  },
};
