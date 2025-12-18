<script setup>
import { ref } from 'vue'
import AppSidebar from '@/components/layout/AppSidebar.vue'
import Breadcrumb from '@/components/layout/Breadcrumb.vue'
import ProductGrid from '@/components/product/ProductGrid.vue'
import { getProductsList } from '@/data/products'

const products = ref(getProductsList())
const sortBy = ref('popular')

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
  // In a real app, this would filter the products
}

function handleResetFilters() {
  activeFilters.value = []
}
</script>

<template>
  <div class="main-layout flex gap-10 items-start pb-[60px]">
    <!-- Sidebar -->
    <AppSidebar 
      @apply-filters="handleApplyFilters"
      @reset-filters="handleResetFilters"
    />

    <!-- Content -->
    <main class="content flex-1 min-w-0">
      <Breadcrumb :items="breadcrumbItems" />

      <div class="content-header flex justify-between items-center mb-5">
        <h1 class="text-[28px] font-extrabold text-text">Sweatshirt</h1>
        <div class="sort-dropdown flex items-center gap-2 text-sm text-text-light">
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
          <div class="filter-icon w-9 h-9 flex items-center justify-center bg-background rounded-lg cursor-pointer hover:bg-gray-200 transition-colors">
            <i class="fa-solid fa-sliders text-text"></i>
          </div>
        </div>
      </div>

      <div class="active-filters flex flex-wrap gap-2.5 mb-7">
        <span 
          v-for="filter in activeFilters"
          :key="filter.id"
          class="filter-tag flex items-center gap-2 bg-[#F2F0FD] text-primary px-3.5 py-2 rounded-lg text-[13px] font-semibold"
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

      <div class="pagination flex items-center justify-center gap-2">
        <button class="page-btn w-9 h-9 flex items-center justify-center rounded-lg text-sm font-medium text-text-light hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
          Previous
        </button>
        <button class="page-btn w-9 h-9 flex items-center justify-center rounded-lg text-sm font-medium text-text-light hover:bg-gray-100">1</button>
        <button class="page-btn w-9 h-9 flex items-center justify-center rounded-lg text-sm font-medium text-text-light hover:bg-gray-100">2</button>
        <button class="page-btn active w-9 h-9 flex items-center justify-center rounded-lg text-sm font-medium bg-primary text-white shadow-md">3</button>
        <button class="page-btn w-9 h-9 flex items-center justify-center rounded-lg text-sm font-medium text-text-light hover:bg-gray-100">4</button>
        <button class="page-btn w-9 h-9 flex items-center justify-center rounded-lg text-sm font-medium text-text-light hover:bg-gray-100">5</button>
        <span class="page-dots px-2 text-text-light">...</span>
        <button class="page-btn w-9 h-9 flex items-center justify-center rounded-lg text-sm font-medium text-text-light hover:bg-gray-100">20</button>
        <button class="page-btn w-9 h-9 flex items-center justify-center rounded-lg text-sm font-medium text-text-light hover:bg-gray-100">Next</button>
      </div>
    </main>
  </div>
</template>
