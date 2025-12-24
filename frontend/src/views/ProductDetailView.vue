<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cart'
import { useWishlistStore } from '@/stores/wishlist'
import { getProductById, getProductsList } from '@/data/products'
import Breadcrumb from '@/components/layout/Breadcrumb.vue'
import ProductCard from '@/components/product/ProductCard.vue'

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

// Related Products - get 4 products excluding current
const relatedProducts = computed(() => {
  const allProducts = getProductsList()
  return allProducts
    .filter(p => p.id !== product.value.id)
    .sort(() => Math.random() - 0.5)
    .slice(0, 4)
})

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

// Reviews
const reviews = ref([
  {
    id: 1,
    name: 'Sarah M.',
    avatar: 'https://ui-avatars.com/api/?name=Sarah+M&background=5F2EEA&color=fff',
    rating: 5,
    date: '2024-12-15',
    comment: 'Amazing quality! The fabric is so soft and the fit is perfect. Highly recommend!'
  },
  {
    id: 2,
    name: 'John D.',
    avatar: 'https://ui-avatars.com/api/?name=John+D&background=34C759&color=fff',
    rating: 4,
    date: '2024-12-10',
    comment: 'Great sweatshirt, very comfortable. The color is slightly different from the picture but still nice.'
  },
  {
    id: 3,
    name: 'Emily R.',
    avatar: 'https://ui-avatars.com/api/?name=Emily+R&background=FF9F43&color=fff',
    rating: 5,
    date: '2024-12-05',
    comment: 'Love it! Perfect for the winter season. Will definitely buy more colors.'
  }
])

const newReview = ref({
  rating: 5,
  comment: ''
})

const reviewsCount = computed(() => reviews.value.length)

// Reviews Slider
const currentSlide = ref(0)
const reviewsPerSlide = 2

const totalSlides = computed(() => Math.ceil(reviews.value.length / reviewsPerSlide))
const visibleReviews = computed(() => {
  const start = currentSlide.value * reviewsPerSlide
  return reviews.value.slice(start, start + reviewsPerSlide)
})

function nextSlide() {
  if (currentSlide.value < totalSlides.value - 1) {
    currentSlide.value++
  } else {
    currentSlide.value = 0 // Loop to start
  }
}

function prevSlide() {
  if (currentSlide.value > 0) {
    currentSlide.value--
  } else {
    currentSlide.value = totalSlides.value - 1 // Loop to end
  }
}

function goToSlide(index) {
  currentSlide.value = index
}

function setRating(rating) {
  newReview.value.rating = rating
}

function submitReview() {
  if (!newReview.value.comment.trim()) {
    alert('Please write a comment')
    return
  }
  
  const review = {
    id: Date.now(),
    name: 'You',
    avatar: 'https://ui-avatars.com/api/?name=You&background=5F2EEA&color=fff',
    rating: newReview.value.rating,
    date: new Date().toISOString().split('T')[0],
    comment: newReview.value.comment
  }
  
  reviews.value.unshift(review)
  newReview.value = { rating: 5, comment: '' }
  currentSlide.value = 0 // Go back to first slide to show new review
  alert('Thank you for your review!')
}
</script>

