<script setup lang="ts">
import { computed, onMounted, ref, watch } from "vue";
import AdminHeader from "@/components/admin/AdminHeader.vue";
import AdminNav from "@/components/admin/AdminNav.vue";
import { useAdminProductStore } from "@/stores/admin/AdminProductStore";
import { storeToRefs } from "pinia";
import { useRoute, useRouter } from "vue-router";

const sidebarOpen = ref(false);
const route = useRoute();
const router = useRouter();

const productStore = useAdminProductStore();
const { meta, isLoading } = storeToRefs(productStore);

onMounted(() => {
  const page = route.query.page ? Number(route.query.page) : 1;
  productStore.fetchAllProduct({ page });
});

watch(
  () => route.query.page,
  (newPage) => {
    const page = newPage ? Number(newPage) : 1;
    productStore.fetchAllProduct({ page });
  }
);

// --- Helpers ---
const formatSizes = (variants: any[]) => {
  if (!variants || variants.length === 0) return "No sizes";
  const sizes = variants.map((v) => v.size);
  const uniqueSizes = [...new Set(sizes)];
  return uniqueSizes.join(", ");
};

const calculateTotalStock = (variants: any[]) => {
  if (!variants || variants.length === 0) return 0;
  return variants.reduce((total, variant) => total + Number(variant.stock), 0);
};

const BASE_URL = "http://127.0.0.1:8000";
const getProductImage = (product: any) => {
  if (product.images && product.images.length > 0) {
    const primary = product.images.find((img: any) => img.is_primary);
    const imagePath = primary ? primary.image_url : product.images[0].image_url;
    return `${BASE_URL}/storage/${imagePath}`;
  }
  return "/images/placeholder.jpg";
};

// --- Pagination Logic

const displayedPages = computed(() => {
  const current = meta.value.current_page;
  const last = meta.value.last_page;
  const delta = 1;
  const range = [];
  const rangeWithDots: (number | string)[] = [];
  let l;

  range.push(1);
  for (let i = current - delta; i <= current + delta; i++) {
    if (i < last && i > 1) {
      range.push(i);
    }
  }
  range.push(last);

  for (let i of range) {
    if (l) {
      if (i - l === 2) {
        rangeWithDots.push(l + 1);
      } else if (i - l !== 1) {
        rangeWithDots.push("...");
      }
    }
    rangeWithDots.push(i);
    l = i;
  }
  return rangeWithDots;
});

const changePage = (page: number) => {
  if (page < 1 || page > meta.value.last_page || page === meta.value.current_page) {
    return;
  }
  router.push({
    query: { ...route.query, page: page },
  });
  window.scrollTo({ top: 0, behavior: "smooth" });
};

