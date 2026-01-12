<script setup>
import { ref, onMounted, computed } from "vue";
import { useRouter } from "vue-router";
import { getProductsList } from "@/data/products";
import { useHomeStore } from "@/stores/user/HomeStore";
import { useProductShopStore } from "@/stores/user/ProductShop";
import { storeToRefs } from "pinia";

const router = useRouter();
//const featuredProducts = ref(getProductsList().slice(0, 8));
const isLoaded = ref(false);

const homeStore = useHomeStore();

const goToCategory = (id) => {
  router.push({ name: "shop", query: { category_id: id } });
};

onMounted(() => {
  // FIXED: Typo 'fet' -> 'fetch'
  homeStore.fetchHomeCategories();
  homeStore.fetchHomeFeatured();
  homeStore.getHomeBestSellers();

  setTimeout(() => {
    isLoaded.value = true;
  }, 100);
});

const BASE_URL = "http://127.0.0.1:8000";

const getProductImage = (product) => {
  if (product.images && product.images.length > 0) {
    const primary = product.images.find((img) => img.is_primary);

    const imagePath = primary ? primary.image_url : product.images[0].image_url;

    return `${BASE_URL}/storage/${imagePath}`;
  }

  return "/images/placeholder.jpg";
};

const features = [
  {
    icon: "fa-solid fa-truck-fast",
    title: "Free Shipping",
    desc: "On orders over $50",
    color: "bg-blue-500",
  },
  {
    icon: "fa-solid fa-rotate-left",
    title: "Easy Returns",
    desc: "30-day return policy",
    color: "bg-green-500",
  },
  {
    icon: "fa-solid fa-shield-halved",
    title: "Secure Payment",
    desc: "100% secure checkout",
    color: "bg-purple-500",
  },
  {
    icon: "fa-solid fa-headset",
    title: "24/7 Support",
    desc: "Dedicated support",
    color: "bg-orange-500",
  },
];

const testimonials = [
  {
    id: 1,
    name: "Sarah Johnson",
    role: "Fashion Enthusiast",
    avatar: "https://ui-avatars.com/api/?name=Sarah+Johnson&background=5F2EEA&color=fff",
    text: "Amazing quality and super fast delivery! I'm in love with my wardrobe.",
    rating: 5,
  },
  {
    id: 2,
    name: "Michael Chen",
    role: "Style Blogger",
    avatar: "https://ui-avatars.com/api/?name=Michael+Chen&background=E91E63&color=fff",
    text: "The best online fashion store I've ever used. Great selection and prices!",
    rating: 5,
  },
  {
    id: 3,
    name: "Emily Davis",
    role: "Regular Customer",
    avatar: "https://ui-avatars.com/api/?name=Emily+Davis&background=00BCD4&color=fff",
    text: "Customer service is exceptional. They really care about their customers.",
    rating: 5,
  },
];

function goToShop() {
  router.push({ name: "shop" });
}

function goToProduct(id) {
  router.push({ name: "product", params: { id } });
}
</script>

