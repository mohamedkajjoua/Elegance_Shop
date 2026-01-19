import api from "./api";

class SettingService {
  async getSetting() {
    return api.get("/settings");
  }
  async updateSetting(data: any) {
    return api.post("/settings", data);
  }
}

export default new SettingService();
