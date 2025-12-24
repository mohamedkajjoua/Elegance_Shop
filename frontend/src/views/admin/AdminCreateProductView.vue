<script setup>
import { ref, reactive, computed } from 'vue'

// Toggle state for submenu
const productsOpen = ref(true)
// Mobile sidebar toggle
const sidebarOpen = ref(false)

// File input ref
const fileInput = ref(null)
// Drag state
const isDragging = ref(false)

// Form data
const form = reactive({
  category: '',
  brand: '',
  name: '',
  short_description: '',
  description: '',
  price: 0,
  discount: 0,
  shipping: 0,
  free_shipping: false,
  stock: 0,
  sku: '',
  sizes: [],
  colors: [],
  is_featured: false,
  is_active: true,
  images: []
})

// Available sizes
const availableSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '2XL', '3XL']

// Available colors
const availableColors = [
  { name: 'Black', hex: '#000000' },
  { name: 'White', hex: '#FFFFFF' },
  { name: 'Red', hex: '#EF4444' },
  { name: 'Blue', hex: '#3B82F6' },
  { name: 'Green', hex: '#22C55E' },
  { name: 'Yellow', hex: '#FACC15' },
  { name: 'Orange', hex: '#F97316' },
  { name: 'Purple', hex: '#A855F7' },
  { name: 'Pink', hex: '#EC4899' },
  { name: 'Gray', hex: '#6B7280' }
]

// Computed final price after discount
const calculatedFinalPrice = computed(() => {
  if (!form.price || form.price <= 0) return '0.00'
  const discountAmount = (form.price * (form.discount || 0)) / 100
  const finalPrice = form.price - discountAmount
  return finalPrice.toFixed(2)
})

// ========== IMAGE UPLOAD FUNCTIONS ==========

// Trigger file input click
const triggerFileInput = () => {
  if (fileInput.value) {
    fileInput.value.click()
  }
}

// Handle file selection from input
const handleFileSelect = (event) => {
  const files = event.target.files
  if (files) {
    processFiles(files)
  }
  // Reset input so same file can be selected again
  event.target.value = ''
}

// Handle dropped files
const handleDrop = (event) => {
  isDragging.value = false
  const files = event.dataTransfer.files
  if (files) {
    processFiles(files)
  }
}

// Process files and create previews
const processFiles = (files) => {
  const maxFiles = 4
  const maxSize = 5 * 1024 * 1024 // 5MB
  const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif']

  for (let i = 0; i < files.length; i++) {
    // Check if we've reached max files
    if (form.images.length >= maxFiles) {
      alert(`Maximum ${maxFiles} images allowed`)
      break
    }

    const file = files[i]

    // Validate file type
    if (!allowedTypes.includes(file.type)) {
      alert(`${file.name} is not a valid image type. Please use PNG, JPG, WEBP or GIF.`)
      continue
    }

    // Validate file size
    if (file.size > maxSize) {
      alert(`${file.name} is too large. Maximum size is 5MB.`)
      continue
    }

    // Create preview URL
    const preview = URL.createObjectURL(file)
    
    // Add to form images
    form.images.push({
      file: file,
      preview: preview,
      name: file.name
    })
  }
}

// Remove image at index
const removeImage = (index) => {
  // Revoke object URL to free memory
  if (form.images[index].preview) {
    URL.revokeObjectURL(form.images[index].preview)
  }
  form.images.splice(index, 1)
}

// ========== FORM FUNCTIONS ==========

// Form submission handler
const submitForm = () => {
  console.log('Form Data:', {
    ...form,
    final_price: parseFloat(calculatedFinalPrice.value)
  })
  alert('Product created successfully!')
}

