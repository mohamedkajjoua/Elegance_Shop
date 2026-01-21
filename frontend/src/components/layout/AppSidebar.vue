<script setup>
import { ref, onMounted, watch, reactive } from "vue";
import { useProductShopStore } from "@/stores/user/ProductShop";
import { useRoute } from "vue-router";

const emit = defineEmits(["applyFilters", "resetFilters"]);

const productStore = useProductShopStore();
const route = useRoute();

// State (Reactive Variables)
const selectedCategory = ref(null);
const selectedTypes = ref([]);
const selectedColors = ref([]);
const selectedSize = ref("");
const priceRange = ref({ min: 0, max: 500 });

// ✅ Add local loading state to control the Skeleton
const isLoading = ref(true);

// 1. Fetch Data on Mount
onMounted(async () => {
  // ✅ Fix 1: Sync URL first so checkboxes are checked immediately when data arrives
  syncFiltersWithURL();

  if (productStore.categories.length === 0) {
    isLoading.value = true;
    try {
      await productStore.fetchFilterAttributes();
    } finally {
      // ✅ Stop loading even if error occurs
      isLoading.value = false;
    }
  } else {
    // Data already exists, no loading needed
    isLoading.value = false;
  }
});

function syncFiltersWithURL() {
  const q = route.query;
  if (q.category_id) selectedCategory.value = Number(q.category_id);
  if (q.brand_id) selectedTypes.value = [Number(q.brand_id)];
  if (q.color) selectedColors.value = [q.color];
  if (q.size) selectedSize.value = q.size;
  if (q.min_price) priceRange.value.min = Number(q.min_price);
  if (q.max_price) priceRange.value.max = Number(q.max_price);
}

// Methods
function toggleType(typeId) {
  if (selectedTypes.value.includes(typeId)) {
    selectedTypes.value = [];
  } else {
    selectedTypes.value = [typeId];
  }

  applyFilters();
}

function toggleColor(colorName) {
  const index = selectedColors.value.indexOf(colorName);
  if (index > -1) {
    selectedColors.value.splice(index, 1);
  } else {
    selectedColors.value = [colorName];
  }
  applyFilters();
}

function selectSize(size) {
  selectedSize.value = selectedSize.value === size ? "" : size;
  applyFilters();
}

function applyFilters() {
  emit("applyFilters", {
    category_id: selectedCategory.value,
    brand_id: selectedTypes.value[0],
    color: selectedColors.value[0],
    size: selectedSize.value,
    min_price: priceRange.value.min,
    max_price: priceRange.value.max,
  });
}

function resetFilters() {
  selectedCategory.value = null;
  selectedTypes.value = [];
  selectedColors.value = [];
  selectedSize.value = "";
  priceRange.value = { min: 0, max: 500 };
  emit("resetFilters");
}
</script>

