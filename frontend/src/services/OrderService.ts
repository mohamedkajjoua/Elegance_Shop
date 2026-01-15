import api from "./api";

const API_URL = "/orders";
const OrderService = {
  async createOrder(data: any) {
    return await api.post(API_URL, data);
  },

  async getUserOrder() {
    return await api.get(API_URL);
  },

  async getOrderDetails(id: number) {
    return await api.get(`${API_URL}/${id}`);
  },

  async completeOrder(id: number) {
    return await api.post(`${API_URL}/${id}/complete`);
  },
};

export default OrderService;
