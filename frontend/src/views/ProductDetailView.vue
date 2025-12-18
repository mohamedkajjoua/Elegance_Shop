<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cart'
import { useWishlistStore } from '@/stores/wishlist'
import { getProductById } from '@/data/products'
import Breadcrumb from '@/components/layout/Breadcrumb.vue'

const route = useRoute()
const router = useRouter()
const cartStore = useCartStore()
const wishlistStore = useWishlistStore()

const product = computed(() => getProductById(parseInt(route.params.id)) || {
  id: 999,
  title: 'Brooklyn-NYC Urban Sweatshirt',
  price: 125,
  originalPrice: 200,
  image: 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=500',
  description: 'Premium quality urban sweatshirt with modern design. Made from 100% organic cotton for maximum comfort.'
})

const thumbnails = [
  'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=500',
  'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?w=500',
  'https://images.unsplash.com/photo-1620799139507-2a76f79a2f4d?w=500',
  'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=500'
]

const mainImage = ref(product.value.image)
const selectedColor = ref('black')
const selectedSize = ref('L')
const quantity = ref(1)
const activeTab = ref('description')

const colors = [
  { name: 'black', hex: '#1A1A2E' },
  { name: 'navy', hex: '#1E3A5F' },
  { name: 'gray', hex: '#6B7280' },
  { name: 'brown', hex: '#92400E' }
]

const sizes = ['S', 'M', 'L', 'XL', 'XXL']

const isInWishlist = computed(() => wishlistStore.isInWishlist(product.value.id))

const breadcrumbItems = computed(() => [
  { label: 'Category', to: '/' },
  { label: 'Sweatshirt', to: '/' },
  { label: product.value.title }
])

function changeImage(src) {
  mainImage.value = src
}

function selectColor(color) {
  selectedColor.value = color
}

function selectSize(size) {
  selectedSize.value = size
}

function updateQty(change) {
  quantity.value = Math.max(1, Math.min(10, quantity.value + change))
}

function addToCart() {
  const item = {
    id: product.value.id,
    title: product.value.title,
    price: product.value.price,
    image: mainImage.value,
    size: selectedSize.value,
    color: selectedColor.value,
    quantity: quantity.value
  }
  
  cartStore.addToCart(item)
  alert('Added to cart!')
}

function toggleWishlist() {
  wishlistStore.toggleWishlist(product.value)
}
</script>