// Reset form
const resetForm = () => {
  // Clear image previews
  form.images.forEach(img => {
    if (img.preview) {
      URL.revokeObjectURL(img.preview)
    }
  })
  
  form.category = ''
  form.brand = ''
  form.name = ''
  form.short_description = ''
  form.description = ''
  form.price = 0
  form.discount = 0
  form.shipping = 0
  form.free_shipping = false
  form.stock = 0
  form.sku = ''
  form.sizes = []
  form.colors = []
  form.is_featured = false
  form.is_active = true
  form.images = []
}
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
            <router-link to="/admin/products" class="block px-12 py-2 text-sm text-slate-300 hover:text-orange-500">Product List</router-link>
            <router-link to="/admin/products/create" class="block px-12 py-2 text-sm text-orange-500 font-medium">Create Product</router-link>
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
        <h1 class="text-base md:text-lg font-bold text-slate-800">CREATE PRODUCT</h1>
        
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
          <div class="relative hidden md:block">
            <input type="text" placeholder="Search..." class="w-32 lg:w-48 pl-10 pr-4 py-2 bg-gray-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="p-4 md:p-6">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
          <a href="#" class="hover:text-orange-500">Products</a>
          <i class="fa-solid fa-chevron-right text-xs"></i>
          <span class="text-slate-800 font-medium">Create Product</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
          <!-- Main Form -->
          <div class="lg:col-span-2 space-y-4 md:space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
              <h3 class="text-lg font-semibold text-slate-800 mb-4">Basic Information</h3>
              
              <div class="space-y-4">
                <!-- Category & Brand -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Category <span class="text-red-500">*</span></label>
                    <select v-model="form.category" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                      <option value="">Select Category</option>
                      <option value="fashion">Fashion</option>
                      <option value="electronics">Electronics</option>
                      <option value="accessories">Accessories</option>
                      <option value="shoes">Shoes</option>
                      <option value="bags">Bags</option>
                    </select>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Brand <span class="text-red-500">*</span></label>
                    <select v-model="form.brand" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                      <option value="">Select Brand</option>
                      <option value="nike">Nike</option>
                      <option value="adidas">Adidas</option>
                      <option value="puma">Puma</option>
                      <option value="gucci">Gucci</option>
                      <option value="zara">Zara</option>
                    </select>
                  </div>
                </div>

                <!-- Product Name -->
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">Product Name <span class="text-red-500">*</span></label>
                  <input v-model="form.name" type="text" placeholder="Enter product name" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>

                <!-- Short Description -->
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">Short Description</label>
                  <input v-model="form.short_description" type="text" placeholder="Brief summary of the product (max 150 characters)" maxlength="150" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                  <p class="text-xs text-slate-400 mt-1">{{ form.short_description.length }}/150 characters</p>
                </div>

                <!-- Full Description -->
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">Description <span class="text-red-500">*</span></label>
                  <textarea v-model="form.description" rows="5" placeholder="Enter detailed product description" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none"></textarea>
                </div>
              </div>
            </div>

            <!-- Pricing -->
            <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
              <h3 class="text-lg font-semibold text-slate-800 mb-4">Pricing</h3>
              
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Price -->
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">Price <span class="text-red-500">*</span></label>
                  <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">$</span>
                    <input v-model="form.price" type="number" step="0.01" min="0" placeholder="0.00" class="w-full pl-8 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                  </div>
                </div>
                <!-- Discount -->
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">Discount (%)</label>
                  <div class="relative">
                    <input v-model="form.discount" type="number" min="0" max="100" placeholder="0" class="w-full px-4 pr-8 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">%</span>
                  </div>
                </div>
                <!-- Final Price (Calculated) -->
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">Final Price</label>
                  <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">$</span>
                    <input :value="calculatedFinalPrice" type="text" readonly class="w-full pl-8 pr-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-slate-600 cursor-not-allowed">
                  </div>
                  <p class="text-xs text-slate-400 mt-1">Auto-calculated from price & discount</p>
                </div>
              </div>

              <!-- Shipping Cost -->
              <div class="mt-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Shipping Cost</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">$</span>
                    <input v-model="form.shipping" type="number" step="0.01" min="0" placeholder="0.00" class="w-full pl-8 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                  </div>
                  <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-orange-500">
                    <input v-model="form.free_shipping" type="checkbox" class="w-4 h-4 text-orange-500 rounded focus:ring-orange-500">
                    <span class="text-sm text-slate-700">Free Shipping</span>
                  </label>
                </div>
              </div>
            </div>

            <!-- Inventory -->
            <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
              <h3 class="text-lg font-semibold text-slate-800 mb-4">Inventory</h3>
              
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">Stock Quantity <span class="text-red-500">*</span></label>
                  <input v-model="form.stock" type="number" min="0" placeholder="0" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">SKU</label>
                  <input v-model="form.sku" type="text" placeholder="SKU-001" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
              </div>
            </div>

            <!-- Variants - Sizes & Colors -->
            <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
              <h3 class="text-lg font-semibold text-slate-800 mb-4">Variants</h3>
              
              <div class="space-y-4">
                <!-- Sizes -->
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">Sizes</label>
                  <div class="flex flex-wrap gap-2">
                    <label v-for="size in availableSizes" :key="size" 
                      :class="[
                        'flex items-center gap-2 px-4 py-2 border rounded-lg cursor-pointer transition-colors',
                        form.sizes.includes(size) ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-orange-500'
                      ]">
                      <input type="checkbox" :value="size" v-model="form.sizes" class="w-4 h-4 text-orange-500 rounded focus:ring-orange-500">
                      <span :class="['text-sm', form.sizes.includes(size) ? 'text-orange-600' : '']">{{ size }}</span>
                    </label>
                  </div>
                </div>

                <!-- Colors -->
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">Colors</label>
                  <div class="flex flex-wrap gap-3">
                    <label v-for="color in availableColors" :key="color.name" class="relative cursor-pointer">
                      <input type="checkbox" :value="color.name" v-model="form.colors" class="sr-only">
                      <div 
                        :class="[
                          'w-10 h-10 rounded-full transition-all',
                          form.colors.includes(color.name) ? 'ring-2 ring-offset-2 ring-orange-500' : 'hover:ring-2 hover:ring-gray-300 hover:ring-offset-2'
                        ]"
                        :style="{ backgroundColor: color.hex }"
                        :title="color.name"
                      >
                        <i v-if="form.colors.includes(color.name)" class="fa-solid fa-check text-white text-xs absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></i>
                      </div>
                    </label>
                    <!-- Add Custom Color -->
                    <label class="w-10 h-10 rounded-full border-2 border-dashed border-gray-300 flex items-center justify-center cursor-pointer hover:border-orange-500 hover:text-orange-500 text-gray-400">
                      <i class="fa-solid fa-plus text-sm"></i>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Sidebar -->
          <div class="space-y-4 md:space-y-6">
            <!-- Product Images -->
            <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
              <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4">Product Images</h3>
              
              <!-- Hidden File Input -->
              <input 
                ref="fileInput" 
                type="file" 
                accept="image/*" 
                multiple 
                @change="handleFileSelect" 
                class="hidden"
              >
              
              <!-- Main Image Upload Area -->
              <div 
                @click="triggerFileInput"
                @dragover.prevent="isDragging = true"
                @dragleave.prevent="isDragging = false"
                @drop.prevent="handleDrop"
                :class="[
                  'border-2 border-dashed rounded-xl p-4 sm:p-8 text-center transition-colors cursor-pointer mb-4',
                  isDragging ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-orange-500'
                ]"
              >
                <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-orange-100 flex items-center justify-center mx-auto mb-3 sm:mb-4">
                  <i class="fa-solid fa-cloud-arrow-up text-2xl text-orange-500"></i>
                </div>
                <p class="text-sm text-slate-600 mb-1">Drag & drop your image here</p>
                <p class="text-xs text-slate-400">or click to browse</p>
                <p class="text-xs text-slate-400 mt-2">PNG, JPG, WEBP up to 5MB</p>
              </div>

              <!-- Image Preview Grid -->
              <div class="grid grid-cols-4 gap-2">
                <!-- Uploaded Images -->
                <div 
                  v-for="(image, index) in form.images" 
                  :key="index"
                  class="aspect-square rounded-lg bg-gray-100 relative overflow-hidden group"
                >
                  <img :src="image.preview" :alt="'Image ' + (index + 1)" class="w-full h-full object-cover">
                  <button 
                    @click="removeImage(index)"
                    class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity"
                  >
                    <i class="fa-solid fa-times"></i>
                  </button>
                  <!-- Main Image Badge -->
                  <span v-if="index === 0" class="absolute bottom-1 left-1 px-1.5 py-0.5 bg-orange-500 text-white text-xs rounded">Main</span>
                </div>
                
                <!-- Add More Images Button (show if less than 4 images) -->
                <div 
                  v-for="n in Math.max(0, 4 - form.images.length)" 
                  :key="'placeholder-' + n"
                  @click="triggerFileInput"
                  class="aspect-square rounded-lg border-2 border-dashed border-gray-200 flex items-center justify-center text-slate-400 hover:border-orange-500 hover:text-orange-500 cursor-pointer transition-colors"
                >
                  <i class="fa-solid fa-plus"></i>
                </div>
              </div>
              
              <!-- Image Count -->
              <p class="text-xs text-slate-400 mt-2 text-center">{{ form.images.length }}/4 images uploaded</p>
            </div>

            <!-- Status & Featured -->
            <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
              <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4">Status</h3>
              
              <div class="space-y-4">
                <!-- Is Active Toggle -->
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                  <div class="flex items-center gap-3">
                    <div :class="['w-3 h-3 rounded-full', form.is_active ? 'bg-green-500' : 'bg-gray-400']"></div>
                    <div>
                      <span class="text-sm font-medium">Is Active</span>
                      <p class="text-xs text-slate-500">Product will be visible to customers</p>
                    </div>
                  </div>
                  <label class="relative inline-flex items-center cursor-pointer">
                    <input v-model="form.is_active" type="checkbox" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                  </label>
                </div>

                <!-- Is Featured Toggle -->
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                  <div class="flex items-center gap-3">
                    <div :class="['w-3 h-3 rounded-full', form.is_featured ? 'bg-yellow-500' : 'bg-gray-400']"></div>
                    <div>
                      <span class="text-sm font-medium">Is Featured</span>
                      <p class="text-xs text-slate-500">Show on homepage featured section</p>
                    </div>
                  </div>
                  <label class="relative inline-flex items-center cursor-pointer">
                    <input v-model="form.is_featured" type="checkbox" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                  </label>
                </div>
              </div>
            </div>

            <!-- Tags -->
            <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
              <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4">Tags</h3>
              <div class="flex flex-wrap gap-2 mb-3">
                <span class="px-3 py-1 bg-orange-100 text-orange-600 rounded-full text-sm flex items-center gap-1">
                  Fashion <button class="hover:text-orange-800"><i class="fa-solid fa-times text-xs"></i></button>
                </span>
                <span class="px-3 py-1 bg-orange-100 text-orange-600 rounded-full text-sm flex items-center gap-1">
                  New Arrival <button class="hover:text-orange-800"><i class="fa-solid fa-times text-xs"></i></button>
                </span>
              </div>
              <input type="text" placeholder="Add a tag..." class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
          </div>
        </div>

        <!-- Action Buttons - Responsive: Fixed on mobile, inline on desktop -->
        <div class="fixed md:static bottom-0 left-0 right-0 bg-white md:bg-transparent border-t md:border-0 border-gray-200 p-4 md:p-0 md:mt-6 z-20">
          <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3">
            <router-link to="/admin/products" class="order-3 sm:order-1 px-4 sm:px-6 py-3 border border-gray-200 rounded-lg font-medium text-slate-600 hover:bg-gray-50 text-sm sm:text-base text-center">
              Cancel
            </router-link>
            <button @click="form.is_active = false; submitForm()" class="order-2 px-4 sm:px-6 py-3 border border-gray-200 rounded-lg font-medium text-slate-600 hover:bg-gray-50 text-sm sm:text-base">
              Save as Draft
            </button>
            <button @click="submitForm" class="order-1 sm:order-3 px-4 sm:px-6 py-3 bg-orange-500 text-white rounded-lg font-medium hover:bg-orange-600 text-sm sm:text-base">
              <i class="fa-solid fa-plus mr-2"></i>Create Product
            </button>
          </div>
        </div>
        <!-- Spacer for fixed bottom buttons on mobile -->
        <div class="h-36 md:hidden"></div>
      </main>
    </div>
  </div>
