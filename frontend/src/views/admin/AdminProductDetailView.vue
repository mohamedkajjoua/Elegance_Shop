<script setup>
import { ref, computed } from 'vue'

// Toggle state for submenu
const productsOpen = ref(true)
// Mobile sidebar toggle
const sidebarOpen = ref(false)

// Sample product data with all fields
const product = ref({
  id: 1,
  name: 'Black T-shirt',
  short_description: 'Premium quality black t-shirt made from organic cotton',
  description: 'A premium quality black t-shirt made from 100% organic cotton. Perfect for casual wear and everyday comfort. Features a classic crew neck design with reinforced stitching for durability. Machine washable and fade-resistant.',
  price: 80.00,
  discount: 15,
  shipping: 5.00,
  free_shipping: false,
  sku: 'TSH-BLK-001',
  category: 'Fashion',
  brand: 'Nike',
  stock: 486,
  sold: 155,
  rating: 4.5,
  reviews: 55,
  is_active: true,
  is_featured: true,
  sizes: ['S', 'M', 'L', 'XL'],
  colors: [
    { name: 'Black', hex: '#000000' },
    { name: 'White', hex: '#FFFFFF' },
    { name: 'Blue', hex: '#3B82F6' }
  ],
  images: [
    'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=500',
    'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?w=500',
    'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500'
  ],
  tags: ['Fashion', 'New Arrival', 'Trending'],
  createdAt: '2024-01-15',
  updatedAt: '2024-03-20'
})

const selectedImage = ref(0)

