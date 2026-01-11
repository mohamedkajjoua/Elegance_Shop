import api from "./api";

class AdminBrandService {
  async getAll() {
    return api.get("/brands");
  }
  async create(data: any) {
    return api.post("/brands", data);
  }
  async update(data: any, id: number) {
    return api.put(`/brands/${id}`, data);
  }

  async getById(id: number) {
    return api.get(`/brands/${id}`);
  }
  async delete(id: number) {
    return api.delete(`/brands/${id}`);
  }
}

export default new AdminBrandService();
