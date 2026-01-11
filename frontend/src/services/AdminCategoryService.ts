import api from "./api";

class AdminCategoryService {
  async getAll() {
    return api.get("/categories");
  }

  async create(data: any) {
    return api.post("/categories", data);
  }
  async update(data: any, id: number) {
    return api.put(`/categories/${id}`, data);
  }

  async getById(id: number) {
    return api.get(`/categories/${id}`);
  }
  async delete(id: number) {
    return api.delete(`/categories/${id}`);
  }
}

export default new AdminCategoryService();
