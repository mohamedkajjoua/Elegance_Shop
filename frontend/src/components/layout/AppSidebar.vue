<script setup>
import { ref, computed } from 'vue'
import { categories, types, colors, sizes } from '@/data/products'

const emit = defineEmits(['applyFilters', 'resetFilters'])

const selectedCategory = ref('sweatshirt')
const selectedTypes = ref(['Oversize'])
const selectedColors = ref(['Multicolour'])
const selectedSize = ref('XXL')
const priceRange = ref({ min: 0, max: 500 })
const priceSlider = ref(250)

function toggleType(typeName) {
  const index = selectedTypes.value.indexOf(typeName)
  if (index > -1) {
    selectedTypes.value.splice(index, 1)
  } else {
    selectedTypes.value.push(typeName)
  }
}

function toggleColor(colorName) {
  const index = selectedColors.value.indexOf(colorName)
  if (index > -1) {
    selectedColors.value.splice(index, 1)
  } else {
    selectedColors.value.push(colorName)
  }
}

function selectSize(size) {
  selectedSize.value = size
}

function applyFilters() {
  emit('applyFilters', {
    category: selectedCategory.value,
    types: selectedTypes.value,
    colors: selectedColors.value,
    size: selectedSize.value,
    price: priceRange.value
  })
}

function resetFilters() {
  selectedCategory.value = 'sweatshirt'
  selectedTypes.value = []
  selectedColors.value = []
  selectedSize.value = ''
  priceRange.value = { min: 0, max: 500 }
  emit('resetFilters')
}
</script>

<template>
  <aside class="sidebar w-[250px] shrink-0 sticky top-5">
    <!-- Category -->
    <div class="sidebar-section mb-7">
      <h3 class="sidebar-title text-sm font-bold mb-4 text-text flex items-center gap-2">Category</h3>
      <ul class="category-list space-y-3">
        <li v-for="category in categories" :key="category.id">
          <a 
            href="#"
            :class="[
              'category-item flex justify-between items-center text-sm transition-colors',
              category.active ? 'text-primary font-semibold' : 'text-text-light hover:text-primary hover:font-semibold'
            ]"
            @click.prevent="selectedCategory = category.slug"
          >
            {{ category.name }}
          </a>
        </li>
      </ul>
    </div>

    <div class="sidebar-section mb-7">
      <p class="filter-title font-bold text-sm">Filter by :</p>
    </div>

    <!-- Type Filter -->
    <div class="sidebar-section mb-7">
      <div class="collapse-header flex justify-between items-center mb-3 cursor-pointer">
        <h4 class="text-sm font-bold">Tipe</h4>
        <i class="fa-solid fa-chevron-up text-xs text-text-light"></i>
      </div>
      <div class="checkbox-list flex flex-col gap-3">
        <label 
          v-for="type in types" 
          :key="type.id"
          :class="[
            'checkbox-item flex items-center gap-3 text-sm cursor-pointer group',
            selectedTypes.includes(type.name) ? 'text-primary font-semibold' : 'text-text-light hover:text-text'
          ]"
        >
          <input 
            type="checkbox" 
            class="w-[18px] h-[18px] accent-primary cursor-pointer"
            :checked="selectedTypes.includes(type.name)"
            @change="toggleType(type.name)"
          >
          <span :class="{ 'group-hover:text-text transition-colors': !selectedTypes.includes(type.name) }">
            {{ type.name }}
          </span>
        </label>
      </div>
    </div>

    <!-- Color Filter -->
    <div class="sidebar-section mb-7">
      <div class="collapse-header flex justify-between items-center mb-3 cursor-pointer">
        <h4 class="text-sm font-bold">Colour</h4>
        <i class="fa-solid fa-chevron-up text-xs text-text-light"></i>
      </div>
      <div class="checkbox-list flex flex-col gap-3">
        <label 
          v-for="color in colors" 
          :key="color.id"
          :class="[
            'checkbox-item flex items-center gap-3 text-sm cursor-pointer group',
            selectedColors.includes(color.name) ? 'text-primary font-semibold' : 'text-text-light hover:text-text'
          ]"
        >
          <input 
            type="checkbox" 
            class="w-[18px] h-[18px] accent-primary cursor-pointer"
            :checked="selectedColors.includes(color.name)"
            @change="toggleColor(color.name)"
          >
          <span class="color-label flex items-center gap-2">
            <span 
              class="color-dot w-4 h-4 rounded"
              :style="{ background: color.hex }"
            ></span>
            {{ color.name }}
          </span>
        </label>
      </div>
    </div>

    <!-- Size Filter -->
    <div class="sidebar-section mb-7">
      <div class="collapse-header flex justify-between items-center mb-3 cursor-pointer">
        <h4 class="text-sm font-bold">Size</h4>
        <i class="fa-solid fa-chevron-up text-xs text-text-light"></i>
      </div>
      <div class="size-filter flex flex-wrap gap-2">
        <button 
          v-for="size in sizes" 
          :key="size"
          :class="[
            'size-btn min-w-[40px] h-9 px-3 border rounded-lg text-[13px] font-medium transition-colors',
            selectedSize === size 
              ? 'border-primary bg-primary text-white' 
              : 'border-border bg-white text-text-light hover:border-primary hover:text-primary'
          ]"
          @click="selectSize(size)"
        >
          {{ size }}
        </button>
      </div>
    </div>

    <!-- Price Filter -->
    <div class="sidebar-section mb-7">
      <div class="collapse-header flex justify-between items-center mb-3 cursor-pointer">
        <h4 class="text-sm font-bold">Price</h4>
        <i class="fa-solid fa-chevron-up text-xs text-text-light"></i>
      </div>
      <div class="price-inputs flex items-center gap-3 mb-4">
        <div class="price-input flex items-center gap-1 border border-border rounded-lg px-3 py-2 bg-white flex-1">
          <span class="text-text-light text-sm">$</span>
          <input 
            v-model.number="priceRange.min"
            type="number" 
            class="w-full border-none outline-none text-sm font-semibold text-text"
          >
        </div>
        <span class="text-text-light">-</span>
        <div class="price-input flex items-center gap-1 border border-border rounded-lg px-3 py-2 bg-white flex-1">
          <span class="text-text-light text-sm">$</span>
          <input 
            v-model.number="priceRange.max"
            type="number" 
            class="w-full border-none outline-none text-sm font-semibold text-text"
          >
        </div>
      </div>
      <input 
        v-model="priceSlider"
        type="range"
        class="price-slider w-full h-1 bg-border rounded-sm appearance-none cursor-pointer" 
        min="0" 
        max="500"
      >
    </div>

    <!-- Sidebar Actions -->
    <div class="sidebar-actions flex gap-3 mt-8">
      <button 
        class="btn-apply flex-1 bg-primary text-white border-none py-3.5 px-6 rounded-xl font-semibold text-sm cursor-pointer hover:bg-primary-dark transition-colors"
        @click="applyFilters"
      >
        Apply
      </button>
      <button 
        class="btn-reset w-12 h-12 bg-white border border-border rounded-xl flex items-center justify-center cursor-pointer text-text-light hover:border-danger hover:text-danger transition-all"
        @click="resetFilters"
      >
        <i class="fa-solid fa-trash"></i>
      </button>
    </div>
  </aside>
</template>
