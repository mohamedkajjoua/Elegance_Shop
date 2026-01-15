import api from "./api";

const API_URL = "/addresses";

const addressService = {
  async getAddresses() {
    return await api.get(API_URL);
  },

  async getAddressById(id: number) {
    return await api.get(`${API_URL}/${id}`);
  },

  async createAddress(data: any) {
    return await api.post(API_URL, data);
  },
  async updateAddress(id: number, data: any) {
    return await api.put(`${API_URL}/${id}`, data);
  },

  async deleteAddress(id: number) {
    return await api.delete(`${API_URL}/${id}`);
  },
};
export default addressService;