//delete product
const handleDelete = async (id: number) => {
  if (confirm("Are you sure?")) {
    await productStore.deleteProduct(id);
  }
};
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex">
    <AdminNav :isOpen="sidebarOpen" @close="sidebarOpen = false" />

    <div class="flex-1 lg:ml-64">
      <AdminHeader title="PRODUCT LIST" @toggle-sidebar="sidebarOpen = true">
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
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
          <h2 class="text-lg md:text-xl font-semibold text-slate-800">All Product List</h2>
          <div class="flex items-center gap-2 md:gap-3">
            <router-link
              to="/admin/products/create"
              class="px-3 md:px-4 py-2 bg-orange-500 text-white rounded-lg font-medium hover:bg-orange-600 transition-colors text-sm md:text-base"
            >
              <i class="fa-solid fa-plus mr-1 md:mr-2"></i>
              <span class="hidden sm:inline">Add</span> Product
            </router-link>
          </div>
        </div>

        <div v-if="productStore.isLoading" class="block md:hidden space-y-4">
          <div
            v-for="n in 5"
            :key="n"
            class="bg-white p-4 rounded-xl shadow-sm animate-pulse border border-gray-100"
          >
            <div class="flex items-center gap-4 mb-4">
              <div class="h-16 w-16 bg-gray-200 rounded-lg shrink-0"></div>
              <div class="flex-1 space-y-2">
                <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                <div class="h-3 bg-gray-200 rounded w-1/3"></div>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
              <div class="space-y-1">
                <div class="h-3 bg-gray-200 rounded w-10"></div>
                <div class="h-4 bg-gray-200 rounded w-16"></div>
              </div>
              <div class="space-y-1">
                <div class="h-3 bg-gray-200 rounded w-10"></div>
                <div class="h-4 bg-gray-200 rounded w-16"></div>
              </div>
            </div>
            <div class="flex items-center justify-between pt-4 border-t border-gray-50">
              <div class="h-6 w-20 bg-gray-200 rounded-full"></div>
              <div class="flex gap-2">
                <div class="h-8 w-8 bg-gray-200 rounded-lg"></div>
                <div class="h-8 w-8 bg-gray-200 rounded-lg"></div>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="md:hidden space-y-3">
          <div
            v-for="product in productStore.products"
            :key="product.id"
            class="bg-white rounded-xl shadow-sm p-4"
          >
            <div class="flex gap-3">
              <img
                :src="getProductImage(product)"
                :alt="product.name"
                class="w-20 h-20 rounded-xl object-cover flex-shrink-0"
              />
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                  <div>
                    <h3 class="font-semibold text-slate-800 text-sm">{{ product.name }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                      Size: {{ formatSizes(product.variants) }}
                    </p>
                  </div>
                  <span
                    class="px-2 py-1 bg-orange-100 text-orange-600 text-xs font-medium rounded-full"
                  >
                    {{ product.category?.name }}
                  </span>
                </div>
                <div class="flex items-center justify-between mt-2">
                  <span class="text-lg font-bold text-orange-500">
                    ${{ Number(product.final_price).toFixed(2) }}
                  </span>
                  <span v-if="product.discount > 0" class="text-sm text-gray-400 line-through">
                    ${{ Number(product.price).toFixed(2) }}
                  </span>
                  <div class="flex items-center gap-1 text-amber-500">
                    <i class="fa-solid fa-star text-xs"></i>
                    <span class="text-sm font-medium">4.5</span>
                  </div>
                </div>
                <div class="flex items-center justify-between mt-2 text-xs">
                  <div class="flex gap-3">
                    <span class="text-slate-600">
                      <span class="font-medium text-slate-800">
                        {{ calculateTotalStock(product.variants) }}
                      </span>
                      Left
                    </span>
                    <span class="text-green-600">
                      <span class="font-medium">{{ product.sales_count || 0 }}</span> Sold
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div class="flex items-center justify-end gap-2 mt-3 pt-3 border-t border-gray-100">
              <router-link
                :to="`/admin/products/${product.id}`"
                class="flex-1 py-2 px-3 rounded-lg border border-gray-200 flex items-center justify-center gap-2 text-slate-600 hover:bg-gray-50 text-sm"
              >
                <i class="fa-solid fa-eye"></i> View
              </router-link>

              <router-link
                class="flex-1 py-2 px-3 rounded-lg border border-blue-200 bg-blue-50 flex items-center justify-center gap-2 text-blue-600 hover:bg-blue-100 text-sm"
                :to="`/admin/products/edit/${product.id}`"
                ><i class="fa-solid fa-pen"></i> Edit</router-link
              >

              <button
                class="py-2 px-3 rounded-lg border border-red-200 bg-red-50 flex items-center justify-center text-red-500 hover:bg-red-100"
              >
                <i @click="handleDelete(product.id)" class="fa-solid fa-trash text-sm"></i>
              </button>
            </div>
          </div>

          <div
            v-if="meta.total > 0"
            class="bg-white rounded-xl shadow-sm p-4 flex flex-col items-center gap-3"
          >
            <p class="text-sm text-slate-500">
              Showing {{ (meta.current_page - 1) * meta.per_page + 1 }} to
              {{ Math.min(meta.current_page * meta.per_page, meta.total) }} of
              {{ meta.total }} entries
            </p>
            <div class="flex items-center gap-1">
              <button
                @click="changePage(meta.current_page - 1)"
                :disabled="meta.current_page === 1"
                class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100 disabled:opacity-50"
              >
                <i class="fa-solid fa-chevron-left text-xs"></i>
              </button>

              <template v-for="(page, index) in displayedPages" :key="index">
                <button
                  v-if="page !== '...'"
                  @click="changePage(Number(page))"
                  :class="[
                    'w-9 h-9 rounded-lg flex items-center justify-center font-medium transition-colors',
                    meta.current_page === page
                      ? 'bg-orange-500 text-white'
                      : 'border border-gray-200 text-slate-500 hover:bg-gray-100',
                  ]"
                >
                  {{ page }}
                </button>
                <span
                  v-else
                  class="w-9 h-9 flex items-center justify-center text-slate-400 font-bold"
                  >...</span
                >
              </template>

              <button
                @click="changePage(meta.current_page + 1)"
                :disabled="meta.current_page === meta.last_page"
                class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100 disabled:opacity-50"
              >
                <i class="fa-solid fa-chevron-right text-xs"></i>
              </button>
            </div>
          </div>
        </div>
        <div class="hidden md:block bg-white rounded-xl shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full min-w-[800px]">
              <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                  <th class="px-6 py-4 text-left">
                    <input
                      type="checkbox"
                      class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-500"
                    />
                  </th>
                  <th
                    class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider"
                  >
                    Product Name & Size
                  </th>
                  <th
                    class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider"
                  >
                    Price
                  </th>
                  <th
                    class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider"
                  >
                    Stock
                  </th>
                  <th
                    class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider"
                  >
                    Category
                  </th>
                  <th
                    class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider"
                  >
                    Rating
                  </th>
                  <th
                    class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider"
                  >
                    Action
                  </th>
                </tr>
              </thead>
              <tbody v-if="productStore.isLoading">
                <tr v-for="n in 5" :key="n" class="animate-pulse border-b border-gray-100">
                  <td class="p-4 pl-6"><div class="h-4 w-4 bg-gray-200 rounded"></div></td>
                  <td class="p-4">
                    <div class="flex items-center gap-4">
                      <div class="h-12 w-12 bg-gray-200 rounded-lg shrink-0"></div>
                      <div class="space-y-2">
                        <div class="h-4 bg-gray-200 rounded w-32"></div>
                        <div class="h-3 bg-gray-200 rounded w-20"></div>
                      </div>
                    </div>
                  </td>
                  <td class="p-4 text-center">
                    <div class="h-6 bg-gray-200 rounded-full w-24 mx-auto"></div>
                  </td>
                  <td class="p-4">
                    <div class="space-y-2">
                      <div class="h-4 bg-gray-200 rounded w-16"></div>
                      <div class="h-3 bg-gray-200 rounded w-12"></div>
                    </div>
                  </td>
                  <td class="p-4">
                    <div class="space-y-2">
                      <div class="h-4 bg-gray-200 rounded w-10"></div>
                      <div class="h-3 bg-gray-200 rounded w-14"></div>
                    </div>
                  </td>
                  <td class="p-4"><div class="h-6 bg-gray-200 rounded-full w-16"></div></td>
                  <td class="p-4">
                    <div class="flex items-center justify-end gap-2">
                      <div class="h-8 w-8 bg-gray-200 rounded-lg"></div>
                      <div class="h-8 w-8 bg-gray-200 rounded-lg"></div>
                    </div>
                  </td>
                </tr>
              </tbody>

              <tbody v-else class="divide-y divide-gray-100">
                <tr
                  v-for="product in productStore.products"
                  :key="product.id"
                  class="hover:bg-gray-50 transition-colors"
                >
                  <td class="px-6 py-4">
                    <input
                      type="checkbox"
                      class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-500"
                    />
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                      <img
                        :src="getProductImage(product)"
                        :alt="product.name"
                        class="w-12 h-12 rounded-lg object-cover"
                      />
                      <div>
                        <p class="font-medium text-slate-800">{{ product.name }}</p>
                        <p class="text-sm text-slate-500">
                          Size: {{ formatSizes(product.variants) }}
                        </p>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex flex-col">
                      <span class="font-medium text-slate-800">
                        ${{ Number(product.final_price).toFixed(2) }}
                      </span>
                      <span v-if="product.discount > 0" class="text-xs text-gray-400 line-through">
                        ${{ Number(product.price).toFixed(2) }}
                      </span>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <div>
                      <span class="text-slate-800"
                        >{{ calculateTotalStock(product.variants) }} Item</span
                      >
                      <span class="text-orange-500 text-sm"> Left</span>
                    </div>
                    <span class="text-green-500 text-sm">{{ product.sales_count || 0 }} Sold</span>
                  </td>
                  <td class="px-6 py-4 text-slate-600">{{ product.category?.name }}</td>
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                      <span class="flex items-center gap-1 text-amber-500">
                        <i class="fa-solid fa-star text-xs"></i> 4.5
                      </span>
                      <span class="text-slate-500 text-sm">55 Review</span>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                      <router-link
                        :to="`/admin/products/${product.id}`"
                        class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100 hover:text-slate-700"
                      >
                        <i class="fa-solid fa-eye text-sm"></i>
                      </router-link>
                      <router-link
                        :to="`/admin/products/edit/${product.id}`"
                        class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-blue-500 hover:bg-blue-50"
                      >
                        <i class="fa-solid fa-pen text-sm"></i>
                      </router-link>

                      <button
                        class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-red-500 hover:bg-red-50"
                      >
                        <i @click="handleDelete(product.id)" class="fa-solid fa-trash text-sm"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div
            v-if="meta.total > 0"
            class="px-4 md:px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-gray-100"
          >
            <p class="text-sm text-slate-500">
              Showing
              <span class="font-medium text-slate-900">{{
                (meta.current_page - 1) * meta.per_page + 1
              }}</span>
              to
              <span class="font-medium text-slate-900">{{
                Math.min(meta.current_page * meta.per_page, meta.total)
              }}</span>
              of
              <span class="font-medium text-slate-900">{{ meta.total }}</span>
              entries
            </p>

            <div class="flex items-center gap-1 md:gap-2">
              <button
                @click="changePage(meta.current_page - 1)"
                :disabled="meta.current_page === 1"
                :class="[
                  'w-8 h-8 rounded-lg border flex items-center justify-center transition-colors',
                  meta.current_page === 1
                    ? 'border-gray-100 text-gray-300 cursor-not-allowed'
                    : 'border-gray-200 text-slate-500 hover:bg-gray-100',
                ]"
              >
                <i class="fa-solid fa-chevron-left text-xs"></i>
              </button>

              <template v-for="(page, index) in displayedPages" :key="index">
                <button
                  v-if="page !== '...'"
                  @click="changePage(Number(page))"
                  :class="[
                    'w-8 h-8 rounded-lg flex items-center justify-center text-sm font-medium transition-all',
                    meta.current_page === page
                      ? 'bg-orange-500 text-white shadow-sm'
                      : 'border border-gray-200 text-slate-500 hover:bg-gray-100',
                  ]"
                >
                  {{ page }}
                </button>
                <span
                  v-else
                  class="w-8 h-8 flex items-center justify-center text-slate-400 font-bold pb-2"
                >
                  ...
                </span>
              </template>

              <button
                @click="changePage(meta.current_page + 1)"
                :disabled="meta.current_page === meta.last_page"
                :class="[
                  'w-8 h-8 rounded-lg border flex items-center justify-center transition-colors',
                  meta.current_page === meta.last_page
                    ? 'border-gray-100 text-gray-300 cursor-not-allowed'
                    : 'border-gray-200 text-slate-500 hover:bg-gray-100',
                ]"
              >
                <i class="fa-solid fa-chevron-right text-xs"></i>
              </button>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<style scoped>
