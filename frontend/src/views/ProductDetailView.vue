<script setup lang="ts">
import { ref, computed, onMounted, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useCartStore } from "@/stores/cart";
import { useWishlistStore } from "@/stores/wishlist";
import Breadcrumb from "@/components/layout/Breadcrumb.vue";
import ProductCard from "@/components/product/ProductCard.vue";
import { useProductShopStore } from "@/stores/user/ProductShop";
import { storeToRefs } from "pinia";

const route = useRoute();
const router = useRouter();
const cartStore = useCartStore();
const wishlistStore = useWishlistStore();

// Store setup
const productShopStore = useProductShopStore();
const { product, relatedProducts, isLoading } = storeToRefs(productShopStore);

// --- Functions Definitions --

const loadPageData = async (id: string) => {
  window.scrollTo({ top: 0, behavior: "instant" });
  await productShopStore.fetchProductDetails(id);
};

const getColorHex = (colorName: string) => {
  const map: Record<string, string> = {
    black: "#000000",
    white: "#FFFFFF",
    red: "#EF4444",
    blue: "#3B82F6",
    green: "#10B981",
    navy: "#1E3A5F",
    gray: "#6B7280",
    brown: "#92400E",
  };
  return map[colorName.toLowerCase()] || colorName;
};

// --- Lifecycle Hooks & Watchers ---

onMounted(() => {
  if (route.params.id) {
    loadPageData(route.params.id as string);
  }
});

watch(
  () => route.params.id,
  (newId) => {
    if (newId) {
      loadPageData(newId as string);
    }
  }
);

watch(product, (newVal) => {
  if (newVal) {
    mainImage.value =
      newVal.images?.find((i: any) => i.is_primary)?.url || newVal.images?.[0]?.url || "";
  }
});

// --- Computed Properties ---

const availableColors = computed(() => {
  if (!product.value || !product.value.variants) return [];
  const distinctColors = [...new Set(product.value.variants.map((v: any) => v.color))];

  return distinctColors.map((color) => ({
    name: color,
    hex: getColorHex(color as string),
  }));
});

const availableSizes = computed(() => {
  if (!product.value || !product.value.variants) return [];
  return [...new Set(product.value.variants.map((v: any) => v.size))];
});

const isSizeAvailable = (size: string) => {
  if (!selectedColor.value || !product.value) return true;
  return product.value.variants?.some(
    (v: any) => v.color === selectedColor.value && v.size === size
  );
};

const selectedVariant = computed(() => {
  if (!product.value || !selectedColor.value || !selectedSize.value) return null;

  return product.value.variants?.find(
    (v: any) => v.color === selectedColor.value && v.size === selectedSize.value
  );
});

//slider images product
const thumbnails = computed(() => {
  if (product.value?.images && product.value.images.length > 0) {
    return product.value.images.map((img: any) => img.url);
  }
});

const isInWishlist = computed(() =>
  product.value ? wishlistStore.isInWishlist(product.value.id) : false
);

const breadcrumbItems = computed(() => [
  { label: "Category", to: "/" },
  { label: "Shop", to: "/shop" },
]);

// --- State ---
const mainImage = ref("");
const selectedColor = ref("");
const selectedSize = ref("");
const quantity = ref(1);
const activeTab = ref("description");

// --- Methods ---

function changeImage(src: string) {
  mainImage.value = src;
}

function selectColor(color: string) {
  selectedColor.value = color;
}

function selectSize(size: string) {
  selectedSize.value = size;
}

function updateQty(change: number) {
  quantity.value = Math.max(1, Math.min(10, quantity.value + change));
}

const addToCart = () => {
  if (!selectedVariant.value) {
    alert("Please select color and size");
    return;
  }

  console.log("Add to Cart:", {
    product_id: product.value?.id,
    variant_id: selectedVariant.value.id,
    quantity: quantity.value,
  });

  cartStore.addToCart({ ...product.value, ...selectedVariant.value, quantity: quantity.value });
  alert("Added to cart!");
};

function toggleWishlist() {
  if (product.value) wishlistStore.toggleWishlist(product.value);
}

