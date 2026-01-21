<script setup lang="ts">
import { ref, computed, onMounted, watch, reactive } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useCartStore } from "@/stores/cart";
import { useWishlistStore } from "@/stores/user/WishlistStore";
import Breadcrumb from "@/components/layout/Breadcrumb.vue";
import ProductCard from "@/components/product/ProductCard.vue";
import { useProductShopStore } from "@/stores/user/ProductShop";
import { storeToRefs } from "pinia";
import { useReviewStore } from "@/stores/user/reviewStore";
import { useAuthStore } from "@/stores/auth/auth";

const route = useRoute();
const router = useRouter();
const cartStore = useCartStore();
const wishlistStore = useWishlistStore();
const authStore = useAuthStore();
const { user } = storeToRefs(authStore);

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
  },
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
    (v: any) => v.color === selectedColor.value && v.size === size,
  );
};

const selectedVariant = computed(() => {
  if (!product.value || !selectedColor.value || !selectedSize.value) return null;

  return product.value.variants?.find(
    (v: any) => v.color === selectedColor.value && v.size === selectedSize.value,
  );
});

//slider images product
const thumbnails = computed(() => {
  if (product.value?.images && product.value.images.length > 0) {
    return product.value.images.map((img: any) => img.url);
  }
});

const isInWishlist = computed(() =>
  product.value ? wishlistStore.isInWishlist(product.value.id) : false,
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
// ADDED: State for stock message
const stockAlert = ref("");

// --- Methods ---

function changeImage(src: string) {
  mainImage.value = src;
}

function selectColor(color: string) {
  selectedColor.value = color;
  // Reset size and alert when color changes
  selectedSize.value = "";
  stockAlert.value = "";
}

// MODIFIED: Function to check stock when selecting size
function selectSize(size: string) {
  stockAlert.value = ""; // Reset alert

  if (!selectedColor.value) {
    alert("Please select a color first");
    return;
  }

  // Find the variant for this color + size
  const variant = product.value.variants?.find(
    (v: any) => v.color === selectedColor.value && v.size === size,
  );

  // Check if variant exists and has stock (assuming property is 'stock')
  if (variant && variant.stock > 0) {
    selectedSize.value = size;
  } else {
    // Show the message requested
    stockAlert.value = "This is not in stock comming soon";
    selectedSize.value = "";
  }
}

function updateQty(change: number) {
  quantity.value = Math.max(1, Math.min(10, quantity.value + change));
}

const addToCart = () => {
  if (!selectedVariant.value) {
    alert("Please select color and size");
    return;
  }

  //  Appel correct au store avec l'ID du variant et la quantité
  cartStore
    .addToCart(selectedVariant.value.id, quantity.value)
    .then(() => {
      alert("Product added to cart!");
    })
    .catch((err: any) => {
      console.error("Error adding to cart:", err);
      alert("Failed to add product to cart.");
    });
};

function toggleWishlist() {
  if (product.value) wishlistStore.toggleProduct(product.value);
}

// Reviews Data & Logic

const reviewStore = useReviewStore();
const props = defineProps(["productId", "orderId"]);

const from = reactive({
  product_id: props.productId || route.params.id,
  order_id: props.orderId,
  rating: 5,
  comment: "",
});

const loadData = () => {
  const id = props.productId || route.params.id;
  if (id && id !== "undefined") {
    reviewStore.fetchReviews(id);
    from.product_id = id;
  }
};

watch(
  () => props.productId,
  (newId) => {
    if (newId) {
      loadData();
      from.product_id = newId;
    }
  },
);

watch(
  () => props.orderId,
  (newOrderId) => {
    if (newOrderId) from.order_id = newOrderId;
  },
);

onMounted(() => {
  loadData();
});

const handleSubmit = async () => {
  from.product_id = props.productId || route.params.id;

  if (!from.product_id) {
    alert("number of product is not found");
    return;
  }

  if (!from.comment.trim()) {
    alert("Please write a comment");
    return;
  }

  const result = await reviewStore.saveReviews(from);

  if (result?.success) {
    alert("Thank you for your review!");

    from.comment = "";
    from.rating = 5;
    currentSlide.value = 0;
  }
};

const currentSlide = ref(0);
const reviewsPerSlide = 2;

const totalSlides = computed(() => Math.ceil(reviewStore.reviews.length / reviewsPerSlide));
const reviewsCount = computed(() => reviewStore.reviews.length);

const visibleReviews = computed(() => {
  const start = currentSlide.value * reviewsPerSlide;
  return reviewStore.reviews.slice(start, start + reviewsPerSlide);
});

const formatDate = (date: any) => {
  if (!date) return "";
  return new Date(date).toLocaleDateString();
};

const removeReview = async (id: number) => {
  await reviewStore.deleteReview(id);
};
//end Reviews Data & Logic

const discountPercentage = computed(() => {
  if (!selectedVariant.value || !selectedVariant.value.price || !product.value.final_price) {
    return 0;
  }
  return Math.round(
    (1 - Number(product.value.final_price) / Number(selectedVariant.value.price)) * 100,
  );
});

const currentPrice = computed(() => {
  if (selectedVariant.value && selectedVariant.value.price) {
    return Number(selectedVariant.value.price);
  }

  return Number(product.value?.final_price || 0);
});

const currentOriginalPrice = computed(() => {
  return Number(product.value?.price || 0);
});

const currentDiscountPercent = computed(() => {
  const price = currentPrice.value;
  const original = currentOriginalPrice.value;

  if (original > price && original > 0) {
    return Math.round((1 - price / original) * 100);
  }
  return 0;
});
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
            <span class="text-2xl sm:text-3xl font-bold text-primary"> ${{ currentPrice }} </span>

            <span
              v-if="currentDiscountPercent > 0"
              class="text-lg sm:text-xl text-text-light line-through"
            >
              ${{ currentOriginalPrice }}
            </span>

            <span
              v-if="currentDiscountPercent > 0"
              class="bg-danger text-white text-xs sm:text-sm px-2 py-1 rounded"
            >
              {{ currentDiscountPercent }}% OFF
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
            <div class="flex gap-3 flex-wrap">
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
            <p v-if="stockAlert" class="mt-2 text-red-500 text-sm font-semibold">
              {{ stockAlert }}
            </p>
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
              <span
                v-if="selectedVariant && selectedVariant.stock < 12 && selectedVariant.stock > 0"
                class="text-text-light text-sm"
              >
                Only {{ selectedVariant.stock }} items left
              </span>
            </div>
          </div>

          <div class="flex gap-4">
            <button
              class="flex-1 bg-primary text-white py-4 px-8 rounded-xl font-bold text-lg hover:bg-primary-dark transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
              @click="addToCart"
              :disabled="!!stockAlert || !selectedSize"
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
                  :class="star <= from.rating ? 'text-warning' : 'text-gray-300 hover:text-warning'"
                  @click="from.rating = star"
                >
                  <i class="fa-solid fa-star"></i>
                </button>
              </div>
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-text-light mb-2">Your Comment</label>
              <textarea
                v-model="from.comment"
                placeholder="Share your experience with this product..."
                rows="4"
                class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary outline-none text-sm resize-none"
              ></textarea>
            </div>
            <button
              class="bg-primary text-white px-6 py-3 rounded-xl font-semibold text-sm hover:bg-primary-dark transition-colors"
              :disabled="reviewStore.isLoading"
              @click="handleSubmit"
            >
              <i class="fa-solid fa-paper-plane mr-2"></i>
              {{ reviewStore.isLoading ? "Submitting..." : "Submit Review" }}
            </button>
            <p v-if="reviewStore.error" class="text-red-500 text-xs mt-2">
              {{ reviewStore.error }}
            </p>
          </div>

          <div class="reviews-slider">
            <div class="flex justify-between items-center mb-6">
              <h3 class="font-bold text-lg">Customer Reviews ({{ reviewStore.reviews.length }})</h3>
              <div class="slider-nav flex gap-2" v-if="totalSlides > 1">
                <button
                  class="w-10 h-10 rounded-full border border-border flex items-center justify-center"
                  @click="prevSlide"
                >
                  <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button
                  class="w-10 h-10 rounded-full border border-border flex items-center justify-center"
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
                    <img
                      :src="
                        `http://localhost:8000/storage/${review.user?.avatar}` ||
                        `https://ui-avatars.com/api/?name=${review.user.first_name}`
                      "
                      class="w-14 h-14 rounded-full shrink-0"
                    />
                    <div class="flex-1 min-w-0">
                      <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2 mb-1">
                        <span class="font-bold"
                          >{{ review.user?.first_name }} {{ review.user?.last_name }}</span
                        >
                        <span class="text-xs text-text-light">{{
                          formatDate(review.created_at)
                        }}</span>
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
                      <p class="text-text-light text-sm leading-relaxed">{{ review.comment }}</p>
                    </div>
                    <button
                      v-if="user && user.id === review.user_id"
                      class="cart-item-remove text-red-500 hover:text-red-700 transition"
                      @click="removeReview(review.id)"
                    >
                      <i class="fa-solid fa-trash"></i>
                    </button>
                  </div>
                </div>
              </div>
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
