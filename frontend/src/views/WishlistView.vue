<script setup>
import { useWishlistStore } from '@/stores/wishlist'
import { useCartStore } from '@/stores/cart'
import Breadcrumb from '@/components/layout/Breadcrumb.vue'

const wishlistStore = useWishlistStore()
const cartStore = useCartStore()

const breadcrumbItems = [{ label: 'Wishlist' }]

function addToCart(product) {
  cartStore.addToCart({
    ...product,
    size: 'M',
    color: 'Default',
    quantity: 1
  })
  alert('Added to cart!')
}

function removeFromWishlist(productId) {
  wishlistStore.removeFromWishlist(productId)
}
</script>

<template>
  <div class="pb-12">
    <Breadcrumb :items="breadcrumbItems" />
    
    <div class="bg-white rounded-xl md:rounded-2xl p-4 sm:p-6 md:p-8">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6 md:mb-8">
        <h2 class="text-xl md:text-2xl font-bold">My Wishlist</h2>
        <span class="text-sm md:text-base text-text-light">{{ wishlistStore.totalItems }} items</span>
      </div>

      <div v-if="wishlistStore.items.length > 0" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6">
        <div 
          v-for="product in wishlistStore.items"
          :key="product.id"
          class="bg-background rounded-xl md:rounded-2xl p-3 md:p-4 group"
        >
          <div class="relative aspect-square overflow-hidden rounded-xl mb-4">
            <router-link :to="`/product/${product.id}`">
              <img 
                :src="product.image" 
                :alt="product.title"
                class="w-full h-full object-cover transition-transform group-hover:scale-105"
              >
            </router-link>
            <button 
              class="absolute top-2 right-2 md:top-3 md:right-3 w-7 h-7 md:w-9 md:h-9 bg-white rounded-full flex items-center justify-center text-danger hover:bg-danger hover:text-white transition-colors text-sm md:text-base"
              @click="removeFromWishlist(product.id)"
            >
              <i class="fa-solid fa-heart"></i>
            </button>
          </div>
          
          <h3 class="font-semibold text-xs sm:text-sm mb-2 truncate">{{ product.title }}</h3>
          <div class="flex justify-between items-center">
            <span class="font-bold text-sm md:text-lg">${{ product.price }}</span>
            <button 
              class="w-7 h-7 md:w-9 md:h-9 bg-primary text-white rounded-lg flex items-center justify-center hover:bg-primary-dark transition-colors"
              @click="addToCart(product)"
            >
              <i class="fa-solid fa-cart-shopping text-xs md:text-sm"></i>
            </button>
          </div>
        </div>
      </div>

      <div v-else class="text-center py-10 md:py-16 text-text-light">
        <i class="fa-regular fa-heart text-4xl md:text-6xl mb-4 opacity-30"></i>
        <p class="text-base md:text-lg mb-4">Your wishlist is empty</p>
        <router-link 
          to="/"
          class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-semibold hover:bg-primary-dark transition-colors"
        >
          <i class="fa-solid fa-arrow-left"></i> Browse Products
        </router-link>
      </div>
    </div>
  </div>
</template>
