import DashboardService from "@/services/DashboardService";
import { defineStore } from "pinia";
import { ref } from "vue";

export const useDashboardStatsStore = defineStore("dashboardStats", () => {
  const stats = ref({});
  const topProducts = ref([]);
  const recentOrders = ref([]);
  const salesChart = ref([]);
  const isLoading = ref(false);
  const error = ref(null);

  async function fetchDashboardData() {
    isLoading.value = true;
    error.value = null;

    try {
      const res = await DashboardService.getDashboardStats();

      // axios يعيد البيانات داخل res.data
      // الـ API الخاص بنا يعيد البيانات داخل success و data
      if (res.data && res.data.success) {
        const dashboardData = res.data.data; // هنا تكمن كل البيانات

        stats.value = dashboardData.stats; // تأكد أنها stats وليست state
        topProducts.value = dashboardData.top_products;
        recentOrders.value = dashboardData.recent_orders;
        salesChart.value = dashboardData.sales_chart;
      }
    } catch (err: any) {
      error.value = err;
      console.error("Dashboard Fetch Error:", err);
    } finally {
      isLoading.value = false;
    }
  }

  return {
    stats,
    topProducts,
    recentOrders,
    salesChart,
    isLoading,
    error,
    fetchDashboardData,
  };
});