/* ========================================
   BASE STYLES (Mobile First Approach)
   ======================================== */

.admin-products-container {
  min-height: 100vh;
}

/* Product Image */
.product-image {
  width: 48px;
  height: 48px;
  border-radius: 8px;
  object-fit: cover;
}

/* Action Buttons */
.action-btn {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}

/* ========================================
   MOBILE STYLES (max-width: 639px)
   ======================================== */
@media (max-width: 639px) {
  /* Header adjustments */
  .admin-header {
    padding: 0.5rem 1rem;
  }

  .admin-header h1 {
    font-size: 0.875rem;
    font-weight: 600;
  }

  /* Main content padding */
  .admin-main {
    padding: 1rem;
  }

  /* Content header - stack on mobile */
  .content-header {
    flex-direction: column;
    gap: 1rem;
  }

  .content-header h2 {
    font-size: 1rem;
  }

  /* Buttons group */
  .button-group {
    width: 100%;
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
  }

  .button-group .add-btn {
    flex: 1;
    min-width: 120px;
    padding: 0.625rem 1rem;
    font-size: 0.875rem;
  }

  .button-group select {
    flex: 1;
    min-width: 100px;
  }

  /* Table container - horizontal scroll */
  .table-container {
    margin: 0 -1rem;
    padding: 0;
    border-radius: 0;
  }

  .table-wrapper {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }

  /* Table cells */
  .products-table th,
  .products-table td {
    padding: 0.75rem 0.5rem;
    font-size: 0.75rem;
  }

  .products-table th:first-child,
  .products-table td:first-child {
    padding-left: 1rem;
  }

  .products-table th:last-child,
  .products-table td:last-child {
    padding-right: 1rem;
  }

  /* Product info cell */
  .product-info {
    min-width: 200px;
  }

  .product-image {
    width: 40px;
    height: 40px;
  }

  .product-name {
    font-size: 0.8125rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 150px;
  }

  .product-size {
    font-size: 0.6875rem;
  }

  /* Action buttons - smaller on mobile */
  .action-btn {
    width: 28px;
    height: 28px;
  }

  .action-btn i {
    font-size: 0.6875rem;
  }

  /* Pagination */
  .pagination-container {
    flex-direction: column;
    gap: 0.75rem;
    padding: 1rem;
  }

  .pagination-info {
    font-size: 0.75rem;
    text-align: center;
  }

  .pagination-buttons {
    gap: 0.25rem;
  }

  .page-btn {
    width: 28px;
    height: 28px;
    font-size: 0.75rem;
  }

  /* Hide some columns on mobile */
  .hide-mobile {
    display: none !important;
  }
}