<template>
  <aside
    class="sidebar w-full lg:w-[250px] shrink-0 lg:sticky lg:top-5 bg-card-bg lg:bg-transparent p-4 lg:p-0 rounded-xl lg:rounded-none"
  >
    <div class="sidebar-section mb-7">
      <p class="filter-title font-bold text-sm">Filter by :</p>
    </div>
    <div v-if="isLoading" class="animate-pulse space-y-8">
      <div>
        <div class="h-4 bg-gray-200 rounded w-1/3 mb-4"></div>
        <div class="space-y-3">
          <div v-for="n in 5" :key="n" class="h-3 bg-gray-200 rounded w-3/4"></div>
        </div>
      </div>
      <div>
        <div class="h-4 bg-gray-200 rounded w-1/4 mb-4"></div>
        <div class="space-y-3">
          <div v-for="n in 4" :key="n" class="flex gap-2">
            <div class="h-4 w-4 bg-gray-200 rounded"></div>
            <div class="h-3 bg-gray-200 rounded w-1/2"></div>
          </div>
        </div>
      </div>
      <div>
        <div class="h-4 bg-gray-200 rounded w-1/4 mb-4"></div>
        <div class="flex gap-2">
          <div v-for="n in 4" :key="n" class="h-9 w-10 bg-gray-200 rounded-lg"></div>
        </div>
      </div>
    </div>

    <div v-else>
      <div class="sidebar-section mb-7">
        <h3 class="sidebar-title text-sm font-bold mb-4 text-text flex items-center gap-2">
          Category
        </h3>
        <ul class="category-list space-y-3">
          <li v-for="category in productStore.categories" :key="category.id">
            <a
              href="#"
              :class="[
                'category-item flex justify-between items-center text-sm transition-colors',
                selectedCategory === category.id
                  ? 'text-primary font-semibold'
                  : 'text-text-light hover:text-primary hover:font-semibold',
              ]"
              @click.prevent="selectedCategory = category.id"
            >
              {{ category.name }}
            </a>
          </li>
        </ul>
      </div>

      <div class="sidebar-section mb-7">
        <div class="collapse-header flex justify-between items-center mb-3 cursor-pointer">
          <h4 class="text-sm font-bold">Brands</h4>
          <i class="fa-solid fa-chevron-up text-xs text-text-light"></i>
        </div>
        <div class="checkbox-list flex flex-col gap-3 max-h-48 overflow-y-auto custom-scrollbar">
          <label
            v-for="type in productStore.brands"
            :key="type.id"
            :class="[
              'checkbox-item flex items-center gap-3 text-sm cursor-pointer group',
              selectedTypes.includes(type.id)
                ? 'text-primary font-semibold'
                : 'text-text-light hover:text-text',
            ]"
          >
            <input
              type="checkbox"
              class="w-[18px] h-[18px] accent-primary cursor-pointer"
              :checked="selectedTypes.includes(type.id)"
              @change="toggleType(type.id)"
            />
            <span
              :class="{
                'group-hover:text-text transition-colors': !selectedTypes.includes(type.id),
              }"
            >
              {{ type.name }}
            </span>
          </label>
        </div>
      </div>

      <div class="sidebar-section mb-7">
        <div class="collapse-header flex justify-between items-center mb-3 cursor-pointer">
          <h4 class="text-sm font-bold">Colors</h4>
          <i class="fa-solid fa-chevron-up text-xs text-text-light"></i>
        </div>
        <div class="checkbox-list flex flex-col gap-3">
          <label
            v-for="color in productStore.colors"
            :key="color"
            :class="[
              'checkbox-item flex items-center gap-3 text-sm cursor-pointer group',
              selectedColors.includes(color)
                ? 'text-primary font-semibold'
                : 'text-text-light hover:text-text',
            ]"
          >
            <input
              type="checkbox"
              class="w-[18px] h-[18px] accent-primary cursor-pointer"
              :checked="selectedColors.includes(color)"
              @change="toggleColor(color)"
            />
            <span class="color-label flex items-center gap-2">
              <span
                class="color-dot w-4 h-4 rounded border border-gray-200"
                :style="{ background: color.toLowerCase() }"
              ></span>
              {{ color }}
            </span>
          </label>
        </div>
      </div>

      <div class="sidebar-section mb-7">
        <div class="collapse-header flex justify-between items-center mb-3 cursor-pointer">
          <h4 class="text-sm font-bold">Size</h4>
          <i class="fa-solid fa-chevron-up text-xs text-text-light"></i>
        </div>
        <div class="size-filter flex flex-wrap gap-2">
          <button
            v-for="size in productStore.sizes"
            :key="size"
            :class="[
              'size-btn min-w-[40px] h-9 px-3 border rounded-lg text-[13px] font-medium transition-colors',
              selectedSize === size
                ? 'border-primary bg-primary text-white'
                : 'border-border bg-white text-text-light hover:border-primary hover:text-primary',
            ]"
            @click="selectSize(size)"
          >
            {{ size }}
          </button>
        </div>
      </div>

      <div class="sidebar-section mb-7">
        <div class="collapse-header flex justify-between items-center mb-3 cursor-pointer">
          <h4 class="text-sm font-bold">Price</h4>
          <i class="fa-solid fa-chevron-up text-xs text-text-light"></i>
        </div>

        <div class="flex justify-between items-center mb-3">
          <span class="text-sm font-semibold text-primary">${{ priceRange.min }}</span>
          <span class="text-xs text-text-light">to</span>
          <span class="text-sm font-semibold text-primary">${{ priceRange.max }}</span>
        </div>

        <div class="price-slider-container relative h-2 mb-4">
          <div class="absolute inset-0 bg-gray-200 rounded-full"></div>

          <div
            class="absolute h-full bg-primary rounded-full"
            :style="{
              left: (priceRange.min / 500) * 100 + '%',
              width: ((priceRange.max - priceRange.min) / 500) * 100 + '%',
            }"
          ></div>

          <input
            v-model.number="priceRange.min"
            type="range"
            class="price-range-input absolute w-full h-full appearance-none bg-transparent cursor-pointer z-10"
            min="0"
            max="500"
            step="10"
            @input="priceRange.min = Math.min(priceRange.min, priceRange.max - 10)"
          />

          <input
            v-model.number="priceRange.max"
            type="range"
            class="price-range-input absolute w-full h-full appearance-none bg-transparent cursor-pointer z-20"
            min="0"
            max="500"
            step="10"
            @input="priceRange.max = Math.max(priceRange.max, priceRange.min + 10)"
          />
        </div>

        <div class="price-inputs flex items-center gap-3">
          <div
            class="price-input flex items-center gap-1 border border-border rounded-lg px-3 py-2 bg-white flex-1"
          >
            <span class="text-text-light text-sm">$</span>
            <input
              v-model.number="priceRange.min"
              type="number"
              min="0"
              :max="priceRange.max - 10"
              class="w-full border-none outline-none text-sm font-semibold text-text"
              @change="priceRange.min = Math.max(0, Math.min(priceRange.min, priceRange.max - 10))"
            />
          </div>
          <span class="text-text-light">-</span>
          <div
            class="price-input flex items-center gap-1 border border-border rounded-lg px-3 py-2 bg-white flex-1"
          >
            <span class="text-text-light text-sm">$</span>
            <input
              v-model.number="priceRange.max"
              type="number"
              :min="priceRange.min + 10"
              max="500"
              class="w-full border-none outline-none text-sm font-semibold text-text"
              @change="
                priceRange.max = Math.min(500, Math.max(priceRange.max, priceRange.min + 10))
              "
            />
          </div>
        </div>
      </div>

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
    </div>
  </aside>
</template>

<style scoped>
/* Optional: Scrollbar styling for lists */
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: #f1f1f1;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 4px;
}
</style>
