<script setup>
import { useProductShopStore } from "@/stores/user/ProductShop";
import ProductCard from "./ProductCard.vue";
import { storeToRefs } from "pinia";

const productStore = useProductShopStore();
const { products, isLoading } = storeToRefs(productStore);
</script>

<template>
  <!-- Loading -->
  <div v-if="isLoading" class="text-center py-20">
    <span class="loading loading-spinner loading-lg text-primary"></span>
  </div>

  <div
    v-else-if="products.length === 0 && !isLoading"
    class="flex flex-col items-center justify-center py-16 px-4 text-center"
  >
    <div class="bg-gray-50 rounded-full p-6 mb-4">
      <i class="fa-solid fa-magnifying-glass text-4xl text-gray-400"></i>
    </div>

    <h3 class="text-lg font-bold text-text mb-2">No products found</h3>
    <p class="text-text-light text-sm max-w-xs mx-auto mb-6">
      We couldn't find what you're looking for. Try changing your search keywords or filters.
    </p>
  </div>

  <!-- Products -->
  <div
    v-else
    class="product-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-12"
  >
    <ProductCard v-for="product in products" :key="product.id" :product="product" />
  </div>
</template>