/* ========================================
   TABLET STYLES (640px - 1023px)
   ======================================== */
@media (min-width: 640px) and (max-width: 1023px) {
  /* Header */
  .admin-header {
    padding: 0.75rem 1.5rem;
  }

  .admin-header h1 {
    font-size: 1rem;
  }

  /* Main content */
  .admin-main {
    padding: 1.5rem;
  }

  /* Content header */
  .content-header {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
  }

  .content-header h2 {
    font-size: 1.125rem;
  }

  /* Button group */
  .button-group {
    display: flex;
    gap: 0.75rem;
  }

  .button-group .add-btn {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
  }

  /* Table */
  .table-container {
    border-radius: 0.75rem;
    overflow: hidden;
  }

  .products-table th,
  .products-table td {
    padding: 1rem 0.75rem;
    font-size: 0.8125rem;
  }

  /* Product info */
  .product-image {
    width: 44px;
    height: 44px;
  }

  .product-name {
    font-size: 0.875rem;
    max-width: 180px;
  }

  /* Action buttons */
  .action-btn {
    width: 30px;
    height: 30px;
  }

  /* Pagination */
  .pagination-container {
    padding: 1rem 1.5rem;
    flex-direction: row;
    justify-content: space-between;
  }

  .page-btn {
    width: 32px;
    height: 32px;
  }

  /* Sidebar - fixed but narrower */
  .admin-sidebar {
    width: 240px;
  }

  .main-content {
    margin-left: 0;
  }
}

