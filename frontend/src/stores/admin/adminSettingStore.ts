import { ref } from "vue";
import { defineStore } from "pinia";
import SettingsService from "@/services/SettingsService";

export const useSettingStore = defineStore("setting", () => {
  // --- State (Refs) ---
  const settings = ref({
    site_name: "",
    site_description: "",
    call_us: "",
    email_us: "",
    visit_us: "",
    instagram: "",
    facebook: "",
    x: "",
  });

  const isLoading = ref(false);
  const successMessage = ref("");
  const errorMessage = ref("");

  // --- Actions (Functions) ---
  const fetchSettings = async () => {
    isLoading.value = true;
    try {
      const response = await SettingsService.getSetting();

      settings.value = { ...settings.value, ...response.data };
    } catch (error) {
      errorMessage.value = "Failed to load settings";
      console.error(error);
    } finally {
      isLoading.value = false;
    }
  };

  const saveSettings = async () => {
    isLoading.value = true;
    successMessage.value = "";
    try {
      await SettingsService.updateSetting(settings.value);
      successMessage.value = "Settings updated successfully!";
      setTimeout(() => (successMessage.value = ""), 3000);
    } catch (error) {
      errorMessage.value = "Error saving settings";
    } finally {
      isLoading.value = false;
    }
  };

  return {
    settings,
    isLoading,
    successMessage,
    errorMessage,
    fetchSettings,
    saveSettings,
  };
});