// Reviews Data & Logic
var reviews = ref([
  {
    id: 1,
    name: "Sarah M.",
    avatar: "https://ui-avatars.com/api/?name=Sarah+M&background=5F2EEA&color=fff",
    rating: 5,
    date: "2024-12-15",
    comment: "Amazing quality! The fabric is so soft and the fit is perfect. Highly recommend!",
  },
  {
    id: 2,
    name: "John D.",
    avatar: "https://ui-avatars.com/api/?name=John+D&background=34C759&color=fff",
    rating: 4,
    date: "2024-12-10",
    comment:
      "Great sweatshirt, very comfortable. The color is slightly different from the picture but still nice.",
  },
  {
    id: 3,
    name: "Emily R.",
    avatar: "https://ui-avatars.com/api/?name=Emily+R&background=FF9F43&color=fff",
    rating: 5,
    date: "2024-12-05",
    comment: "Love it! Perfect for the winter season. Will definitely buy more colors.",
  },
]);

const newReview = ref({
  rating: 5,
  comment: "",
});

const reviewsCount = computed(() => reviews.value.length);
const currentSlide = ref(0);
const reviewsPerSlide = 2;
const totalSlides = computed(() => Math.ceil(reviews.value.length / reviewsPerSlide));
const visibleReviews = computed(() => {
  const start = currentSlide.value * reviewsPerSlide;
  return reviews.value.slice(start, start + reviewsPerSlide);
});

function nextSlide() {
  if (currentSlide.value < totalSlides.value - 1) {
    currentSlide.value++;
  } else {
    currentSlide.value = 0;
  }
}

function prevSlide() {
  if (currentSlide.value > 0) {
    currentSlide.value--;
  } else {
    currentSlide.value = totalSlides.value - 1;
  }
}

function goToSlide(index: number) {
  currentSlide.value = index;
}

function setRating(rating: number) {
  newReview.value.rating = rating;
}

function submitReview() {
  if (!newReview.value.comment.trim()) {
    alert("Please write a comment");
    return;
  }

  const review = {
    id: Date.now(),
    name: "You",
    avatar: "https://ui-avatars.com/api/?name=You&background=5F2EEA&color=fff",
    rating: newReview.value.rating,
    date: new Date().toISOString().split("T")[0],
    comment: newReview.value.comment,
  };

  reviews.value.unshift(review);
  newReview.value = { rating: 5, comment: "" };
  currentSlide.value = 0;
  alert("Thank you for your review!");
}
</script>

