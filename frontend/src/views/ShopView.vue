<template>
  <div class="main-layout flex flex-col lg:flex-row gap-6 lg:gap-10 items-start pb-[60px]">
    <button
      class="mobile-filter-btn lg:hidden flex items-center gap-2 bg-card-bg text-text px-4 py-3 rounded-xl text-sm font-semibold border border-border hover:border-primary transition-colors w-full justify-center"
      @click="showMobileFilters = !showMobileFilters"
    >
      <i class="fa-solid fa-sliders"></i>
      {{ showMobileFilters ? "Hide Filters" : "Show Filters" }}
    </button>

    <div
      :class="['sidebar-wrapper w-full lg:w-auto', showMobileFilters ? 'block' : 'hidden lg:block']"
    >
      <AppSidebar @apply-filters="handleApplyFilters" @reset-filters="handleResetFilters" />
    </div>

    <main class="content flex-1 min-w-0 w-full">
      <Breadcrumb :items="breadcrumbItems" />

      <div
        class="content-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5"
      >
        <h1 class="text-[24px] sm:text-[28px] font-extrabold text-text">Shop</h1>
        <div
          class="sort-dropdown flex items-center gap-2 text-sm text-text-light w-full sm:w-auto justify-between sm:justify-start"
        >
          <span>Sort by:</span>
          <select
            v-model="sortBy"
            class="border-none bg-transparent text-sm font-semibold text-text cursor-pointer outline-none p-2"
          >
            <option value="popular">Most Popular</option>
            <option value="newest">Newest</option>
            <option value="price-low">Price: Low to High</option>
            <option value="price-high">Price: High to Low</option>
          </select>
          <div
            class="filter-icon w-9 h-9 flex items-center justify-center bg-background rounded-lg cursor-pointer hover:bg-gray-200 transition-colors lg:hidden"
            @click="showMobileFilters = !showMobileFilters"
          >
            <i class="fa-solid fa-sliders text-text"></i>
          </div>
        </div>
      </div>

      <div
        v-if="activeFilters.length > 0"
        class="active-filters flex flex-wrap gap-2 sm:gap-2.5 mb-5 sm:mb-7"
      >
        <span
          v-for="filter in activeFilters"
          :key="filter.id"
          class="filter-tag flex items-center gap-2 bg-[#F2F0FD] text-primary px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-lg text-[12px] sm:text-[13px] font-semibold"
        >
          {{ filter.label }}
          <button
            class="hover:opacity-100 opacity-70 transition-opacity"
            @click="removeFilter(filter.id)"
          >
            <i class="fa-solid fa-xmark"></i>
          </button>
        </span>
      </div>

      <ProductGrid />

      <div
        v-if="meta.last_page > 1 && !isLoading"
        class="pagination flex items-center justify-center gap-1 sm:gap-2 flex-wrap mt-8"
      >
        <button
          class="page-btn px-3 sm:w-auto h-9 flex items-center justify-center rounded-lg text-xs sm:text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          :class="meta.current_page === 1 ? 'text-gray-300' : 'text-text-light hover:bg-gray-100'"
          :disabled="meta.current_page === 1"
          @click="changePage(meta.current_page - 1)"
        >
          <span class="hidden sm:inline">Previous</span>
          <i class="fa-solid fa-chevron-left sm:hidden"></i>
        </button>

        <template v-for="(page, index) in displayedPages" :key="index">
          <span
            v-if="page === '...'"
            class="page-dots px-1 sm:px-2 text-text-light hidden sm:inline"
          >
            ...
          </span>

          <button
            v-else
            class="page-btn w-8 sm:w-9 h-8 sm:h-9 flex items-center justify-center rounded-lg text-xs sm:text-sm font-medium transition-all"
            :class="[
              meta.current_page === page
                ? 'bg-primary text-white shadow-md' // Active Style
                : 'text-text-light hover:bg-gray-100', // Inactive Style
            ]"
            @click="changePage(page as number)"
          >
            {{ page }}
          </button>
        </template>

        <button
          class="page-btn px-3 sm:w-auto h-9 flex items-center justify-center rounded-lg text-xs sm:text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          :class="
            meta.current_page === meta.last_page
              ? 'text-gray-300'
              : 'text-text-light hover:bg-gray-100'
          "
          :disabled="meta.current_page === meta.last_page"
          @click="changePage(meta.current_page + 1)"
        >
          <span class="hidden sm:inline">Next</span>
          <i class="fa-solid fa-chevron-right sm:hidden"></i>
        </button>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import AppSidebar from "@/components/layout/AppSidebar.vue";
import Breadcrumb from "@/components/layout/Breadcrumb.vue";
import ProductGrid from "@/components/product/ProductGrid.vue";
import { useProductShopStore } from "@/stores/user/ProductShop";
import { storeToRefs } from "pinia";

const productStore = useProductShopStore();
const { meta, isLoading } = storeToRefs(productStore);
const route = useRoute();
const router = useRouter();

// State
const sortBy = ref("popular");
const showMobileFilters = ref(false);
const breadcrumbItems = [{ label: "Home", to: "/" }, { label: "Shop" }];
const activeFilters = ref([]);

// Methods

function handleApplyFilters(filters: any) {
  console.log("Applying filters:", filters);

  router.push({
    query: {
      ...route.query,
      ...filters,
      page: 1,
    },
  });

  showMobileFilters.value = false;
  window.scrollTo({ top: 0, behavior: "smooth" });
}

function handleResetFilters() {
  activeFilters.value = [];
  router.push({ path: "/shop" });
}

function removeFilter(id: number) {
  activeFilters.value = activeFilters.value.filter((f: any) => f.id !== id);
}

const fetchDataFromURL = async () => {
  const query = route.query;

  const params = {
    page: Number(query.page) || 1,
    per_page: 9,
    q: (query.q as string) || "",
    category_id: query.category_id,
    brand_id: query.brand_id,
    min_price: query.min_price,
    max_price: query.max_price,
    sort_by: query.sort_by || sortBy.value,
    color: query.color,
    size: query.size,
  };

  console.log("Params before cleaning:", params);

  const cleanParams: any = {};

  for (const [key, value] of Object.entries(params)) {
    if (value !== undefined && value !== null && value !== "") {
      cleanParams[key] = value;
    }
  }

  console.log("Params after cleaning:", cleanParams);

  await productStore.fetchProduct(cleanParams);
};

const changePage = (page: number) => {
  if (page < 1 || page > meta.value.last_page || page === meta.value.current_page) {
    return;
  }

  router.push({
    query: {
      ...route.query,
      page: page,
    },
  });

  window.scrollTo({ top: 0, behavior: "smooth" });
};

watch(sortBy, (newSort) => {
  router.push({
    query: {
      ...route.query,
      sort_by: newSort,
      page: 1,
    },
  });
});

const displayedPages = computed(() => {
  const total = meta.value.last_page;
  const current = meta.value.current_page;
  const delta = 1;
  const range = [];
  const rangeWithDots: (number | string)[] = [];
  let l;

  for (let i = 1; i <= total; i++) {
    if (i === 1 || i === total || (i >= current - delta && i <= current + delta)) {
      range.push(i);
    }
  }

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

watch(
  () => route.query,
  () => {
    fetchDataFromURL();

    if (route.query.sort_by) {
      sortBy.value = route.query.sort_by as string;
    }
  },
  { deep: true, immediate: true }
);
</script>