// Computed final price
const finalPrice = computed(() => {
  if (!product.value.price) return 0
  const discountAmount = (product.value.price * (product.value.discount || 0)) / 100
  return product.value.price - discountAmount
})
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex">
    <!-- Mobile Overlay -->
    <div v-if="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>
    
    <!-- Sidebar -->
    <aside :class="[
      'bg-slate-800 text-white flex flex-col fixed h-full z-50 transition-transform duration-300',
      'w-64 lg:translate-x-0',
      sidebarOpen ? 'translate-x-0' : '-translate-x-full'
    ]">
      <!-- Logo -->
      <div class="p-5 flex items-center gap-3 border-b border-slate-700">
        <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
          <i class="fa-solid fa-bag-shopping text-white"></i>
        </div>
        <span class="text-xl font-bold">LapakBaju</span>
        <button @click="sidebarOpen = false" class="ml-auto text-slate-400 hover:text-white lg:hidden">
          <i class="fa-solid fa-times"></i>
        </button>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 overflow-y-auto py-4">
        <p class="px-5 text-xs text-slate-500 uppercase tracking-wider mb-3">General</p>
        
        <router-link to="/admin" class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
          <i class="fa-solid fa-grid-2 w-5"></i>
          <span>Dashboard</span>
        </router-link>

        <!-- Products - Active with Toggle -->
        <div class="relative">
          <button @click="productsOpen = !productsOpen" class="w-full flex items-center gap-3 px-5 py-3 bg-orange-500/10 text-orange-500 border-l-4 border-orange-500">
            <i class="fa-solid fa-cube w-5"></i>
            <span>Products</span>
            <i :class="['fa-solid ml-auto text-xs transition-transform', productsOpen ? 'fa-chevron-up' : 'fa-chevron-down']"></i>
          </button>
          <div v-show="productsOpen" class="bg-slate-900/50 py-2">
            <router-link to="/admin/products" class="block px-12 py-2 text-sm text-orange-500 font-medium">Product List</router-link>
            <router-link to="/admin/products/create" class="block px-12 py-2 text-sm text-slate-300 hover:text-orange-500">Create Product</router-link>
          </div>
        </div>

        <!-- Category -->
        <router-link to="/admin/category" class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
          <i class="fa-solid fa-layer-group w-5"></i>
          <span>Category</span>
        </router-link>

        <!-- Brand -->
        <router-link to="/admin/brand" class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
          <i class="fa-solid fa-tag w-5"></i>
          <span>Brand</span>
        </router-link>

        <router-link to="/admin/orders" class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
          <i class="fa-solid fa-cart-shopping w-5"></i>
          <span>Orders</span>
        </router-link>

        <a href="#" class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
          <i class="fa-solid fa-gear w-5"></i>
          <span>Settings</span>
        </a>

        <p class="px-5 text-xs text-slate-500 uppercase tracking-wider mt-6 mb-3">Users</p>
        
        <router-link to="/admin/users" class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
          <i class="fa-solid fa-users w-5"></i>
          <span>User List</span>
        </router-link>
        
        <router-link to="/admin/profile" class="flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
          <i class="fa-solid fa-user w-5"></i>
          <span>Profile</span>
        </router-link>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64">
      <!-- Header -->
      <header class="bg-white h-16 flex items-center justify-between px-4 md:px-6 sticky top-0 z-10 shadow-sm">
        <button @click="sidebarOpen = true" class="lg:hidden w-10 h-10 rounded-full flex items-center justify-center text-slate-500 hover:bg-gray-100">
          <i class="fa-solid fa-bars"></i>
        </button>
        <h1 class="text-base md:text-lg font-bold text-slate-800">PRODUCT DETAIL</h1>
        
        <div class="flex items-center gap-2 md:gap-4">
          <button class="w-10 h-10 rounded-full flex items-center justify-center text-slate-500 hover:bg-gray-100 hidden md:flex">
            <i class="fa-solid fa-moon"></i>
          </button>
          <button class="w-10 h-10 rounded-full flex items-center justify-center text-slate-500 hover:bg-gray-100 relative">
            <i class="fa-solid fa-bell"></i>
            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
          </button>
          <div class="w-10 h-10 rounded-full bg-orange-500 overflow-hidden">
            <img src="https://ui-avatars.com/api/?name=Admin&background=F97316&color=fff" alt="Admin">
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="p-4 md:p-6">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
          <router-link to="/admin/products" class="hover:text-orange-500">Products</router-link>
          <i class="fa-solid fa-chevron-right text-xs"></i>
          <span class="text-slate-800 font-medium">Product Detail</span>
        </div>

        <!-- Back Button & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
          <router-link to="/admin/products" class="inline-flex items-center gap-2 text-slate-600 hover:text-orange-500">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Products</span>
          </router-link>
          <div class="flex items-center gap-2">
            <router-link to="/admin/products/edit/1" class="px-4 py-2 border border-gray-200 rounded-lg font-medium text-slate-600 hover:bg-gray-50 flex items-center gap-2">
              <i class="fa-solid fa-pen text-sm"></i>
              <span class="hidden sm:inline">Edit</span>
            </router-link>
            <button class="px-4 py-2 border border-red-200 bg-red-50 rounded-lg font-medium text-red-500 hover:bg-red-100 flex items-center gap-2">
              <i class="fa-solid fa-trash text-sm"></i>
              <span class="hidden sm:inline">Delete</span>
            </button>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
          <!-- Left Column - Images -->
          <div class="lg:col-span-1 space-y-4">
            <!-- Main Image -->
            <div class="bg-white rounded-xl p-4 shadow-sm">
              <div class="aspect-square rounded-xl overflow-hidden mb-4">
                <img :src="product.images[selectedImage]" :alt="product.name" class="w-full h-full object-cover">
              </div>
              <!-- Thumbnail Gallery -->
              <div class="grid grid-cols-4 gap-2">
                <button 
                  v-for="(img, index) in product.images" 
                  :key="index"
                  @click="selectedImage = index"
                  :class="[
                    'aspect-square rounded-lg overflow-hidden border-2 transition-colors',
                    selectedImage === index ? 'border-orange-500' : 'border-transparent hover:border-gray-300'
                  ]"
                >
                  <img :src="img" :alt="`${product.name} ${index + 1}`" class="w-full h-full object-cover">
                </button>
              </div>
            </div>

            <!-- Status Card -->
            <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
              <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4">Status</h3>
              <div class="space-y-3">
                <!-- Is Active -->
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                  <div class="flex items-center gap-3">
                    <div :class="['w-3 h-3 rounded-full', product.is_active ? 'bg-green-500' : 'bg-gray-400']"></div>
                    <span class="text-sm font-medium">Active</span>
                  </div>
                  <span :class="['px-2 py-1 text-xs font-medium rounded-full', product.is_active ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600']">
                    {{ product.is_active ? 'Yes' : 'No' }}
                  </span>
                </div>
                <!-- Is Featured -->
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                  <div class="flex items-center gap-3">
                    <div :class="['w-3 h-3 rounded-full', product.is_featured ? 'bg-yellow-500' : 'bg-gray-400']"></div>
                    <span class="text-sm font-medium">Featured</span>
                  </div>
                  <span :class="['px-2 py-1 text-xs font-medium rounded-full', product.is_featured ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-100 text-gray-600']">
                    {{ product.is_featured ? 'Yes' : 'No' }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column - Product Info -->
          <div class="lg:col-span-2 space-y-4">
            <!-- Basic Info -->
            <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
              <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                <div class="flex-1">
                  <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span class="px-3 py-1 bg-orange-100 text-orange-600 rounded-full text-sm font-medium">{{ product.category }}</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm font-medium">{{ product.brand }}</span>
                  </div>
                  <h2 class="text-xl md:text-2xl font-bold text-slate-800">{{ product.name }}</h2>
                  <p class="text-sm text-slate-500 mt-1">SKU: {{ product.sku }}</p>
                </div>
                <div class="text-right">
                  <!-- Pricing with Discount -->
                  <div class="flex items-center gap-2 justify-end">
                    <p v-if="product.discount > 0" class="text-lg text-slate-400 line-through">${{ product.price.toFixed(2) }}</p>
                    <p class="text-2xl md:text-3xl font-bold text-orange-500">${{ finalPrice.toFixed(2) }}</p>
                  </div>
                  <span v-if="product.discount > 0" class="px-2 py-1 bg-red-100 text-red-600 rounded-full text-xs font-medium">
                    -{{ product.discount }}% OFF
                  </span>
                  <div class="flex items-center gap-1 mt-2 text-amber-500 justify-end">
                    <i class="fa-solid fa-star text-sm"></i>
                    <span class="font-medium">{{ product.rating }}</span>
                    <span class="text-slate-500 text-sm">({{ product.reviews }} reviews)</span>
                  </div>
                </div>
              </div>
              
              <!-- Short Description -->
              <p class="text-slate-500 text-sm mb-3 italic">"{{ product.short_description }}"</p>
              
              <!-- Full Description -->
              <p class="text-slate-600 leading-relaxed">{{ product.description }}</p>

              <!-- Shipping Info -->
              <div class="mt-4 pt-4 border-t border-gray-100">
                <div class="flex items-center gap-2">
                  <i class="fa-solid fa-truck text-slate-400"></i>
                  <span v-if="product.free_shipping" class="text-green-600 font-medium">Free Shipping</span>
                  <span v-else class="text-slate-600">Shipping: <strong>${{ product.shipping.toFixed(2) }}</strong></span>
                </div>
              </div>
            </div>

            <!-- Inventory & Stats -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 md:gap-4">
              <div class="bg-white rounded-xl p-4 shadow-sm text-center">
                <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-blue-100 flex items-center justify-center mx-auto mb-2">
                  <i class="fa-solid fa-box text-blue-500 text-lg md:text-xl"></i>
                </div>
                <p class="text-lg md:text-2xl font-bold text-slate-800">{{ product.stock }}</p>
                <p class="text-xs md:text-sm text-slate-500">In Stock</p>
              </div>
              <div class="bg-white rounded-xl p-4 shadow-sm text-center">
                <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-green-100 flex items-center justify-center mx-auto mb-2">
                  <i class="fa-solid fa-shopping-cart text-green-500 text-lg md:text-xl"></i>
                </div>
                <p class="text-lg md:text-2xl font-bold text-slate-800">{{ product.sold }}</p>
                <p class="text-xs md:text-sm text-slate-500">Total Sold</p>
              </div>
              <div class="bg-white rounded-xl p-4 shadow-sm text-center">
                <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-amber-100 flex items-center justify-center mx-auto mb-2">
                  <i class="fa-solid fa-star text-amber-500 text-lg md:text-xl"></i>
                </div>
                <p class="text-lg md:text-2xl font-bold text-slate-800">{{ product.rating }}</p>
                <p class="text-xs md:text-sm text-slate-500">Rating</p>
              </div>
              <div class="bg-white rounded-xl p-4 shadow-sm text-center">
                <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-purple-100 flex items-center justify-center mx-auto mb-2">
                  <i class="fa-solid fa-comments text-purple-500 text-lg md:text-xl"></i>
                </div>
                <p class="text-lg md:text-2xl font-bold text-slate-800">{{ product.reviews }}</p>
                <p class="text-xs md:text-sm text-slate-500">Reviews</p>
              </div>
            </div>

            <!-- Variants -->
            <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
              <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4">Variants</h3>
              
              <div class="space-y-4">
                <!-- Sizes -->
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">Available Sizes</label>
                  <div class="flex flex-wrap gap-2">
                    <span 
                      v-for="size in product.sizes" 
                      :key="size"
                      class="px-4 py-2 bg-orange-50 border border-orange-200 text-orange-600 rounded-lg font-medium"
                    >
                      {{ size }}
                    </span>
                  </div>
                </div>

                <!-- Colors -->
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">Available Colors</label>
                  <div class="flex flex-wrap gap-3">
                    <div 
                      v-for="color in product.colors" 
                      :key="color.name"
                      class="flex items-center gap-2 px-3 py-2 bg-gray-50 rounded-lg"
                    >
                      <div 
                        class="w-6 h-6 rounded-full ring-2 ring-offset-1 ring-gray-200"
                        :style="{ backgroundColor: color.hex }"
                      ></div>
                      <span class="text-sm text-slate-600">{{ color.name }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Additional Info -->
            <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
              <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4">Additional Information</h3>
              
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                  <span class="text-slate-600">Brand</span>
                  <span class="font-medium text-slate-800">{{ product.brand }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                  <span class="text-slate-600">Category</span>
                  <span class="font-medium text-slate-800">{{ product.category }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                  <span class="text-slate-600">Created</span>
                  <span class="font-medium text-slate-800">{{ product.createdAt }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                  <span class="text-slate-600">Last Updated</span>
                  <span class="font-medium text-slate-800">{{ product.updatedAt }}</span>
                </div>
              </div>
            </div>

            <!-- Tags -->
            <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
              <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4">Tags</h3>
              <div class="flex flex-wrap gap-2">
                <span 
                  v-for="tag in product.tags" 
                  :key="tag"
                  class="px-3 py-1 bg-orange-100 text-orange-600 rounded-full text-sm font-medium"
                >
                  {{ tag }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<style scoped>
/* ========================================
   MOBILE STYLES (max-width: 639px)
   ======================================== */
@media (max-width: 639px) {
  .stat-card {
    padding: 0.75rem;
  }
  
  .stat-card .icon {
    width: 40px;
    height: 40px;
  }
}

/* ========================================
   TABLET STYLES (640px - 1023px)
   ======================================== */
@media (min-width: 640px) and (max-width: 1023px) {
  .product-grid {
    grid-template-columns: 1fr;
  }
}

/* ========================================
   DESKTOP STYLES (1024px and above)
   ======================================== */
@media (min-width: 1024px) {
  .product-grid {
    grid-template-columns: 1fr 2fr;
  }
}

/* Smooth transitions */
button, a {
  transition: all 0.2s ease;
}

/* Image gallery */
.thumbnail-btn:hover {
  transform: scale(1.05);
}

/* Stats card hover effect */
.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
</style>