<template>
  <div class="home-page" :class="{ loaded: isLoaded }">
    <section
      class="hero-section relative overflow-hidden rounded-2xl sm:rounded-3xl mb-10 sm:mb-14 lg:mb-20"
    >
      <div class="hero-bg absolute inset-0">
        <div
          class="absolute inset-0 bg-gradient-to-br from-violet-600 via-purple-600 to-indigo-800"
        ></div>
        <div
          class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"
        ></div>
      </div>

      <div
        class="absolute top-10 left-10 w-32 h-32 bg-gradient-to-br from-pink-400/30 to-purple-400/30 rounded-full blur-3xl animate-float"
      ></div>
      <div
        class="absolute bottom-10 right-10 w-48 h-48 bg-gradient-to-br from-blue-400/30 to-cyan-400/30 rounded-full blur-3xl animate-float-reverse"
      ></div>
      <div
        class="absolute top-1/2 left-1/3 w-24 h-24 bg-gradient-to-br from-yellow-400/20 to-orange-400/20 rounded-full blur-2xl animate-pulse-slow"
      ></div>

      <div
        class="hero-content relative z-10 flex flex-col lg:flex-row items-center justify-between px-6 sm:px-10 lg:px-16 py-14 sm:py-18 lg:py-24 gap-10"
      >
        <div class="hero-text text-center lg:text-left text-white max-w-2xl">
          <div
            class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md rounded-full text-sm font-medium mb-8 border border-white/20 animate-slide-down"
          >
            <span class="flex h-2 w-2 relative">
              <span
                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"
              ></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-green-400"></span>
            </span>
            Collection 2026 is here!
          </div>

          <h1
            class="text-4xl sm:text-5xl lg:text-7xl font-black leading-[1.1] mb-6 animate-slide-up"
          >
            Elevate Your
            <span class="relative inline-block">
              <span
                class="relative z-10 text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 via-orange-300 to-pink-300"
                >Style Game</span
              >
              <svg
                class="absolute -bottom-2 left-0 w-full"
                viewBox="0 0 200 12"
                preserveAspectRatio="none"
              >
                <path
                  d="M0,8 Q50,0 100,8 T200,8"
                  stroke="url(#underline-gradient)"
                  stroke-width="4"
                  fill="none"
                  stroke-linecap="round"
                />
                <defs>
                  <linearGradient id="underline-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" style="stop-color: #fcd34d" />
                    <stop offset="50%" style="stop-color: #fb923c" />
                    <stop offset="100%" style="stop-color: #f472b6" />
                  </linearGradient>
                </defs>
              </svg>
            </span>
          </h1>

          <p
            class="text-lg sm:text-xl text-white/80 mb-10 max-w-xl mx-auto lg:mx-0 animate-slide-up-delay"
          >
            Discover premium fashion that defines who you are. Curated collections for the modern
            trendsetter.
          </p>

          <div
            class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start animate-fade-in-delay"
          >
            <button
              @click="goToShop"
              class="group relative px-8 py-4 bg-white text-gray-900 font-bold rounded-2xl overflow-hidden transition-all duration-300 transform hover:scale-105 hover:shadow-2xl hover:shadow-white/25"
            >
              <span class="relative z-10 flex items-center justify-center gap-2">
                <span>Explore Collection</span>
                <i
                  class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"
                ></i>
              </span>
              <div
                class="absolute inset-0 bg-gradient-to-r from-yellow-300 to-orange-300 opacity-0 group-hover:opacity-100 transition-opacity"
              ></div>
            </button>
            <button
              class="group px-8 py-4 border-2 border-white/30 text-white font-semibold rounded-2xl backdrop-blur-sm hover:bg-white/10 hover:border-white/50 transition-all duration-300 flex items-center justify-center gap-3"
            >
              <div
                class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center group-hover:scale-110 transition-transform"
              >
                <i class="fa-solid fa-play text-sm ml-0.5"></i>
              </div>
              <span>Watch Lookbook</span>
            </button>
          </div>

          <div
            class="flex items-center justify-center lg:justify-start gap-8 sm:gap-12 mt-12 pt-10 border-t border-white/10"
          >
            <div class="text-center animate-count">
              <div
                class="text-3xl sm:text-4xl font-black bg-gradient-to-r from-white to-white/80 bg-clip-text text-transparent"
              >
                500+
              </div>
              <div class="text-sm text-white/60 mt-1">Premium Products</div>
            </div>
            <div class="w-px h-12 bg-white/20"></div>
            <div class="text-center animate-count">
              <div
                class="text-3xl sm:text-4xl font-black bg-gradient-to-r from-white to-white/80 bg-clip-text text-transparent"
              >
                50K+
              </div>
              <div class="text-sm text-white/60 mt-1">Happy Customers</div>
            </div>
            <div class="w-px h-12 bg-white/20 hidden sm:block"></div>
            <div class="text-center hidden sm:block animate-count">
              <div
                class="text-3xl sm:text-4xl font-black bg-gradient-to-r from-white to-white/80 bg-clip-text text-transparent"
              >
                4.9★
              </div>
              <div class="text-sm text-white/60 mt-1">Customer Rating</div>
            </div>
          </div>
        </div>

        <div class="hero-image relative animate-slide-left">
          <div class="relative flex gap-4">
            <div class="relative">
              <div
                class="absolute -inset-3 bg-gradient-to-r from-yellow-400 via-pink-500 to-purple-600 rounded-2xl blur-xl opacity-40 animate-pulse-slow"
              ></div>
              <img
                src="https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=280&h=350&fit=crop"
                alt="Fashion Sweater"
                class="relative w-36 sm:w-44 lg:w-56 h-44 sm:h-56 lg:h-72 rounded-2xl shadow-2xl object-cover"
              />
            </div>
            <div class="flex flex-col gap-4">
              <img
                src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=200&h=160&fit=crop"
                alt="Fashion Jacket"
                class="w-28 sm:w-32 lg:w-40 h-20 sm:h-24 lg:h-32 rounded-xl shadow-xl object-cover transform hover:scale-105 transition-transform"
              />
              <img
                src="https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=200&h=160&fit=crop"
                alt="Fashion Shirt"
                class="w-28 sm:w-32 lg:w-40 h-20 sm:h-24 lg:h-32 rounded-xl shadow-xl object-cover transform hover:scale-105 transition-transform"
              />
            </div>
          </div>

          <div
            class="absolute -left-8 sm:-left-16 bottom-16 bg-white/95 backdrop-blur-xl rounded-2xl p-4 shadow-2xl animate-float border border-gray-100"
          >
            <div class="flex items-center gap-3">
              <div
                class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/30"
              >
                <i class="fa-solid fa-fire text-white"></i>
              </div>
              <div>
                <div class="text-xs text-gray-500 font-medium">Trending Now</div>
                <div class="font-bold text-gray-800">Summer Collection</div>
              </div>
            </div>
          </div>

          <div
            class="absolute -right-4 sm:-right-8 top-16 bg-gradient-to-r from-orange-500 to-red-500 text-white px-5 py-3 rounded-2xl shadow-xl shadow-orange-500/30 transform rotate-6 animate-bounce-slow"
          >
            <div class="text-xs font-medium opacity-80">Limited Time</div>
            <div class="font-black text-xl">-50% OFF</div>
          </div>

          <div
            class="absolute -right-4 bottom-32 bg-white/95 backdrop-blur-xl rounded-2xl p-3 shadow-xl animate-float-reverse border border-gray-100"
          >
            <div class="flex items-center gap-2">
              <div class="flex -space-x-2">
                <img
                  src="https://ui-avatars.com/api/?name=A&background=5F2EEA&color=fff&size=32"
                  class="w-8 h-8 rounded-full border-2 border-white"
                  alt=""
                />
                <img
                  src="https://ui-avatars.com/api/?name=B&background=E91E63&color=fff&size=32"
                  class="w-8 h-8 rounded-full border-2 border-white"
                  alt=""
                />
                <img
                  src="https://ui-avatars.com/api/?name=C&background=00BCD4&color=fff&size=32"
                  class="w-8 h-8 rounded-full border-2 border-white"
                  alt=""
                />
              </div>
              <div class="text-xs">
                <div class="font-bold text-gray-800">2.5k+</div>
                <div class="text-gray-500">Reviews</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="features-bar grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-14 sm:mb-20">
      <div
        v-for="(feature, index) in features"
        :key="feature.title"
        :style="{ animationDelay: `${index * 100}ms` }"
        class="feature-card group bg-white rounded-2xl sm:rounded-3xl p-5 sm:p-6 flex items-center gap-4 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 border border-gray-100 animate-fade-in-up"
      >
        <div
          :class="[
            feature.color,
            'feature-icon w-12 h-12 sm:w-14 sm:h-14 text-white rounded-2xl flex items-center justify-center text-lg sm:text-xl shadow-lg group-hover:scale-110 group-hover:rotate-6 transition-all duration-300',
          ]"
        >
          <i :class="feature.icon"></i>
        </div>
        <div>
          <h4 class="font-bold text-sm sm:text-base text-gray-800">{{ feature.title }}</h4>
          <p class="text-xs sm:text-sm text-gray-500">{{ feature.desc }}</p>
        </div>
      </div>
    </section>

    <section class="categories-section mb-14 sm:mb-20">
      <div
        class="section-header flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4 mb-8 sm:mb-10"
      >
        <div>
          <span
            class="inline-block px-3 py-1 bg-primary/10 text-primary text-sm font-semibold rounded-lg mb-3"
            >Categories</span
          >
          <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mb-2">Shop by Category</h2>
          <p class="text-gray-500 text-base sm:text-lg">Find exactly what you're looking for</p>
        </div>
        <button
          @click="goToShop()"
          class="group flex items-center gap-2 text-primary font-semibold hover:gap-4 transition-all px-5 py-2.5 bg-primary/5 rounded-xl hover:bg-primary/10"
        >
          View All Categories
          <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
        </button>
      </div>
      <div v-if="homeStore.isLoading" class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div v-for="n in 8" :key="n" class="animate-pulse">
          <div class="bg-gray-200 h-64 rounded-2xl mb-4"></div>
          <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
          <div class="h-4 bg-gray-200 rounded w-1/4"></div>
        </div>
      </div>
      <div class="categories-grid grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div
          v-for="(category, index) in homeStore.categories"
          :key="category.id"
          @click="goToCategory(category.id)"
          :style="{ animationDelay: `${index * 150}ms` }"
          class="category-card group bg-white rounded-2xl sm:rounded-3xl p-6 sm:p-8 cursor-pointer animate-fade-in-up border border-gray-100 hover:border-primary/30 hover:shadow-xl transition-all duration-500 hover:-translate-y-2"
        >
          <h3
            class="font-bold text-lg sm:text-xl text-gray-800 mb-2 group-hover:text-primary transition-colors"
          >
            {{ category.name }}
          </h3>
          <p class="text-gray-500 text-sm mb-4">{{ category.products_count }} Products</p>
          <div
            class="flex items-center gap-2 text-primary font-semibold text-sm opacity-0 group-hover:opacity-100 transition-opacity"
          >
            <span>Explore</span>
            <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
          </div>
        </div>
      </div>
    </section>

    <section class="featured-banner-section mb-14 sm:mb-20">
      <div
        class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 rounded-3xl p-6 sm:p-10 lg:p-14 relative overflow-hidden"
      >
        <div class="absolute inset-0 opacity-10">
          <div class="absolute top-10 right-10 w-64 h-64 bg-purple-500 rounded-full blur-3xl"></div>
          <div class="absolute bottom-10 left-10 w-48 h-48 bg-blue-500 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10">
          <div
            class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 mb-10"
          >
            <div>
              <span
                class="inline-block px-3 py-1 bg-white/10 text-white text-sm font-semibold rounded-lg mb-3 backdrop-blur-sm"
                >⭐ Featured</span
              >
              <h2 class="text-3xl sm:text-4xl font-black text-white mb-2">Featured Collection</h2>
              <p class="text-gray-400">Handpicked items just for you</p>
            </div>
            <button
              @click="goToShop"
              class="px-6 py-3 bg-white text-gray-900 font-bold rounded-xl hover:bg-gray-100 transition-all flex items-center gap-2"
            >
              Shop Featured <i class="fa-solid fa-arrow-right"></i>
            </button>
          </div>
          <div v-if="homeStore.isLoading" class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div v-for="n in 8" :key="n" class="animate-pulse">
              <div class="bg-gray-200 h-64 rounded-2xl mb-4"></div>
              <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
              <div class="h-4 bg-gray-200 rounded w-1/4"></div>
            </div>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
            <div
              v-for="product in homeStore.featuredProducts.slice(0, 6)"
              :key="product.id"
              @click="goToProduct(product.id)"
              class="group relative rounded-xl overflow-hidden cursor-pointer"
            >
              <div class="aspect-[4/5]">
                <img
                  :src="getProductImage(product)"
                  :alt="product.title"
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                />
              </div>
              <div
                class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"
              ></div>
              <div v-if="product.badge" class="absolute top-2 left-2">
                <span
                  class="px-2 py-0.5 bg-white text-gray-900 text-[10px] font-bold rounded-full"
                  >{{ product.badge }}</span
                >
              </div>
              <div class="absolute bottom-0 left-0 right-0 p-3">
                <p class="text-white/70 text-[10px] mb-0.5">{{ product.category?.name }}</p>
                <h3 class="text-white font-bold text-xs sm:text-sm mb-1 line-clamp-1">
                  {{ product.name }}
                </h3>
                <span class="text-white font-bold text-sm">${{ product.final_price }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="featured-section mb-14 sm:mb-20">
      <div class="section-header text-center mb-10">
        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3">Handpicked For You</h2>
        <p class="text-gray-500 text-base max-w-md mx-auto">
          Curated collection of our finest pieces
        </p>
      </div>
      <div v-if="homeStore.isLoading" class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div v-for="n in 8" :key="n" class="animate-pulse">
          <div class="bg-gray-200 h-64 rounded-2xl mb-4"></div>
          <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
          <div class="h-4 bg-gray-200 rounded w-1/4"></div>
        </div>
      </div>
      <div class="products-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 sm:gap-8">
        <div
          v-for="p in homeStore.BestSellersProduct.slice(0, 12)"
          :key="p.id"
          @click="goToProduct(p.id)"
          class="product-card group cursor-pointer"
        >
          <div class="relative overflow-hidden rounded-2xl bg-gray-100 mb-4">
            <img
              :src="getProductImage(p)"
              :alt="p.name"
              class="w-full aspect-[3/4] object-cover transition-transform duration-500 group-hover:scale-105"
            />

            <div v-if="p.badge" class="absolute top-3 left-3">
              <span
                class="px-3 py-1.5 bg-gray-900 text-white text-[10px] font-semibold uppercase tracking-wide rounded-full"
              >
                {{ p.badge }}
              </span>
            </div>

            <!--  <button
              @click.stop
              class="absolute bottom-4 left-4 right-4 py-3 bg-white text-gray-900 font-semibold rounded-xl text-sm opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-3 group-hover:translate-y-0 hover:bg-gray-900 hover:text-white"
            >
              Add to Cart
            </button> -->
          </div>

          <div class="px-1">
            <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">
              {{ p.brand?.name || "Brand" }}
            </p>
            <h3
              class="font-medium text-gray-900 text-sm sm:text-base mb-2 line-clamp-1 group-hover:text-primary transition-colors"
            >
              {{ p.name }}
            </h3>

            <div class="flex items-center gap-1.5 mb-2">
              <div class="flex text-amber-400 text-xs">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star-half-alt"></i>
              </div>
              <span class="text-gray-400 text-xs">({{ p.rating || "4.5" }})</span>
            </div>

            <div class="flex items-center gap-2">
              <span class="font-bold text-gray-900">${{ p.final_price }}</span>

              <span v-if="p.discount > 0" class="text-gray-400 text-sm line-through"
                >${{ p.price }}</span
              >
            </div>
          </div>
        </div>
      </div>

      <div class="text-center mt-10">
        <button
          @click="goToShop"
          class="px-8 py-3.5 border-2 border-gray-900 text-gray-900 font-semibold rounded-full hover:bg-gray-900 hover:text-white transition-all duration-300"
        >
          View All Products
        </button>
      </div>
    </section>
    <section class="testimonials-section mb-14 sm:mb-20">
      <div class="text-center mb-10">
        <span
          class="inline-block px-3 py-1 bg-primary/10 text-primary text-sm font-semibold rounded-lg mb-3"
          >Testimonials</span
        >
        <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mb-2">What Our Customers Say</h2>
        <p class="text-gray-500 text-base sm:text-lg">Real feedback from real fashion lovers</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
          v-for="testimonial in testimonials"
          :key="testimonial.id"
          class="testimonial-card bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 hover:shadow-xl transition-all duration-500 hover:-translate-y-1"
        >
          <div class="flex items-center gap-1 text-yellow-400 mb-4">
            <i v-for="n in testimonial.rating" :key="n" class="fa-solid fa-star"></i>
          </div>
          <p class="text-gray-600 mb-6 leading-relaxed">"{{ testimonial.text }}"</p>
          <div class="flex items-center gap-3">
            <img :src="testimonial.avatar" :alt="testimonial.name" class="w-12 h-12 rounded-full" />
            <div>
              <h4 class="font-bold text-gray-800">{{ testimonial.name }}</h4>
              <p class="text-sm text-gray-500">{{ testimonial.role }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="sletter-section relative overflow-hidden rounded-3xl mb-10">
      <div
        class="absolute inset-0 bg-gradient-to-br from-primary via-purple-600 to-indigo-700"
      ></div>
      <div
        class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"
      ></div>

      <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
      <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>

      <div class="relative z-10 px-6 sm:px-10 lg:px-16 py-14 sm:py-20 text-center">
        <div class="max-w-2xl mx-auto">
          <div
            class="w-20 h-20 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-8 border border-white/20"
          >
            <i class="fa-solid fa-envelope text-3xl text-white"></i>
          </div>
          <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white mb-4">
            Join the Fashion Club
          </h2>
          <p class="text-white/80 text-lg mb-10">
            Subscribe and get 15% off your first order + exclusive access to arrivals and special
            offers.
          </p>
          <form class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
            <div class="flex-1 relative">
              <input
                type="email"
                placeholder="Enter your email address"
                class="w-full px-6 py-4 bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl text-white placeholder-white/60 focus:outline-none focus:border-white/50 focus:bg-white/20 transition-all"
              />
            </div>
            <button
              type="submit"
              class="px-8 py-4 bg-white text-gray-900 font-bold rounded-2xl hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2 shadow-xl"
            >
              Subscribe
              <i class="fa-solid fa-arrow-right"></i>
            </button>
          </form>
          <p class="text-white/50 text-sm mt-6">
            No spam, unsubscribe at any time. By subscribing, you agree to our Privacy Policy.
          </p>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
/* Animations */
@keyframes float {
  0%,
  100% {
    transform: translateY(0px) rotate(0deg);
  }
  50% {
    transform: translateY(-20px) rotate(2deg);
  }
}

@keyframes float-reverse {
  0%,
  100% {
    transform: translateY(0px) rotate(0deg);
  }
  50% {
    transform: translateY(20px) rotate(-2deg);
  }
}

@keyframes pulse-slow {
  0%,
  100% {
    opacity: 0.3;
    transform: scale(1);
  }
  50% {
    opacity: 0.5;
    transform: scale(1.1);
  }
}

@keyframes bounce-slow {
  0%,
  100% {
    transform: translateY(0) rotate(6deg);
  }
  50% {
    transform: translateY(-10px) rotate(6deg);
  }
}

@keyframes slide-down {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slide-up {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slide-left {
  from {
    opacity: 0;
    transform: translateX(60px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}

.animate-float-reverse {
  animation: float-reverse 7s ease-in-out infinite;
}

.animate-pulse-slow {
  animation: pulse-slow 4s ease-in-out infinite;
}

.animate-bounce-slow {
  animation: bounce-slow 3s ease-in-out infinite;
}

.animate-slide-down {
  animation: slide-down 0.8s ease-out forwards;
}

.animate-slide-up {
  animation: slide-up 0.8s ease-out forwards;
}

.animate-slide-up-delay {
  opacity: 0;
  animation: slide-up 0.8s ease-out 0.2s forwards;
}

.animate-fade-in-delay {
  opacity: 0;
  animation: slide-up 0.8s ease-out 0.4s forwards;
}

.animate-slide-left {
  animation: slide-left 1s ease-out forwards;
}

.animate-fade-in-up {
  opacity: 0;
  animation: fade-in-up 0.6s ease-out forwards;
}

.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Loading State */
.home-page:not(.loaded) .animate-slide-down,
.home-page:not(.loaded) .animate-slide-up,
.home-page:not(.loaded) .animate-slide-up-delay,
.home-page:not(.loaded) .animate-fade-in-delay,
.home-page:not(.loaded) .animate-slide-left {
  animation: none;
  opacity: 0;
}

.home-page.loaded .animate-slide-down,
.home-page.loaded .animate-slide-up,
.home-page.loaded .animate-slide-left {
  opacity: 1;
}
</style>
