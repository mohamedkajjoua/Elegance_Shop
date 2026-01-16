<script setup>
import { computed } from "vue";
import { useCartStore } from "@/stores/cart";
import { useWishlistStore } from "@/stores/user/WishlistStore";

const props = defineProps({
  product: {
    type: Object,
    required: true,
  },
});

const cartStore = useCartStore();
const wishlistStore = useWishlistStore();

const BASE_URL = "http://127.0.0.1:8000";

const mainImage = computed(() => {
  if (props.product.images?.length) {
    const primary = props.product.images.find((img) => img.is_primary);
    const imagePath = primary ? primary.image_url : props.product.images[0].image_url;

    return `${BASE_URL}/storage/${imagePath}`;
  }

  return "/placeholder-image.jpg";
});

const badgeData = computed(() => {
  if (props.product.discount > 0) {
    return { text: `-${props.product.discount}%`, class: "bg-[#FFE0E0] text-[#C62828]" }; // Sale
  }
  if (props.product.is_featured) {
    return { text: "HOT", class: "bg-[#E5E0FC] text-primary" }; // Featured
  }

  return null;
});

const isInWishlist = computed(() => {
  return wishlistStore.isInWishlist(props.product.id || props.product.product_id);
});

function toggleWishlist() {
  wishlistStore.toggleProduct(props.product);
}

function addToCart(e) {
  e.preventDefault();
  e.stopPropagation();

  //  sécurité
  if (!props.product.variants || props.product.variants.length === 0) {
    console.error("Produit sans variants", props.product);
    alert("Ce produit n’est pas disponible");
    return;
  }

  const variantId = props.product.variants[0].id;

  cartStore.addToCart(variantId, 1);

  // feedback UI
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
  <div
    class="product-card bg-white rounded-[20px] p-3 border border-transparent hover:-translate-y-1 hover:shadow-lg hover:border-[#F0EBFF] transition-all duration-300 group"
  >
    <router-link
      :to="`/product/${product.id}`"
      class="product-image block relative aspect-square overflow-hidden bg-[#f5f5f5] rounded-2xl"
    >
      <img
        :src="mainImage"
        :alt="product.name"
        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
      />

      <span
        v-if="badgeData"
        :class="[
          'absolute top-3 left-3 px-3 py-1.5 rounded-md text-[10px] font-bold uppercase tracking-wide',
          badgeData.class,
        ]"
      >
        {{ badgeData.text }}
      </span>

      <button
        class="wishlist-btn absolute top-3 right-3 w-9 h-9 bg-white/90 border-none rounded-full flex items-center justify-center cursor-pointer transition-all opacity-0 group-hover:opacity-100 hover:bg-white hover:scale-110 shadow-sm"
        :class="isInWishlist ? 'text-red-500' : 'text-gray-400 hover:text-red-500'"
        @click.stop.prevent="toggleWishlist"
      >
        <i :class="isInWishlist ? 'fa-solid fa-heart' : 'fa-regular fa-heart'"></i>
      </button>
    </router-link>

    <div class="product-info pt-4 px-1 pb-1">
      <h3
        class="product-title text-[15px] font-semibold text-gray-800 mb-3 whitespace-nowrap overflow-hidden text-ellipsis"
      >
        {{ product.name }}
      </h3>

      <div class="product-footer flex justify-between items-end">
        <div class="product-price flex flex-col gap-1">
          <span class="price-label text-[11px] text-gray-400 uppercase"
            >Price :{{ product.brand_id }}</span
          >
          <div class="price-row flex items-center gap-2">
            <span class="current-price text-lg font-bold text-gray-900"
              >${{ product.final_price }}</span
            >

            <span
              v-if="product.discount > 0"
              class="original-price text-[13px] text-gray-400 line-through"
            >
              ${{ product.price }}
            </span>
          </div>
        </div>

        <button
          class="add-cart-btn w-9 h-9 flex items-center justify-center bg-[#F0EBFF] text-primary rounded-lg hover:bg-primary hover:text-white transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
          @click="addToCart"
        >
          <i class="fa-solid fa-cart-shopping"></i>
        </button>
      </div>
    </div>
  </div>
</template>