/* ========================================
   DESKTOP / PC STYLES (1024px and above)
   ======================================== */
@media (min-width: 1024px) {
  /* Header */
  .admin-header {
    padding: 0.75rem 1.5rem;
  }

  .admin-header h1 {
    font-size: 1.125rem;
  }

  /* Search input */
  .search-input {
    width: 200px;
    transition: width 0.3s ease;
  }

  .search-input:focus {
    width: 280px;
  }

  /* Main content - sidebar offset */
  .main-content {
    margin-left: 256px;
  }

  .admin-main {
    padding: 1.5rem;
  }

  /* Content header */
  .content-header h2 {
    font-size: 1.25rem;
  }

  /* Button group */
  .button-group {
    gap: 0.75rem;
  }

  .button-group .add-btn {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
  }

  /* Table */
  .table-container {
    border-radius: 0.75rem;
  }

  .products-table {
    min-width: 800px;
  }

  .products-table th,
  .products-table td {
    padding: 1rem 1.5rem;
  }

  /* Product info */
  .product-image {
    width: 48px;
    height: 48px;
  }

  .product-name {
    font-size: 0.9375rem;
    max-width: none;
  }

  /* Action buttons with hover effects */
  .action-btn {
    width: 32px;
    height: 32px;
  }

  .action-btn:hover {
    transform: scale(1.05);
  }

  /* Pagination */
  .pagination-container {
    padding: 1rem 1.5rem;
  }

  .page-btn {
    width: 32px;
    height: 32px;
  }

  .page-btn:hover:not(.active) {
    background-color: #f3f4f6;
  }
}

