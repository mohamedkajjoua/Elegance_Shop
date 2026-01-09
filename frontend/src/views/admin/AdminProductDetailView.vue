<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import AdminHeader from "@/components/admin/AdminHeader.vue";
import AdminNav from "@/components/admin/AdminNav.vue";
import { useAdminProductStore } from "@/stores/admin/AdminProductStore";
import { storeToRefs } from "pinia";
import { useRoute } from "vue-router";

// Mobile sidebar toggle
const sidebarOpen = ref(false);
const adminProduct = useAdminProductStore();
const route = useRoute();

// get currentProduct details
const { currentProduct, isLoading } = storeToRefs(adminProduct);
const selectedImage = ref(0);

// Base URL for images
const BASE_URL = "http://127.0.0.1:8000";

onMounted(async () => {
  const productId = Number(route.params.id);
  if (productId) {
    await adminProduct.fetchProductById(productId);
  }
});

const finalPrice = computed(() => {
  if (!currentProduct.value) return 0;
  const price = Number(currentProduct.value.price) || 0;
  const discount = Number(currentProduct.value.discount) || 0;
  return price - (price * discount) / 100;
});

const getImageUrl = (img: any) => {
  if (!img) return "/images/placeholder.jpg";

  if (typeof img === "object" && img.url) {
    return img.url;
  }

  const path = typeof img === "object" ? img.image_url : img;

  if (!path) return "/images/placeholder.jpg";

  if (path.startsWith("http")) return path;

  return `${BASE_URL}/storage/${path.replace(/^\//, "")}`;
};

const productSizes = computed(() => {
  if (!currentProduct.value?.variants) return [];
  const sizes = currentProduct.value.variants.map((v: any) => v.size);
  return [...new Set(sizes)];
});

const productColors = computed(() => {
  if (!currentProduct.value?.variants) return [];
  const uniqueColors = [...new Set(currentProduct.value.variants.map((v: any) => v.color))];

  return uniqueColors.map((color) => ({
    name: color,
    hex: color,
  }));
});

// ✅ FIX: Calculate Total Stock
const totalStock = computed(() => {
  if (!currentProduct.value?.variants) return 0;
  return currentProduct.value.variants.reduce(
    (acc: number, curr: any) => acc + Number(curr.stock),
    0
  );
});

