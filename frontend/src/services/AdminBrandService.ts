import api from "./api";

class AdminBrandService {
  async getAll() {
    return api.get("/brands");
  }
}

export default new AdminBrandService();
