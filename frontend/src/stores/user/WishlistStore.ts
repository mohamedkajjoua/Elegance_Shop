import { wishlistService } from "@/services/WhishlistService";
import { defineStore } from "pinia";
import { ref, computed } from "vue";

export const useWishlistStore = defineStore("wishlist", () => {
  const items = ref([]);
  const loading = ref(false);

  const count = computed(() => items.value?.length || 0);

  const isInWishlist = (productId: any) => {
    return items.value?.some((item) => (item.product_id || item.id) === productId) || false;
  };

  async function fetchWishlist() {
    if (items.value.length > 0) return;

    if (loading.value) return;

    loading.value = true;
    try {
      const response = await wishlistService.getAll();
      items.value = response;
    } catch (error) {
      console.error("Erreur fetch wishlist:", error);
      items.value = [];
    } finally {
      loading.value = false;
    }
  }

  async function toggleProduct(product: any) {
    if (!items.value) items.value = [];

    const productId = product.id || product.product_id;

    const exists = items.value.some((item) => (item.product_id || item.id) === productId);

    if (exists) {
      items.value = items.value.filter((item) => (item.product_id || item.id) !== productId);
    } else {
      items.value.push({ product_id: productId, ...product });
    }

    try {
      await wishlistService.toggle(productId);
    } catch (error) {
      console.error("error toggle:", error);

      await fetchWishlist();
    }
  }

  async function deleteProduct(productId: any) {
    if (!items.value) return;

    const previousItems = [...items.value];
    items.value = items.value.filter((item) => (item.product_id || item.id) !== productId);

    try {
      await wishlistService.remove(productId);
    } catch (error) {
      console.error("error delete:", error);
      items.value = previousItems;
    }
  }

  return {
    items,
    loading,
    count,
    isInWishlist,
    fetchWishlist,
    toggleProduct,
    deleteProduct,
  };
});
