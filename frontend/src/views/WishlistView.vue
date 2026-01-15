<script setup>
import { onMounted, computed } from "vue";
import { useWishlistStore } from "@/stores/user/WishlistStore";
import { useCartStore } from "@/stores/cart";
import Breadcrumb from "@/components/layout/Breadcrumb.vue";

const wishlistStore = useWishlistStore();
const cartStore = useCartStore();
const BASE_URL = "http://127.0.0.1:8000";

const breadcrumbItems = [{ label: "Wishlist", to: "/wishlist" }];

onMounted(() => {
  wishlistStore.fetchWishlist();
});

const getImageUrl = (item) => {
  const product = item.product || item;
  if (!product) return "/images/placeholder.jpg";

  let path = product.image || product.images?.[0]?.image_url || product.image_url;

  if (!path) return "/images/placeholder.jpg";
  if (path.startsWith("http")) return path;

  const cleanPath = path.replace(/^\//, "");
  return cleanPath.startsWith("storage/")
    ? `${BASE_URL}/${cleanPath}`
    : `${BASE_URL}/storage/${cleanPath}`;
};

function addToCart(e, item) {
  e.preventDefault();
  e.stopPropagation();

  const product = item.product || item;

  if (!product.variants || product.variants.length === 0) {
    console.error("Produit sans variants", product);
    alert("Ce produit n’est pas disponible");
    return;
  }

  const variantId = product.variants[0].id;
  cartStore.addToCart(variantId, 1);

  const btn = e.currentTarget;
  const oldHtml = btn.innerHTML;

  btn.disabled = true;
  btn.innerHTML = '<i class="fa-solid fa-check"></i>';

  btn.classList.remove("bg-[#F0EBFF]", "text-primary");
  btn.classList.add("bg-green-500", "text-white");

  setTimeout(() => {
    btn.innerHTML = oldHtml;
    btn.disabled = false;
    btn.classList.remove("bg-green-500", "text-white");
    btn.classList.add("bg-[#F0EBFF]", "text-primary");
  }, 1000);
}
</script>

<template>
  <div class="pb-12">
    <Breadcrumb :items="breadcrumbItems" />

    <div class="bg-white rounded-xl md:rounded-2xl p-4 sm:p-6 md:p-8 min-h-[400px]">
      <div class="flex justify-between items-center mb-8">
        <h2 class="text-xl md:text-2xl font-bold">My Wishlist</h2>
        <span class="text-sm text-gray-500">{{ wishlistStore.count }} items</span>
      </div>

      <div
        v-if="wishlistStore.loading"
        class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6"
      >
        <div v-for="i in 4" :key="i" class="animate-pulse bg-gray-50 rounded-[20px] p-3 h-80"></div>
      </div>

      <div
        v-else-if="wishlistStore.items.length > 0"
        class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6"
      >
        <div
          v-for="item in wishlistStore.items"
          :key="item.id"
          class="product-card bg-white rounded-[20px] p-3 border border-transparent hover:-translate-y-1 hover:shadow-lg hover:border-[#F0EBFF] transition-all duration-300 group"
        >
          <router-link
            :to="`/product/${item.product_id || item.id}`"
            class="product-image block relative aspect-square overflow-hidden bg-[#f5f5f5] rounded-2xl"
          >
            <img
              :src="getImageUrl(item)"
              :alt="item.product?.name || item.name"
              class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
            />

            <span
              v-if="item.product?.discount > 0"
              class="absolute top-3 left-3 px-3 py-1.5 rounded-md text-[10px] font-bold uppercase tracking-wide bg-red-500 text-white"
            >
              -{{ item.product.discount }}%
            </span>

            <button
              class="wishlist-btn absolute top-3 right-3 w-9 h-9 bg-white/90 border-none rounded-full flex items-center justify-center cursor-pointer transition-all opacity-0 group-hover:opacity-100 hover:bg-white hover:scale-110 shadow-sm text-red-500"
              @click.stop.prevent="wishlistStore.toggleProduct(item.product || item)"
            >
              <i class="fa-solid fa-heart"></i>
            </button>
          </router-link>

          <div class="product-info pt-4 px-1 pb-1">
            <h3
              class="product-title text-[15px] font-semibold text-gray-800 mb-3 whitespace-nowrap overflow-hidden text-ellipsis"
            >
              {{ item.product?.name || item.name }}
            </h3>

            <div class="product-footer flex justify-between items-end">
              <div class="product-price flex flex-col gap-1">
                <span class="price-label text-[11px] text-gray-400 uppercase"> Price </span>
                <div class="price-row flex items-center gap-2">
                  <span class="current-price text-lg font-bold text-gray-900">
                    ${{ item.product?.final_price || item.final_price }}
                  </span>
                  <span
                    v-if="(item.product?.discount || item.discount) > 0"
                    class="original-price text-[13px] text-gray-400 line-through"
                  >
                    ${{ item.product?.price || item.price }}
                  </span>
                </div>
              </div>

              <button
                class="add-cart-btn w-9 h-9 flex items-center justify-center bg-[#F0EBFF] text-primary rounded-lg hover:bg-primary hover:text-white transition-colors"
                @click="addToCart($event, item)"
              >
                <i class="fa-solid fa-cart-shopping"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="text-center py-20">
        <i class="fa-regular fa-heart text-6xl text-gray-200 mb-4"></i>
        <h3 class="text-xl font-bold text-gray-800">Your wishlist is empty</h3>
        <router-link to="/shop" class="mt-4 inline-block text-primary font-semibold"
          >Go to Shop</router-link
        >
      </div>
    </div>
  </div>
</template>
