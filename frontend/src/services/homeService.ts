import api from "./api";

class homeService {
  async getHomeCategory() {
    return api.get("/home/getCategoryToHome");
  }

  async getHomeFeatured() {
    return api.get("/products/featured");
  }

  async getHomeBestSellers() {
    return api.get("/products/best-sellers");
  }
}

export default new homeService();
