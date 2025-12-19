<script setup>
import { ref } from 'vue'
import AppSidebar from '@/components/layout/AppSidebar.vue'
import Breadcrumb from '@/components/layout/Breadcrumb.vue'
import ProductGrid from '@/components/product/ProductGrid.vue'
import { getProductsList } from '@/data/products'

const products = ref(getProductsList())
const sortBy = ref('popular')
const showMobileFilters = ref(false)

const breadcrumbItems = [
  { label: 'Category', to: '#' },
  { label: 'Sweatshirt' }
]

const activeFilters = ref([
  { id: 1, label: 'Oversize' },
  { id: 2, label: 'Multicolour' },
  { id: 3, label: 'XXL' }
])

function removeFilter(id) {
  activeFilters.value = activeFilters.value.filter(f => f.id !== id)
}

function handleApplyFilters(filters) {
  console.log('Applying filters:', filters)
  showMobileFilters.value = false
}

function handleResetFilters() {
  activeFilters.value = []
}
</script>

<template>
  <div class="main-layout flex flex-col lg:flex-row gap-6 lg:gap-10 items-start pb-[60px]">
    <!-- Mobile Filter Toggle -->
    <button 
      class="mobile-filter-btn lg:hidden flex items-center gap-2 bg-card-bg text-text px-4 py-3 rounded-xl text-sm font-semibold border border-border hover:border-primary transition-colors w-full justify-center"
      @click="showMobileFilters = !showMobileFilters"
    >
      <i class="fa-solid fa-sliders"></i>
      {{ showMobileFilters ? 'Hide Filters' : 'Show Filters' }}
    </button>

    <!-- Sidebar (hidden on mobile, visible on desktop) -->
    <div :class="['sidebar-wrapper w-full lg:w-auto', showMobileFilters ? 'block' : 'hidden lg:block']">
      <AppSidebar 
        @apply-filters="handleApplyFilters"
        @reset-filters="handleResetFilters"
      />
    </div>

    <!-- Content -->
    <main class="content flex-1 min-w-0 w-full">
      <Breadcrumb :items="breadcrumbItems" />

      <div class="content-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5">
        <h1 class="text-[24px] sm:text-[28px] font-extrabold text-text">Sweatshirt</h1>
        <div class="sort-dropdown flex items-center gap-2 text-sm text-text-light w-full sm:w-auto justify-between sm:justify-start">
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
          <div class="filter-icon w-9 h-9 flex items-center justify-center bg-background rounded-lg cursor-pointer hover:bg-gray-200 transition-colors lg:hidden" @click="showMobileFilters = !showMobileFilters">
            <i class="fa-solid fa-sliders text-text"></i>
          </div>
        </div>
      </div>

      <div class="active-filters flex flex-wrap gap-2 sm:gap-2.5 mb-5 sm:mb-7">
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

      <ProductGrid :products="products" />

      <div class="pagination flex items-center justify-center gap-1 sm:gap-2 flex-wrap">
        <button class="page-btn px-3 sm:w-auto h-9 flex items-center justify-center rounded-lg text-xs sm:text-sm font-medium text-text-light hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
          <span class="hidden sm:inline">Previous</span>
          <i class="fa-solid fa-chevron-left sm:hidden"></i>
        </button>
        <button class="page-btn w-8 sm:w-9 h-8 sm:h-9 flex items-center justify-center rounded-lg text-xs sm:text-sm font-medium text-text-light hover:bg-gray-100">1</button>
        <button class="page-btn w-8 sm:w-9 h-8 sm:h-9 flex items-center justify-center rounded-lg text-xs sm:text-sm font-medium text-text-light hover:bg-gray-100">2</button>
        <button class="page-btn active w-8 sm:w-9 h-8 sm:h-9 flex items-center justify-center rounded-lg text-xs sm:text-sm font-medium bg-primary text-white shadow-md">3</button>
        <button class="page-btn w-8 sm:w-9 h-8 sm:h-9 flex items-center justify-center rounded-lg text-xs sm:text-sm font-medium text-text-light hover:bg-gray-100">4</button>
        <button class="page-btn w-8 sm:w-9 h-8 sm:h-9 flex items-center justify-center rounded-lg text-xs sm:text-sm font-medium text-text-light hover:bg-gray-100 hidden sm:flex">5</button>
        <span class="page-dots px-1 sm:px-2 text-text-light hidden sm:inline">...</span>
        <button class="page-btn w-8 sm:w-9 h-8 sm:h-9 flex items-center justify-center rounded-lg text-xs sm:text-sm font-medium text-text-light hover:bg-gray-100 hidden sm:flex">20</button>
        <button class="page-btn px-3 sm:w-auto h-9 flex items-center justify-center rounded-lg text-xs sm:text-sm font-medium text-text-light hover:bg-gray-100">
          <span class="hidden sm:inline">Next</span>
          <i class="fa-solid fa-chevron-right sm:hidden"></i>
        </button>
      </div>
    </main>
  </div>
</template>
