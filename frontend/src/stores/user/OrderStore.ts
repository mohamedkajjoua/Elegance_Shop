import OrderService from "@/services/OrderService";
import { defineStore } from "pinia";
import { ref } from "vue";

export const useOrderStore = defineStore("order", () => {
  const orders = ref([]);
  const currentOrder = ref(null);
  const isLoading = ref(false);
  const error = ref(null);

  const createOrder = async (data: any) => {
    isLoading.value = true;
    error.value = null;

    try {
      const res = await OrderService.createOrder(data);
      return res.data.data || res.data;
    } catch (err: any) {
      error.value = err.response?.data?.message || "failed to place order";
      throw err;
    } finally {
      isLoading.value = false;
    }
  };
  const fetchUserOrders = async () => {
    isLoading.value = true;
    error.value = null;
    try {
      const res = await OrderService.getUserOrder();

      if (res.data && Array.isArray(res.data.orders)) {
        orders.value = res.data.orders;
      } else {
        orders.value = [];
      }
    } catch (err: any) {
      error.value = err.response?.data?.message || "Failed to fetch orders";
      console.error(err);
    } finally {
      isLoading.value = false;
    }
  };
  const fetchOrderDetails = async (id: number) => {
    isLoading.value = true;
    try {
      const res = await OrderService.getOrderDetails(id);
      currentOrder.value = res.data.data || res.data;
    } catch (err: any) {
      error.value = err.response?.data?.message || "Failed to fetch order details";
    } finally {
      isLoading.value = false;
    }
  };

  return {
    orders,
    isLoading,
    error,
    createOrder,
    fetchUserOrders,
    fetchOrderDetails,
  };
});