</template>

<style scoped>
/* ========================================
   BASE STYLES
   ======================================== */

/* Form inputs - larger touch targets */
input[type="text"],
input[type="number"],
select,
textarea {
  font-size: 16px; /* Prevents zoom on iOS */
}

/* ========================================
   MOBILE STYLES (max-width: 639px)
   ======================================== */
@media (max-width: 639px) {
  /* Cards with tighter padding */
  .form-card {
    padding: 1rem;
  }

  /* Section titles */
  .form-card h3 {
    font-size: 1rem;
    margin-bottom: 0.75rem;
  }

  /* Form inputs */
  input[type="text"],
  input[type="number"],
  select,
  textarea {
    padding: 0.75rem;
    font-size: 16px;
  }

  /* Labels */
  label {
    font-size: 0.8125rem;
  }

  /* Size checkboxes - larger touch area */
  .size-option {
    padding: 0.625rem 0.875rem;
    min-width: 48px;
  }

  /* Color swatches - larger for touch */
  .color-swatch {
    width: 36px;
    height: 36px;
  }

  /* Upload area */
  .upload-area {
    padding: 1.5rem;
  }

  .upload-area .upload-icon {
    width: 48px;
    height: 48px;
  }

  /* Thumbnail grid */
  .thumbnail-grid {
    grid-template-columns: repeat(4, 1fr);
    gap: 0.375rem;
  }

  /* Status options */
  .status-option {
    padding: 0.75rem;
  }

  /* Tags */
  .tag {
    font-size: 0.75rem;
    padding: 0.375rem 0.625rem;
  }
}