const formatDate = (dateString: string) => {
  if (!dateString) return "N/A";
  return new Date(dateString).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex">
    <AdminNav :isOpen="sidebarOpen" @close="sidebarOpen = false" />

    <div class="flex-1 lg:ml-64">
      <AdminHeader title="PRODUCT DETAIL" @toggle-sidebar="sidebarOpen = true">
        <div class="relative hidden md:block">
          <input
            type="text"
            placeholder="Search..."
            class="w-32 lg:w-48 pl-10 pr-4 py-2 bg-gray-100 rounded-lg text-sm"
          />
          <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
      </AdminHeader>

      <main class="p-4 md:p-6">
        <div v-if="isLoading" class="flex justify-center py-20">
          <i class="fa-solid fa-spinner fa-spin text-4xl text-orange-500"></i>
        </div>

        <div v-else-if="currentProduct">
          <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
            <router-link to="/admin/products" class="hover:text-orange-500">Products</router-link>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <span class="text-slate-800 font-medium">Product Detail</span>
          </div>

          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <router-link
              to="/admin/products"
              class="inline-flex items-center gap-2 text-slate-600 hover:text-orange-500"
            >
              <i class="fa-solid fa-arrow-left"></i>
              <span>Back to Products</span>
            </router-link>
            <div class="flex items-center gap-2">
              <router-link
                :to="`/admin/products/${currentProduct.id}`"
                class="px-4 py-2 border border-gray-200 rounded-lg font-medium text-slate-600 hover:bg-gray-50 flex items-center gap-2"
              >
                <i class="fa-solid fa-pen text-sm"></i>
                <span class="hidden sm:inline">Edit</span>
              </router-link>
              <button
                class="px-4 py-2 border border-red-200 bg-red-50 rounded-lg font-medium text-red-500 hover:bg-red-100 flex items-center gap-2"
              >
                <i class="fa-solid fa-trash text-sm"></i>
                <span class="hidden sm:inline">Delete</span>
              </button>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
            <div class="lg:col-span-1 space-y-4">
              <div class="bg-white rounded-xl p-4 shadow-sm">
                <div class="aspect-square rounded-xl overflow-hidden mb-4">
                  <img
                    v-if="currentProduct.images && currentProduct.images.length > 0"
                    :src="getImageUrl(currentProduct.images[selectedImage])"
                    :alt="currentProduct.name"
                    class="w-full h-full object-cover"
                  />
                  <div
                    v-else
                    class="w-full h-full flex items-center justify-center bg-gray-50 text-gray-400"
                  >
                    No Image
                  </div>
                </div>
                <div class="grid grid-cols-4 gap-2">
                  <button
                    v-for="(img, index) in currentProduct.images"
                    :key="index"
                    @click="selectedImage = index"
                    :class="[
                      'aspect-square rounded-lg overflow-hidden border-2 transition-colors',
                      selectedImage === index
                        ? 'border-orange-500'
                        : 'border-transparent hover:border-gray-300',
                    ]"
                  >
                    <img
                      :src="getImageUrl(img)"
                      :alt="`${currentProduct.name} ${index + 1}`"
                      class="w-full h-full object-cover"
                    />
                  </button>
                </div>
              </div>

              <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
                <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4">Status</h3>
                <div class="space-y-3">
                  <div
                    class="flex items-center justify-between p-3 border border-gray-200 rounded-lg"
                  >
                    <div class="flex items-center gap-3">
                      <div
                        :class="[
                          'w-3 h-3 rounded-full',
                          currentProduct.is_active ? 'bg-green-500' : 'bg-gray-400',
                        ]"
                      ></div>
                      <span class="text-sm font-medium">Active</span>
                    </div>
                    <span
                      :class="[
                        'px-2 py-1 text-xs font-medium rounded-full',
                        currentProduct.is_active
                          ? 'bg-green-100 text-green-600'
                          : 'bg-gray-100 text-gray-600',
                      ]"
                    >
                      {{ currentProduct.is_active ? "Yes" : "No" }}
                    </span>
                  </div>
                  <div
                    class="flex items-center justify-between p-3 border border-gray-200 rounded-lg"
                  >
                    <div class="flex items-center gap-3">
                      <div
                        :class="[
                          'w-3 h-3 rounded-full',
                          currentProduct.is_featured ? 'bg-yellow-500' : 'bg-gray-400',
                        ]"
                      ></div>
                      <span class="text-sm font-medium">Featured</span>
                    </div>
                    <span
                      :class="[
                        'px-2 py-1 text-xs font-medium rounded-full',
                        currentProduct.is_featured
                          ? 'bg-yellow-100 text-yellow-600'
                          : 'bg-gray-100 text-gray-600',
                      ]"
                    >
                      {{ currentProduct.is_featured ? "Yes" : "No" }}
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <div class="lg:col-span-2 space-y-4">
              <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                  <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                      <span
                        class="px-3 py-1 bg-orange-100 text-orange-600 rounded-full text-sm font-medium"
                        >{{
                          currentProduct.category?.name || currentProduct.category || "N/A"
                        }}</span
                      >
                      <span
                        class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm font-medium"
                        >{{ currentProduct.brand?.name || currentProduct.brand || "N/A" }}</span
                      >
                    </div>
                    <h2 class="text-xl md:text-2xl font-bold text-slate-800">
                      {{ currentProduct.name }}
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                      SKU: {{ currentProduct.sku || "N/A" }}
                    </p>
                  </div>
                  <div class="text-right">
                    <div class="flex items-center gap-2 justify-end">
                      <p
                        v-if="currentProduct.discount > 0"
                        class="text-lg text-slate-400 line-through"
                      >
                        ${{ Number(currentProduct.price).toFixed(2) }}
                      </p>
                      <p class="text-2xl md:text-3xl font-bold text-orange-500">
                        ${{ finalPrice.toFixed(2) }}
                      </p>
                    </div>
                    <span
                      v-if="currentProduct.discount > 0"
                      class="px-2 py-1 bg-red-100 text-red-600 rounded-full text-xs font-medium"
                    >
                      -{{ currentProduct.discount }}% OFF
                    </span>
                    <div class="flex items-center gap-1 mt-2 text-amber-500 justify-end">
                      <i class="fa-solid fa-star text-sm"></i>
                      <span class="font-medium">{{ currentProduct.rating || 0 }}</span>
                      <span class="text-slate-500 text-sm"
                        >({{ currentProduct.reviews || 0 }} reviews)</span
                      >
                    </div>
                  </div>
                </div>

                <p class="text-slate-500 text-sm mb-3 italic">
                  "{{ currentProduct.short_description }}"
                </p>

                <p class="text-slate-600 leading-relaxed">{{ currentProduct.description }}</p>

                <div class="mt-4 pt-4 border-t border-gray-100">
                  <div class="flex items-center gap-2">
                    <i class="fa-solid fa-truck text-slate-400"></i>
                    <span v-if="currentProduct.shipping == 0" class="text-green-600 font-medium"
                      >Free Shipping</span
                    >
                    <span v-else class="text-slate-600"
                      >Shipping:
                      <strong>${{ Number(currentProduct.shipping).toFixed(2) }}</strong></span
                    >
                  </div>
                </div>
              </div>

              <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 md:gap-4">
                <div class="bg-white rounded-xl p-4 shadow-sm text-center">
                  <div
                    class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-blue-100 flex items-center justify-center mx-auto mb-2"
                  >
                    <i class="fa-solid fa-box text-blue-500 text-lg md:text-xl"></i>
                  </div>
                  <p class="text-lg md:text-2xl font-bold text-slate-800">
                    {{ totalStock }}
                  </p>
                  <p class="text-xs md:text-sm text-slate-500">In Stock</p>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm text-center">
                  <div
                    class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-green-100 flex items-center justify-center mx-auto mb-2"
                  >
                    <i class="fa-solid fa-shopping-cart text-green-500 text-lg md:text-xl"></i>
                  </div>
                  <p class="text-lg md:text-2xl font-bold text-slate-800">
                    {{ currentProduct.sales_count || 0 }}
                  </p>
                  <p class="text-xs md:text-sm text-slate-500">Total Sold</p>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm text-center">
                  <div
                    class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-amber-100 flex items-center justify-center mx-auto mb-2"
                  >
                    <i class="fa-solid fa-star text-amber-500 text-lg md:text-xl"></i>
                  </div>
                  <p class="text-lg md:text-2xl font-bold text-slate-800">
                    {{ currentProduct.rating || 0 }}
                  </p>
                  <p class="text-xs md:text-sm text-slate-500">Rating</p>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm text-center">
                  <div
                    class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-purple-100 flex items-center justify-center mx-auto mb-2"
                  >
                    <i class="fa-solid fa-comments text-purple-500 text-lg md:text-xl"></i>
                  </div>
                  <p class="text-lg md:text-2xl font-bold text-slate-800">
                    {{ currentProduct.reviews || 0 }}
                  </p>
                  <p class="text-xs md:text-sm text-slate-500">Reviews</p>
                </div>
              </div>

              <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
                <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4">Variants</h3>

                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2"
                      >Available Sizes</label
                    >
                    <div class="flex flex-wrap gap-2">
                      <span
                        v-for="size in productSizes"
                        :key="size"
                        class="px-4 py-2 bg-orange-50 border border-orange-200 text-orange-600 rounded-lg font-medium"
                      >
                        {{ size }}
                      </span>
                      <span v-if="productSizes.length === 0" class="text-sm text-gray-400"
                        >No sizes defined</span
                      >
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2"
                      >Available Colors</label
                    >
                    <div class="flex flex-wrap gap-3">
                      <div
                        v-for="color in productColors"
                        :key="color.name"
                        class="flex items-center gap-2 px-3 py-2 bg-gray-50 rounded-lg"
                      >
                        <div
                          class="w-6 h-6 rounded-full ring-2 ring-offset-1 ring-gray-200"
                          :style="{ backgroundColor: color.hex }"
                        ></div>
                        <span class="text-sm text-slate-600">{{ color.name }}</span>
                      </div>
                      <span v-if="productColors.length === 0" class="text-sm text-gray-400"
                        >No colors defined</span
                      >
                    </div>
                  </div>
                </div>
              </div>
              <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Additional Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-slate-600">Brand</span>
                    <span class="font-medium text-slate-800">
                      {{ currentProduct.brand?.name || currentProduct.brand || "N/A" }}
                    </span>
                  </div>

                  <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-slate-600">Category</span>
                    <span class="font-medium text-slate-800">
                      {{ currentProduct.category?.name || currentProduct.category || "N/A" }}
                    </span>
                  </div>

                  <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-slate-600">Created At</span>
                    <span class="font-medium text-slate-800">
                      {{ formatDate(currentProduct.created_at || currentProduct.createdAt) }}
                    </span>
                  </div>

                  <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-slate-600">Last Update</span>
                    <span class="font-medium text-slate-800">
                      {{ formatDate(currentProduct.updated_at || currentProduct.updatedAt) }}
                    </span>
                  </div>
                </div>
              </div>

              <div
                v-if="currentProduct.tags && currentProduct.tags.length > 0"
                class="bg-white rounded-xl p-4 md:p-6 shadow-sm"
              >
                <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4">Tags</h3>
                <div class="flex flex-wrap gap-2">
                  <span
                    v-for="tag in currentProduct.tags"
                    :key="tag"
                    class="px-3 py-1 bg-orange-100 text-orange-600 rounded-full text-sm font-medium"
                  >
                    {{ tag }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="text-center py-20 text-gray-500">Product not found.</div>
      </main>
    </div>
  </div>
</template>