<template>
  <div v-if="product || isLoading" class="pb-12">
    <Breadcrumb :items="breadcrumbItems" />

    <div class="bg-white rounded-xl md:rounded-2xl p-4 sm:p-6 md:p-8">
      <div v-if="isLoading" class="grid grid-cols-1 lg:grid-cols-2 gap-10 animate-pulse">
        <div class="bg-gray-200 rounded-3xl h-[500px]"></div>
        <div class="space-y-4 py-8">
          <div class="h-8 bg-gray-200 rounded w-3/4"></div>
          <div class="h-6 bg-gray-200 rounded w-1/4"></div>
          <div class="h-32 bg-gray-200 rounded w-full"></div>
          <div class="flex gap-4">
            <div class="h-12 bg-gray-200 rounded w-1/3"></div>
            <div class="h-12 bg-gray-200 rounded w-2/3"></div>
          </div>
        </div>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-12">
        <div class="product-gallery">
          <div
            class="main-image-wrapper relative aspect-square bg-gray-100 rounded-2xl overflow-hidden mb-4"
          >
            <img :src="mainImage" :alt="product.title" class="w-full h-full object-cover" />
          </div>
          <div class="thumbnails flex gap-2 sm:gap-3 overflow-x-auto pb-2">
            <button
              v-for="(thumb, index) in thumbnails"
              :key="index"
              :class="[
                'thumb-btn w-16 h-16 sm:w-20 sm:h-20 rounded-lg sm:rounded-xl overflow-hidden border-2 transition-all flex-shrink-0',
                mainImage === thumb ? 'border-primary' : 'border-transparent hover:border-gray-300',
              ]"
              @click="changeImage(thumb)"
            >
              <img :src="thumb" class="w-full h-full object-cover" alt="" />
            </button>
          </div>
        </div>

        <div class="product-info">
          <span
            v-if="product.category"
            class="inline-block bg-primary/10 text-primary text-xs font-semibold px-3 py-1 rounded-full mb-3"
          >
            {{ typeof product.category === "object" ? product.category.name : product.category }}
          </span>

          <h1 class="text-2xl sm:text-3xl font-bold text-text mb-3">{{ product.title }}</h1>

          <p v-if="product.short_description" class="text-text-light text-sm leading-relaxed mb-4">
            {{ product.short_description }}
          </p>

          <div class="flex items-center gap-4 mb-6">
            <span class="text-2xl sm:text-3xl font-bold text-primary">${{ product.price }}</span>
            <span
              v-if="product.originalPrice"
              class="text-lg sm:text-xl text-text-light line-through"
            >
              ${{ product.originalPrice }}
            </span>
            <span
              v-if="product.originalPrice"
              class="bg-danger text-white text-xs sm:text-sm px-2 py-1 rounded"
            >
              {{ Math.round((1 - product.price / product.originalPrice) * 100) }}% OFF
            </span>
          </div>

          <div class="mb-6">
            <h3 class="text-sm font-semibold mb-3">
              Color: <span class="text-text-light capitalize">{{ selectedColor }}</span>
            </h3>
            <div class="flex gap-3">
              <button
                v-for="color in availableColors"
                :key="color.name"
                :class="[
                  'color-btn w-10 h-10 rounded-full border-2 transition-all',
                  selectedColor === color.name ? 'border-primary scale-110' : 'border-transparent',
                ]"
                :style="{ backgroundColor: color.hex }"
                @click="selectColor(color.name)"
              ></button>
            </div>
          </div>

          <div class="mb-6">
            <h3 class="text-sm font-semibold mb-3">
              Size: <span class="text-text-light">{{ selectedSize }}</span>
            </h3>
            <div class="flex gap-3">
              <button
                v-for="size in availableSizes"
                :key="size"
                :class="[
                  'size-select-btn min-w-[48px] h-12 px-4 rounded-xl border-2 font-semibold transition-all',
                  selectedSize === size
                    ? 'border-primary bg-primary text-white'
                    : 'border-border bg-white text-text hover:border-primary',
                ]"
                @click="selectSize(size)"
              >
                {{ size }}
              </button>
            </div>
          </div>

          <div class="mb-8">
            <h3 class="text-sm font-semibold mb-3">Quantity</h3>
            <div class="flex items-center gap-4">
              <div
                class="qty-input flex items-center gap-4 border border-border rounded-xl px-4 py-3"
              >
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
                  : 'border-border bg-white text-text-light hover:border-danger hover:text-danger',
              ]"
              @click="toggleWishlist"
            >
              <i
                :class="isInWishlist ? 'fa-solid fa-heart' : 'fa-regular fa-heart'"
                class="text-xl"
              ></i>
            </button>
          </div>

          <div class="mt-6 p-4 bg-background rounded-xl space-y-3">
            <div class="flex items-center gap-3">
              <div
                class="w-8 h-8 rounded-lg bg-success/10 flex items-center justify-center shrink-0"
              >
                <i class="fa-solid fa-truck-fast text-success text-sm"></i>
              </div>
              <div class="flex items-center gap-2">
                <span class="text-text-light text-sm">Shipping:</span>
                <span v-if="product.shipping == 0" class="font-bold text-success">Free</span>
                <span v-else class="font-bold text-success">{{ product.shipping }} $</span>
              </div>
            </div>
            <div v-if="product.category" class="flex items-center gap-3">
              <div
                class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center shrink-0"
              >
                <i class="fa-solid fa-tag text-primary text-sm"></i>
              </div>
              <div class="flex items-center gap-2">
                <span class="text-text-light text-sm">Category:</span>
                <span class="font-bold text-text">{{
                  typeof product.category === "object" ? product.category.name : product.category
                }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="!isLoading && product">
      <div class="mt-12">
        <div class="tabs flex gap-8 border-b border-border mb-6">
          <button
            :class="[
              'tab-btn py-4 font-semibold text-sm border-b-2 transition-all',
              activeTab === 'description'
                ? 'border-primary text-primary'
                : 'border-transparent text-text-light hover:text-text',
            ]"
            @click="activeTab = 'description'"
          >
            Description
          </button>
          <button
            :class="[
              'tab-btn py-4 font-semibold text-sm border-b-2 transition-all',
              activeTab === 'reviews'
                ? 'border-primary text-primary'
                : 'border-transparent text-text-light hover:text-text',
            ]"
            @click="activeTab = 'reviews'"
          >
            Reviews ({{ reviewsCount }})
          </button>
        </div>

        <div v-show="activeTab === 'description'" class="tab-content">
          <p class="text-text-light leading-relaxed">
            {{ product.short_description || "No description available." }}
          </p>
        </div>

        <div v-show="activeTab === 'reviews'" class="tab-content">
          <div class="write-review bg-background rounded-xl p-6 mb-8">
            <h3 class="font-bold text-lg mb-4">Write a Review</h3>
            <div class="mb-4">
              <label class="block text-sm font-medium text-text-light mb-2">Your Rating</label>
              <div class="flex gap-2">
                <button
                  v-for="star in 5"
                  :key="star"
                  class="text-2xl transition-colors"
                  :class="
                    star <= newReview.rating ? 'text-warning' : 'text-gray-300 hover:text-warning'
                  "
                  @click="setRating(star)"
                >
                  <i class="fa-solid fa-star"></i>
                </button>
              </div>
            </div>
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
            <div class="slider-container overflow-hidden">
              <div
                class="slider-track grid grid-cols-1 md:grid-cols-2 gap-4 transition-all duration-300"
              >
                <div
                  v-for="review in visibleReviews"
                  :key="review.id"
                  class="review-card bg-background rounded-xl p-5 border border-border"
                >
                  <div class="flex items-start gap-4">
                    <img :src="review.avatar" class="w-14 h-14 rounded-full shrink-0" alt="" />
                    <div class="flex-1 min-w-0">
                      <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2 mb-1">
                        <span class="font-bold">{{ review.name }}</span>
                        <span class="text-xs text-text-light">{{ review.date }}</span>
                      </div>
                      <div class="text-warning text-sm mb-3">
                        <i
                          v-for="star in 5"
                          :key="star"
                          :class="[
                            'fa-star',
                            star <= review.rating ? 'fa-solid' : 'fa-regular text-gray-300',
                          ]"
                        ></i>
                      </div>
                      <p class="text-text-light text-sm leading-relaxed">
                        {{ review.comment }}
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="slider-dots flex justify-center gap-2 mt-6" v-if="totalSlides > 1">
              <button
                v-for="(dot, index) in totalSlides"
                :key="index"
                :class="[
                  'w-3 h-3 rounded-full transition-all',
                  currentSlide === index ? 'bg-primary w-8' : 'bg-gray-300 hover:bg-gray-400',
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

      <div class="mt-12 bg-white rounded-2xl p-6 sm:p-8">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-xl sm:text-2xl font-bold">You May Also Like</h2>
          <router-link to="/" class="text-primary text-sm font-semibold hover:underline">
            View All <i class="fa-solid fa-arrow-right ml-1"></i>
          </router-link>
        </div>
        <div v-if="isLoading" class="grid grid-cols-2 md:grid-cols-4 gap-6">
          <div v-for="n in 8" :key="n" class="animate-pulse">
            <div class="bg-gray-200 h-64 rounded-2xl mb-4"></div>
            <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
            <div class="h-4 bg-gray-200 rounded w-1/4"></div>
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
          <template v-if="relatedProducts?.length">
            <ProductCard v-for="p in relatedProducts" :key="p.id" :product="p" />
          </template>
          <div v-else class="col-span-full text-center text-gray-400 py-10">
            No related products found.
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
