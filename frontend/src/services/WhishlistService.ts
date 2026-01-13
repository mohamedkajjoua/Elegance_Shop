import api from "./api";


// تأكد من الـ URL ديال الـ API ديالك
const API_URL = '/api/wishlist'; 

export const wishlistService = {
  // جلب القائمة كاملة
  async getAll() {
    const response = await api.get(API_URL);
    return response.data; // كترجع الـ data لي فيها المنتج والصور والـ variants
  },

  // إضافة أو حذف (Toggle)
  async toggle(productId: any) {
    const response = await api.post(`${API_URL}/toggle`, { product_id: productId });
    return response.data;
  },

  // حذف نهائي
  async remove(productId: any) {
    const response = await api.delete(`${API_URL}/${productId}`);
    return response.data;
  }
};