/* ========================================
   TABLET STYLES (640px - 1023px)
   ======================================== */
@media (min-width: 640px) and (max-width: 1023px) {
  /* Form cards */
  .form-card {
    padding: 1.25rem;
  }

  .form-card h3 {
    font-size: 1.0625rem;
  }

  /* Inputs */
  input[type="text"],
  input[type="number"],
  select,
  textarea {
    padding: 0.75rem 1rem;
  }

  /* Size options */
  .size-option {
    padding: 0.5rem 1rem;
  }

  /* Upload area */
  .upload-area {
    padding: 1.5rem;
  }
}

/* ========================================
   DESKTOP STYLES (1024px and above)
   ======================================== */
@media (min-width: 1024px) {
  /* Form cards */
  .form-card {
    padding: 1.5rem;
  }

  .form-card h3 {
    font-size: 1.125rem;
    margin-bottom: 1rem;
  }

  /* Inputs */
  input[type="text"],
  input[type="number"],
  select,
  textarea {
    padding: 0.75rem 1rem;
  }

  /* Hover effects */
  input[type="text"]:hover,
  input[type="number"]:hover,
  select:hover,
  textarea:hover {
    border-color: #e2e8f0;
  }

  /* Size options with hover */
  .size-option:hover {
    border-color: #f97316;
    background-color: #fff7ed;
  }

  /* Color swatches with hover */
  .color-swatch:hover {
    transform: scale(1.1);
  }

  /* Upload area hover */
  .upload-area:hover {
    border-color: #f97316;
    background-color: #fffbf5;
  }

  /* Thumbnail hover */
  .thumbnail:hover .remove-btn {
    opacity: 1;
  }

  /* Action buttons hover */
  .action-btn:hover {
    transform: translateY(-1px);
  }
}

