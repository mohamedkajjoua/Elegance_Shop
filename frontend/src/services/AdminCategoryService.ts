import api from "./api";

class AdminCategoryService {
  async getAll() {
    return api.get("/categories");
  }
}

export default new AdminCategoryService();
