import api from "./api";

class DashboardService {
  async getDashboardStats() {
    return api.get("/admin/dashboardStats");
  }
}

export default new DashboardService();