<template>
  <div class="pb-12">
    <Breadcrumb :items="breadcrumbItems" />
    
    <div class="bg-white rounded-xl md:rounded-2xl p-4 sm:p-6 md:p-8">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-12">
        <!-- Image Gallery -->
        <div class="product-gallery">
          <div class="main-image-wrapper relative aspect-square bg-gray-100 rounded-2xl overflow-hidden mb-4">
            <img 
              :src="mainImage" 
              :alt="product.title"
              class="w-full h-full object-cover"
            >
          </div>
          <div class="thumbnails flex gap-2 sm:gap-3 overflow-x-auto pb-2">
            <button 
              v-for="(thumb, index) in thumbnails"
              :key="index"
              :class="[
                'thumb-btn w-16 h-16 sm:w-20 sm:h-20 rounded-lg sm:rounded-xl overflow-hidden border-2 transition-all flex-shrink-0',
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
          <!-- Category Badge -->
          <span v-if="product.category" class="inline-block bg-primary/10 text-primary text-xs font-semibold px-3 py-1 rounded-full mb-3">
            {{ product.category }}
          </span>
          
          <h1 class="text-2xl sm:text-3xl font-bold text-text mb-3">{{ product.title }}</h1>
          
          <!-- Short Description -->
          <p v-if="product.description" class="text-text-light text-sm leading-relaxed mb-4">
            {{ product.description }}
          </p>
          
          <div class="flex items-center gap-4 mb-6">
            <span class="text-2xl sm:text-3xl font-bold text-primary">${{ product.price }}</span>
            <span v-if="product.originalPrice" class="text-lg sm:text-xl text-text-light line-through">
              ${{ product.originalPrice }}
            </span>
            <span v-if="product.originalPrice" class="bg-danger text-white text-xs sm:text-sm px-2 py-1 rounded">
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

          <!-- Shipping & Category Info -->
          <div class="mt-6 p-4 bg-background rounded-xl space-y-3">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-success/10 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-truck-fast text-success text-sm"></i>
              </div>
              <div class="flex items-center gap-2">
                <span class="text-text-light text-sm">Shipping:</span>
                <span class="font-bold text-success">Free</span>
              </div>
            </div>
            <div v-if="product.category" class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-tag text-primary text-sm"></i>
              </div>
              <div class="flex items-center gap-2">
                <span class="text-text-light text-sm">Category:</span>
                <span class="font-bold text-text">{{ product.category }}</span>
              </div>
            </div>
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
            Reviews ({{ reviewsCount }})
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
          <!-- Write Review Form -->
          <div class="write-review bg-background rounded-xl p-6 mb-8">
            <h3 class="font-bold text-lg mb-4">Write a Review</h3>
            
            <!-- Star Rating -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-text-light mb-2">Your Rating</label>
              <div class="flex gap-2">
                <button 
                  v-for="star in 5"
                  :key="star"
                  class="text-2xl transition-colors"
                  :class="star <= newReview.rating ? 'text-warning' : 'text-gray-300 hover:text-warning'"
                  @click="setRating(star)"
                >
                  <i class="fa-solid fa-star"></i>
                </button>
              </div>
            </div>
            
            <!-- Comment Input -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-text-light mb-2">Your Comment</label>
              <textarea 
                v-model="newReview.comment"
                placeholder="Share your experience with this product..."
                rows="4"
                class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary outline-none text-sm resize-none"
              ></textarea>
            </div>
            
            <button 
              class="bg-primary text-white px-6 py-3 rounded-xl font-semibold text-sm hover:bg-primary-dark transition-colors"
              @click="submitReview"
            >
              <i class="fa-solid fa-paper-plane mr-2"></i>Submit Review
            </button>
          </div>

          <!-- Reviews Slider -->
          <div class="reviews-slider">
            <div class="flex justify-between items-center mb-6">
              <h3 class="font-bold text-lg">Customer Reviews ({{ reviewsCount }})</h3>
              <div class="slider-nav flex gap-2" v-if="totalSlides > 1">
                <button 
                  class="w-10 h-10 rounded-full border border-border flex items-center justify-center hover:border-primary hover:text-primary transition-colors"
                  @click="prevSlide"
                >
                  <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button 
                  class="w-10 h-10 rounded-full border border-border flex items-center justify-center hover:border-primary hover:text-primary transition-colors"
                  @click="nextSlide"
                >
                  <i class="fa-solid fa-chevron-right"></i>
                </button>
              </div>
            </div>
            
            <!-- Slider Container -->
            <div class="slider-container overflow-hidden">
              <div class="slider-track grid grid-cols-1 md:grid-cols-2 gap-4 transition-all duration-300">
                <div 
                  v-for="review in visibleReviews"
                  :key="review.id"
                  class="review-card bg-background rounded-xl p-5 border border-border"
                >
                  <div class="flex items-start gap-4">
                    <img :src="review.avatar" class="w-14 h-14 rounded-full shrink-0" alt="">
                    <div class="flex-1 min-w-0">
                      <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2 mb-1">
                        <span class="font-bold">{{ review.name }}</span>
                        <span class="text-xs text-text-light">{{ review.date }}</span>
                      </div>
                      <div class="text-warning text-sm mb-3">
                        <i 
                          v-for="star in 5" 
                          :key="star"
                          :class="['fa-star', star <= review.rating ? 'fa-solid' : 'fa-regular text-gray-300']"
                        ></i>
                      </div>
                      <p class="text-text-light text-sm leading-relaxed">{{ review.comment }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Dots Navigation -->
            <div class="slider-dots flex justify-center gap-2 mt-6" v-if="totalSlides > 1">
              <button 
                v-for="(dot, index) in totalSlides"
                :key="index"
                :class="[
                  'w-3 h-3 rounded-full transition-all',
                  currentSlide === index ? 'bg-primary w-8' : 'bg-gray-300 hover:bg-gray-400'
                ]"
                @click="goToSlide(index)"
              ></button>
            </div>
            
            <div v-if="reviews.length === 0" class="text-center py-12 text-text-light">
              <i class="fa-solid fa-comment-dots text-5xl mb-4 opacity-30"></i>
              <p>No reviews yet. Be the first to review this product!</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Related Products Section -->
    <div class="mt-12 bg-white rounded-2xl p-6 sm:p-8">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl sm:text-2xl font-bold">You May Also Like</h2>
        <router-link to="/" class="text-primary text-sm font-semibold hover:underline">
          View All <i class="fa-solid fa-arrow-right ml-1"></i>
        </router-link>
      </div>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <ProductCard 
          v-for="relatedProduct in relatedProducts" 
          :key="relatedProduct.id" 
          :product="relatedProduct" 
        />
      </div>
    </div>
  </div>
</template>