/* ========================================
   LARGE DESKTOP (1280px+)
   ======================================== */
@media (min-width: 1280px) {
  .form-card {
    padding: 1.5rem 2rem;
  }
}

/* ========================================
   UTILITY STYLES
   ======================================== */

/* Smooth transitions */
input,
select,
textarea,
button {
  transition: all 0.2s ease;
}

/* Focus states */
input:focus,
select:focus,
textarea:focus {
  outline: none;
}

/* Custom file input styling */
input[type="file"] {
  display: none;
}

/* Drag and drop active state */
.upload-area.drag-active {
  border-color: #f97316;
  background-color: #fff7ed;
}

/* Checkbox and radio styling */
input[type="checkbox"],
input[type="radio"] {
  accent-color: #f97316;
}

/* Tag removal animation */
.tag {
  transition: all 0.2s ease;
}

.tag:hover {
  background-color: #ffedd5;
}

/* Image thumbnail delete button */
.thumbnail .remove-btn {
  opacity: 0.8;
  transition: opacity 0.2s ease;
}

/* Loading state for buttons */
.btn-loading {
  position: relative;
  color: transparent;
}

.btn-loading::after {
  content: '';
  position: absolute;
  width: 16px;
  height: 16px;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  border: 2px solid #fff;
  border-radius: 50%;
  border-top-color: transparent;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to {
    transform: translate(-50%, -50%) rotate(360deg);
  }
}

/* Scrollbar styling */
textarea::-webkit-scrollbar {
  width: 6px;
}

textarea::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 3px;
}

textarea::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 3px;
}

textarea::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* Print styles */
@media print {
  .action-buttons,
  .sidebar {
    display: none !important;
  }
}
</style>
