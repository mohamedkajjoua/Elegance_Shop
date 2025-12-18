<script setup>
import { computed } from 'vue'
import { useCartStore } from '@/stores/cart'
import { useWishlistStore } from '@/stores/wishlist'

const props = defineProps({
  product: {
    type: Object,
    required: true
  }
})

const cartStore = useCartStore()
const wishlistStore = useWishlistStore()

const isInWishlist = computed(() => wishlistStore.isInWishlist(props.product.id))

const badgeClass = computed(() => {
  const classes = {
    new: 'bg-[#E5E0FC] text-primary',
    bestseller: 'bg-[#D8F3DC] text-[#2D6A4F]',
    promo: 'bg-[#FFE0E0] text-[#C62828]'
  }
  return classes[props.product.badgeType] || ''
})

function toggleWishlist(e) {
  e.preventDefault()
  e.stopPropagation()
  wishlistStore.toggleWishlist(props.product)
}

function quickAddToCart(e) {
  e.preventDefault()
  e.stopPropagation()
  
  const product = {
    ...props.product,
    size: 'M',
    color: 'Default',
    quantity: 1
  }
  
  cartStore.addToCart(product)
  
  // Visual feedback
  const btn = e.currentTarget
  const originalContent = btn.innerHTML
  btn.innerHTML = '<i class="fa-solid fa-check"></i>'
  btn.style.background = '#34C759'
  
  setTimeout(() => {
    btn.innerHTML = originalContent
    btn.style.background = ''
  }, 1000)
}
</script>

<template>
  <div class="product-card bg-card-bg rounded-[20px] p-3 border border-transparent hover:-translate-y-1 hover:shadow-md hover:border-[#F0EBFF] transition-all duration-300 group">
    <router-link 
      :to="`/product/${product.id}`"
      class="product-image block relative aspect-square overflow-hidden bg-[#f5f5f5] rounded-2xl"
    >
      <img 
        :src="product.image" 
        :alt="product.title"
        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
      >
      <span 
        v-if="product.badge"
        :class="['badge absolute top-3 left-3 px-3 py-1.5 rounded-md text-[10px] font-bold uppercase tracking-wide', badgeClass]"
      >
        {{ product.badge }}
      </span>
      <button 
        class="wishlist-btn absolute top-3 right-3 w-9 h-9 bg-white/90 border-none rounded-full flex items-center justify-center cursor-pointer transition-all opacity-0 group-hover:opacity-100 hover:bg-white hover:scale-110"
        :class="isInWishlist ? 'text-danger' : 'text-text-light hover:text-danger'"
        @click="toggleWishlist"
      >
        <i :class="isInWishlist ? 'fa-solid fa-heart' : 'fa-regular fa-heart'"></i>
      </button>
    </router-link>
    
    <div class="product-info pt-4 px-1 pb-1">
      <h3 class="product-title text-[15px] font-semibold text-text mb-3 whitespace-nowrap overflow-hidden text-ellipsis">
        {{ product.title }}
      </h3>
      <div class="product-footer flex justify-between items-end">
        <div class="product-price flex flex-col gap-1">
          <span class="price-label text-[11px] text-text-light uppercase">Price</span>
          <div class="price-row flex items-center">
            <span class="current-price text-lg font-bold text-text">${{ product.price }}</span>
            <span 
              v-if="product.originalPrice"
              class="original-price text-[13px] text-text-light line-through ml-2"
            >
              ${{ product.originalPrice }}
            </span>
          </div>
        </div>
        <button 
          class="add-cart-btn w-9 h-9 flex items-center justify-center bg-[#F0EBFF] text-primary rounded-lg hover:bg-primary hover:text-white transition-colors"
          @click="quickAddToCart"
        >
          <i class="fa-solid fa-cart-shopping"></i>
        </button>
      </div>
    </div>
  </div>
</template>
