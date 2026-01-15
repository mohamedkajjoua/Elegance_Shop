import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { wishlistService } from '@/services/wishlistService';

export const useWishlistStore = defineStore('wishlist', () => {

  const items = ref([]);
  const loading = ref(false);

  const count = computed(() => items.value.length);
 
  const isInWishlist = (productId) => {
    return items.value.some(item => item.product_id === productId);
  };

  
  async function fetchWishlist() {
    loading.value = true;
    try {

      const response = await wishlistService.getAll();
      items.value = response.data; 
    } catch (error) {
      console.error("Erreur fetch wishlist:", error);
    } finally {
      loading.value = false;
    }
  }

  
  async function toggleProduct(productId) {
    try {
      const res = await wishlistService.toggle(productId);
      await fetchWishlist();
      return res; 
    } catch (error) {
      console.error("Erreur toggle:", error);
    }
  }

  
  async function deleteProduct(productId) {
    try {
      await wishlistService.remove(productId);
      
      items.value = items.value.filter(item => item.product_id !== productId);
    } catch (error) {
      console.error("Erreur delete:", error);
    }
  }

  return { 
    items, 
    loading, 
    count, 
    isInWishlist, 
    fetchWishlist, 
    toggleProduct, 
    deleteProduct 
  };
});