<template>
  <div class="pb-12">
    <Breadcrumb :items="breadcrumbItems" />
    
    <div class="bg-white rounded-2xl p-8">
      <div class="grid grid-cols-2 gap-12">
        <!-- Image Gallery -->
        <div class="product-gallery">
          <div class="main-image-wrapper relative aspect-square bg-gray-100 rounded-2xl overflow-hidden mb-4">
            <img 
              :src="mainImage" 
              :alt="product.title"
              class="w-full h-full object-cover"
            >
          </div>
          <div class="thumbnails flex gap-3">
            <button 
              v-for="(thumb, index) in thumbnails"
              :key="index"
              :class="[
                'thumb-btn w-20 h-20 rounded-xl overflow-hidden border-2 transition-all',
                mainImage === thumb ? 'border-primary' : 'border-transparent hover:border-gray-300'
              ]"
              @click="changeImage(thumb)"
            >
              <img :src="thumb" class="w-full h-full object-cover" alt="">
            </button>
          </div>
        </div>

        <!-- Product Info -->
        <div class="product-info">
          <h1 class="text-3xl font-bold text-text mb-4">{{ product.title }}</h1>
          
          <div class="flex items-center gap-4 mb-6">
            <span class="text-3xl font-bold text-primary">${{ product.price }}</span>
            <span v-if="product.originalPrice" class="text-xl text-text-light line-through">
              ${{ product.originalPrice }}
            </span>
            <span v-if="product.originalPrice" class="bg-danger text-white text-sm px-2 py-1 rounded">
              {{ Math.round((1 - product.price / product.originalPrice) * 100) }}% OFF
            </span>
          </div>

          <!-- Color Selection -->
          <div class="mb-6">
            <h3 class="text-sm font-semibold mb-3">Color: <span class="text-text-light capitalize">{{ selectedColor }}</span></h3>
            <div class="flex gap-3">
              <button 
                v-for="color in colors"
                :key="color.name"
                :class="[
                  'color-btn w-10 h-10 rounded-full border-2 transition-all',
                  selectedColor === color.name ? 'border-primary scale-110' : 'border-transparent'
                ]"
                :style="{ backgroundColor: color.hex }"
                @click="selectColor(color.name)"
              ></button>
            </div>
          </div>

          <!-- Size Selection -->
          <div class="mb-6">
            <h3 class="text-sm font-semibold mb-3">Size: <span class="text-text-light">{{ selectedSize }}</span></h3>
            <div class="flex gap-3">
              <button 
                v-for="size in sizes"
                :key="size"
                :class="[
                  'size-select-btn min-w-[48px] h-12 px-4 rounded-xl border-2 font-semibold transition-all',
                  selectedSize === size 
                    ? 'border-primary bg-primary text-white' 
                    : 'border-border bg-white text-text hover:border-primary'
                ]"
                @click="selectSize(size)"
              >
                {{ size }}
              </button>
            </div>
          </div>

          <!-- Quantity -->
          <div class="mb-8">
            <h3 class="text-sm font-semibold mb-3">Quantity</h3>
            <div class="flex items-center gap-4">
              <div class="qty-input flex items-center gap-4 border border-border rounded-xl px-4 py-3">
                <button 
                  class="w-8 h-8 flex items-center justify-center text-text-light hover:text-primary transition-colors"
                  @click="updateQty(-1)"
                >
                  <i class="fa-solid fa-minus"></i>
                </button>
                <span class="w-8 text-center font-bold text-lg">{{ quantity }}</span>
                <button 
                  class="w-8 h-8 flex items-center justify-center text-text-light hover:text-primary transition-colors"
                  @click="updateQty(1)"
                >
                  <i class="fa-solid fa-plus"></i>
                </button>
              </div>
              <span class="text-text-light text-sm">Only 12 items left</span>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex gap-4">
            <button 
              class="flex-1 bg-primary text-white py-4 px-8 rounded-xl font-bold text-lg hover:bg-primary-dark transition-colors"
              @click="addToCart"
            >
              Add to Cart
            </button>
            <button 
              :class="[
                'w-14 h-14 rounded-xl border-2 flex items-center justify-center transition-all',
                isInWishlist 
                  ? 'border-danger bg-danger/10 text-danger' 
                  : 'border-border bg-white text-text-light hover:border-danger hover:text-danger'
              ]"
              @click="toggleWishlist"
            >
              <i :class="isInWishlist ? 'fa-solid fa-heart' : 'fa-regular fa-heart'" class="text-xl"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Tabs -->
      <div class="mt-12">
        <div class="tabs flex gap-8 border-b border-border mb-6">
          <button 
            :class="[
              'tab-btn py-4 font-semibold text-sm border-b-2 transition-all',
              activeTab === 'description' ? 'border-primary text-primary' : 'border-transparent text-text-light hover:text-text'
            ]"
            @click="activeTab = 'description'"
          >
            Description
          </button>
          <button 
            :class="[
              'tab-btn py-4 font-semibold text-sm border-b-2 transition-all',
              activeTab === 'reviews' ? 'border-primary text-primary' : 'border-transparent text-text-light hover:text-text'
            ]"
            @click="activeTab = 'reviews'"
          >
            Reviews (24)
          </button>
        </div>

        <div v-show="activeTab === 'description'" class="tab-content">
          <p class="text-text-light leading-relaxed">
            Premium quality urban sweatshirt with modern design. Made from 100% organic cotton for maximum comfort. 
            Features a relaxed fit with ribbed cuffs and hem. Perfect for casual outings or lounging at home.
          </p>
          <ul class="mt-4 space-y-2 text-text-light">
            <li><i class="fa-solid fa-check text-success mr-2"></i> 100% Organic Cotton</li>
            <li><i class="fa-solid fa-check text-success mr-2"></i> Relaxed Fit</li>
            <li><i class="fa-solid fa-check text-success mr-2"></i> Machine Washable</li>
            <li><i class="fa-solid fa-check text-success mr-2"></i> Sustainable Production</li>
          </ul>
        </div>

        <div v-show="activeTab === 'reviews'" class="tab-content">
          <div class="space-y-6">
            <div class="review-item flex gap-4 p-4 bg-background rounded-xl">
              <img src="https://ui-avatars.com/api/?name=Sarah+M&background=5F2EEA&color=fff" class="w-12 h-12 rounded-full" alt="">
              <div>
                <div class="flex items-center gap-2 mb-2">
                  <span class="font-semibold">Sarah M.</span>
                  <div class="text-warning text-sm">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                  </div>
                </div>
                <p class="text-text-light text-sm">Amazing quality! The fabric is so soft and the fit is perfect. Highly recommend!</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