/* ========================================
   LARGE DESKTOP STYLES (1280px and above)
   ======================================== */
@media (min-width: 1280px) {
  .admin-main {
    padding: 2rem;
  }

  .search-input {
    width: 240px;
  }

  .search-input:focus {
    width: 320px;
  }

  .products-table th,
  .products-table td {
    padding: 1.25rem 1.5rem;
  }

  .content-header h2 {
    font-size: 1.375rem;
  }
}

/* ========================================
   EXTRA LARGE DESKTOP (1536px and above)
   ======================================== */
@media (min-width: 1536px) {
  .admin-main {
    padding: 2rem 2.5rem;
  }

  .products-table th,
  .products-table td {
    padding: 1.25rem 2rem;
  }
}

/* ========================================
   UTILITY CLASSES
   ======================================== */

/* Smooth transitions */
.transition-all {
  transition: all 0.2s ease-in-out;
}

/* Custom scrollbar for table */
.table-wrapper::-webkit-scrollbar {
  height: 6px;
}

.table-wrapper::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 3px;
}

.table-wrapper::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 3px;
}

.table-wrapper::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* Focus states for accessibility */
.action-btn:focus,
.page-btn:focus,
.add-btn:focus {
  outline: 2px solid #f97316;
  outline-offset: 2px;
}

/* Animations */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.table-container {
  animation: fadeIn 0.3s ease-out;
}

/* Print styles */
@media print {
  .admin-sidebar,
  .mobile-menu-btn,
  .pagination-container,
  .action-btn {
    display: none !important;
  }

  .main-content {
    margin-left: 0 !important;
  }

  .products-table {
    font-size: 10pt;
  }
}
</style>
