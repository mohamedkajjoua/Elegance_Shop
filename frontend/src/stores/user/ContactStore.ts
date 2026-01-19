import { defineStore } from "pinia";
import { ref } from "vue";

import api from "@/services/api";

export const useContactStore = defineStore("contact", () => {
  // --- State ---
  const loading = ref(false);
  const successMessage = ref(null);
  const errorMessage = ref(null);

  // --- Actions ---
  const sendContactMessage = async (formData: any) => {
    loading.value = true;
    successMessage.value = null;
    errorMessage.value = null;

    try {
      const response = await api.post("/contact/send", formData);

      successMessage.value = response.data.message;
      return { success: true };
    } catch (error: any) {
      errorMessage.value = error.response?.data?.message || "Failed to send Email    ";
      return { success: false };
    } finally {
      loading.value = false;
    }
  };

  const resetStatus = () => {
    successMessage.value = null;
    errorMessage.value = null;
  };

  return {
    loading,
    successMessage,
    errorMessage,
    sendContactMessage,
    resetStatus,
  };
});
