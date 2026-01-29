<script setup lang="ts">
import { ref, reactive, computed, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import AdminHeader from "@/components/admin/AdminHeader.vue";
import AdminNav from "@/components/admin/AdminNav.vue";
import { useAdminProductStore } from "@/stores/admin/AdminProductStore";

import type { IProduct, IProductPayload, IProductVariant } from "@/types/ProductType";
import { useAdminCategoryStore } from "@/stores/admin/AdminCategoryStore";
import { useAdminBrandStore } from "@/stores/admin/AdminBrandStore";

const router = useRouter();
const route = useRoute();
const adminStore = useAdminProductStore();
const categoryStore = useAdminCategoryStore();
const bransStore = useAdminBrandStore();

const sidebarOpen = ref(false);
const fileInput = ref<HTMLInputElement | null>(null);
const isDragging = ref(false);
const isSubmitting = ref(false);
const isLoadingData = ref(false);
const errors = ref<Record<string, string[]>>({});
const deletedImageIds = ref<number[]>([]);

const productId = computed(() => Number(route.params.id));

interface IUiImage {
  id?: number;
  file?: File;
  preview: string;
  isExisting: boolean;
  is_primary: boolean;
}

interface IUiForm {
  name: string;
  description: string;
  short_description: string;
  price: number;
  discount: number;
  shipping: number;
  category_id: number;
  brand_id: number;
  is_featured: boolean;
  is_active: boolean;
  images: IUiImage[];
  variants: IProductVariant[];
}

const form = reactive<IUiForm>({
  name: "",
  description: "",
  short_description: "",
  price: 0,
  discount: 0,
  shipping: 0,
  category_id: 0,
  brand_id: 0,
  is_featured: false,
  is_active: true,
  images: [],
  variants: [],
});

onMounted(async () => {
  isLoadingData.value = true;
  try {
    categoryStore.fetchCategory();
    bransStore.fetchBrand();

    if (productId.value) {
      await loadProductData(productId.value);
    }
  } catch (error) {
    console.error("Error:", error);
  } finally {
    isLoadingData.value = false;
  }
});

const loadProductData = async (id: number) => {
  const response: any = await adminStore.fetchProductById(id);
  const product: IProduct = response.data || response;

  if (!product) return;

  form.name = product.name;
  form.description = product.description;
  form.short_description = product.short_description;
  form.price = Number(product.price);
  form.discount = Number(product.discount);

  form.shipping = Number(product.shipping) || 0;

  form.category_id = Number(product.category?.id || product.category_id || 0);
  form.brand_id = Number(product.brand_id || product.brand?.id || 0);

  form.is_featured = Boolean(product.is_featured);
  form.is_active = Boolean(product.is_active);

  if (product.variants && product.variants.length > 0) {
    form.variants = product.variants.map((v) => ({
      id: v.id,
      color: v.color,
      size: v.size,
      price: Number(v.price),
      stock: Number(v.stock),
    }));
  } else {
    addVariantRow();
  }

  if (product.images && product.images.length > 0) {
    const baseUrl = "http://127.0.0.1:8000/storage/";
    form.images = product.images.map((img) => ({
      id: img.id,
      preview: img.url.startsWith("http") ? img.url : `${baseUrl}${img.url.replace(/^\//, "")}`,
      isExisting: true,
      is_primary: img.is_primary,
    }));
  }
};

const calculatedFinalPrice = computed(() => {
  const price = Number(form.price) || 0;
  const discount = Number(form.discount) || 0;
  return (price - (price * discount) / 100).toFixed(2);
});

const availableSizes = ["XS", "S", "M", "L", "XL", "XXL", "2XL", "3XL"];
const availableColors = [
  "Black",
  "White",
  "Red",
  "Blue",
  "Green",
  "Yellow",
  "Orange",
  "Purple",
  "Pink",
  "Gray",
];

const addVariantRow = () => {
  form.variants.push({
    id: 0,
    size: "",
    color: "",
    stock: 10,
    price: 0,
  });
};

const removeVariantRow = (index: number) => {
  form.variants.splice(index, 1);
};

const processFiles = (files: FileList) => {
  for (let i = 0; i < files.length; i++) {
    const file = files[i];
    if (form.images.length >= 4) break;

    const preview = URL.createObjectURL(file);
    form.images.push({
      file: file,
      preview: preview,
      isExisting: false,
      is_primary: form.images.length === 0,
    });
  }
};

const removeImage = (index: number) => {
  const img = form.images[index];
  if (img.isExisting && img.id) {
    deletedImageIds.value.push(img.id);
  } else if (img.preview) {
    URL.revokeObjectURL(img.preview);
  }
  form.images.splice(index, 1);
};

const triggerFileInput = () => fileInput.value?.click();
const handleFileSelect = (e: Event) => {
  const target = e.target as HTMLInputElement;
  if (target.files) processFiles(target.files);
  target.value = "";
};

const handleDrop = (event: DragEvent) => {
  isDragging.value = false;
  const files = event.dataTransfer?.files;
  if (files) processFiles(files);
};

// --- send data ---
const submitForm = async () => {
  errors.value = {};
  isSubmitting.value = true;

  try {
    const newImages = form.images
      .filter((img) => !img.isExisting && img.file)
      .map((img) => img.file as File);

    const payload: any = {
      name: form.name,
      description: form.description,
      short_description: form.short_description,
      price: form.price,
      discount: form.discount,
      shipping: form.shipping,
      category_id: form.category_id,
      brand_id: form.brand_id,
      is_featured: form.is_featured ? 1 : 0,
      is_active: form.is_active ? 1 : 0,
      variants: form.variants,

      _method: "PUT",
      deleted_images: deletedImageIds.value,
      images: newImages,
    };

    await adminStore.updateProduct(productId.value, payload);

    router.push("/admin/products");
  } catch (err: any) {
    console.error(err);
    if (err.response?.status === 422) {
      errors.value = err.response.data.errors;
      window.scrollTo({ top: 0, behavior: "smooth" });
    } else {
      console.log("kkk");
    }
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex">
    <AdminNav :isOpen="sidebarOpen" @close="sidebarOpen = false" />

    <div class="flex-1 lg:ml-64">
      <AdminHeader title="EDIT PRODUCT" @toggle-sidebar="sidebarOpen = true">
        <div class="relative hidden md:block">
          <input
            type="text"
            placeholder="Search..."
            class="w-32 lg:w-48 pl-10 pr-4 py-2 bg-gray-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
          />
          <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
      </AdminHeader>

      <main class="p-4 md:p-6">
        <div v-if="isLoadingData" class="flex justify-center py-20">
          <i class="fa-solid fa-spinner fa-spin text-4xl text-orange-500"></i>
        </div>

        <form v-else @submit.prevent="submitForm">
          <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
            <router-link to="/admin/products" class="hover:text-orange-500">Products</router-link>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <span class="text-slate-800 font-medium">Edit Product</span>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
            <div class="lg:col-span-2 space-y-4 md:space-y-6">
              <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Basic Information</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                  <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2"
                      >Category <span class="text-red-500">*</span></label
                    >
                    <select
                      v-model="form.category_id"
                      class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                    >
                      <option :value="0">Select Category</option>
                      <option v-for="c in categoryStore.categories" :key="c.id" :value="c.id">
                        {{ c.name }}
                      </option>
                    </select>
                    <p v-if="errors.category_id" class="text-red-500 text-xs mt-1">
                      {{ errors.category_id[0] }}
                    </p>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Brand</label>
                    <select
                      v-model="form.brand_id"
                      class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                    >
                      <option :value="0">Select Brand</option>
                      <option v-for="b in bransStore.brands" :key="b.id" :value="b.id">
                        {{ b.name }}
                      </option>
                    </select>
                  </div>
                </div>

                <div class="mb-4">
                  <label class="block text-sm font-medium text-slate-700 mb-2"
                    >Product Name <span class="text-red-500">*</span></label
                  >
                  <input
                    type="text"
                    v-model="form.name"
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                  />
                  <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name[0] }}</p>
                </div>

                <div class="mb-4">
                  <label class="block text-sm font-medium text-slate-700 mb-2"
                    >Short Description</label
                  >
                  <input
                    type="text"
                    v-model="form.short_description"
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                  <textarea
                    v-model="form.description"
                    rows="5"
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 resize-none"
                  ></textarea>
                </div>
              </div>

              <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Pricing</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2"
                      >Price <span class="text-red-500">*</span></label
                    >
                    <div class="relative">
                      <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">$</span>
                      <input
                        type="number"
                        v-model="form.price"
                        class="w-full pl-8 pr-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                      />
                    </div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2"
                      >Discount (%)</label
                    >
                    <div class="relative">
                      <input
                        type="number"
                        v-model="form.discount"
                        class="w-full px-4 pr-8 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                      />
                      <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"
                        >%</span
                      >
                    </div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Final Price</label>
                    <div class="relative">
                      <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">$</span>
                      <input
                        type="text"
                        :value="calculatedFinalPrice"
                        readonly
                        class="w-full pl-8 pr-4 py-3 border bg-gray-50 rounded-lg text-slate-600 cursor-not-allowed"
                      />
                    </div>
                  </div>
                </div>
                <div class="mt-4">
                  <label class="block text-sm font-medium text-slate-700 mb-2">Shipping Cost</label>
                  <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">$</span>
                    <input
                      type="number"
                      v-model="form.shipping"
                      class="w-full pl-8 pr-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                    />
                  </div>
                </div>
              </div>

              <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                  <h3 class="text-lg font-semibold text-slate-800">Product Variants</h3>
                  <button
                    type="button"
                    @click="addVariantRow"
                    class="text-sm text-orange-600 font-medium hover:text-orange-700 flex items-center gap-1"
                  >
                    <i class="fa-solid fa-plus-circle"></i> Add Option
                  </button>
                </div>

                <div
                  class="grid grid-cols-10 gap-2 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider px-1"
                >
                  <div class="col-span-2">Size</div>
                  <div class="col-span-2">Color</div>
                  <div class="col-span-2">Stock</div>
                  <div class="col-span-2">Price</div>
                  <div class="col-span-1"></div>
                </div>

                <div class="space-y-3">
                  <div
                    v-for="(variant, index) in form.variants"
                    :key="index"
                    class="grid grid-cols-10 gap-2 items-center bg-gray-50 p-3 rounded-lg border border-gray-200"
                  >
                    <div class="col-span-2">
                      <select
                        v-model="variant.size"
                        class="w-full px-2 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:border-orange-500 bg-white"
                      >
                        <option value="" disabled>Size</option>
                        <option v-for="s in availableSizes" :key="s" :value="s">{{ s }}</option>
                      </select>
                    </div>
                    <div class="col-span-2">
                      <select
                        v-model="variant.color"
                        class="w-full px-2 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:border-orange-500 bg-white"
                      >
                        <option value="" disabled>Color</option>
                        <option v-for="c in availableColors" :key="c" :value="c">{{ c }}</option>
                      </select>
                    </div>
                    <div class="col-span-2">
                      <input
                        type="number"
                        v-model="variant.price"
                        class="w-full px-2 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:border-orange-500"
                        placeholder="Price"
                      />
                    </div>
                    <div class="col-span-2">
                      <input
                        type="number"
                        v-model="variant.stock"
                        placeholder="Qty"
                        class="w-full px-2 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:border-orange-500"
                      />
                    </div>

                    <div class="col-span-1 text-center">
                      <button
                        type="button"
                        @click="removeVariantRow(index)"
                        class="text-red-500 hover:text-red-700 p-2"
                        :disabled="form.variants.length === 1"
                      >
                        <i class="fa-solid fa-trash-can"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="space-y-4 md:space-y-6">
              <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
                <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4">
                  Product Images
                </h3>

                <input
                  ref="fileInput"
                  type="file"
                  multiple
                  class="hidden"
                  @change="handleFileSelect"
                  accept="image/*"
                />

                <div
                  @click="triggerFileInput"
                  @dragover.prevent="isDragging = true"
                  @dragleave.prevent="isDragging = false"
                  @drop.prevent="handleDrop"
                  :class="[
                    'border-2 border-dashed rounded-xl p-4 sm:p-8 text-center transition-colors cursor-pointer mb-4',
                    isDragging
                      ? 'border-orange-500 bg-orange-50'
                      : 'border-gray-200 hover:border-orange-500',
                    errors.images ? 'border-red-500' : '',
                  ]"
                >
                  <div
                    class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-orange-100 flex items-center justify-center mx-auto mb-3 sm:mb-4"
                  >
                    <i class="fa-solid fa-cloud-arrow-up text-2xl text-orange-500"></i>
                  </div>
                  <p class="text-sm text-slate-600 mb-1">Drag & drop your image here</p>
                  <p class="text-xs text-slate-400 mt-2">PNG, JPG, WEBP up to 5MB</p>
                </div>

                <div class="grid grid-cols-4 gap-2">
                  <div
                    v-for="(img, idx) in form.images"
                    :key="idx"
                    class="aspect-square rounded-lg bg-gray-100 relative overflow-hidden group"
                  >
                    <img :src="img.preview" class="w-full h-full object-cover" />
                    <button
                      type="button"
                      @click="removeImage(idx)"
                      class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity"
                    >
                      <i class="fa-solid fa-times"></i>
                    </button>
                    <span
                      v-if="idx === 0"
                      class="absolute bottom-1 left-1 px-1.5 py-0.5 bg-orange-500 text-white text-xs rounded"
                      >Main</span
                    >
                    <span
                      v-if="img.isExisting"
                      class="absolute top-1 left-1 px-1.5 py-0.5 bg-blue-500 text-white text-[10px] rounded"
                      >Old</span
                    >
                  </div>

                  <div
                    v-for="n in Math.max(0, 4 - form.images.length)"
                    :key="'placeholder-' + n"
                    @click="triggerFileInput"
                    class="aspect-square rounded-lg border-2 border-dashed border-gray-200 flex items-center justify-center text-slate-400 hover:border-orange-500 hover:text-orange-500 cursor-pointer transition-colors"
                  >
                    <i class="fa-solid fa-plus"></i>
                  </div>
                </div>
              </div>

              <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
                <h3 class="text-base md:text-lg font-semibold text-slate-800 mb-4">Status</h3>
                <div class="space-y-4">
                  <div
                    class="flex items-center justify-between p-3 border border-gray-200 rounded-lg"
                  >
                    <div class="flex items-center gap-3">
                      <div
                        :class="[
                          'w-3 h-3 rounded-full',
                          form.is_active ? 'bg-green-500' : 'bg-gray-400',
                        ]"
                      ></div>
                      <div>
                        <span class="text-sm font-medium">Is Active</span>
                        <p class="text-xs text-slate-500">Product will be visible</p>
                      </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input v-model="form.is_active" type="checkbox" class="sr-only peer" />
                      <div
                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"
                      ></div>
                    </label>
                  </div>

                  <div
                    class="flex items-center justify-between p-3 border border-gray-200 rounded-lg"
                  >
                    <div class="flex items-center gap-3">
                      <div
                        :class="[
                          'w-3 h-3 rounded-full',
                          form.is_featured ? 'bg-yellow-500' : 'bg-gray-400',
                        ]"
                      ></div>
                      <div>
                        <span class="text-sm font-medium">Is Featured</span>
                        <p class="text-xs text-slate-500">Show on homepage</p>
                      </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input v-model="form.is_featured" type="checkbox" class="sr-only peer" />
                      <div
                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"
                      ></div>
                    </label>
                  </div>
                </div>
              </div>

              <div class="flex flex-col gap-2">
                <button
                  type="submit"
                  :disabled="isSubmitting"
                  class="w-full py-3 bg-orange-500 text-white rounded-lg font-medium hover:bg-orange-600 disabled:opacity-50"
                >
                  {{ isSubmitting ? "Updating..." : "Update Product" }}
                </button>
                <button
                  type="button"
                  @click="router.push('/admin/products')"
                  class="w-full py-3 border border-gray-200 text-slate-600 rounded-lg font-medium hover:bg-gray-50"
                >
                  Cancel
                </button>
              </div>
            </div>
          </div>
        </form>
      </main>
    </div>
  </div>
</template>

<style scoped>
/* Keeping your exact existing styles */
input[type="text"],
input[type="number"],
select,
textarea {
  font-size: 16px;
}
@media (max-width: 639px) {
  .form-card {
    padding: 1rem;
  }
  .form-card h3 {
    font-size: 1rem;
    margin-bottom: 0.75rem;
  }
  input[type="text"],
  input[type="number"],
  select,
  textarea {
    padding: 0.75rem;
    font-size: 16px;
  }
  label {
    font-size: 0.8125rem;
  }
  .size-option {
    padding: 0.625rem 0.875rem;
    min-width: 48px;
  }
  .color-swatch {
    width: 36px;
    height: 36px;
  }
  .upload-area {
    padding: 1.5rem;
  }
  .upload-area .upload-icon {
    width: 48px;
    height: 48px;
  }
  .thumbnail-grid {
    grid-template-columns: repeat(4, 1fr);
    gap: 0.375rem;
  }
  .status-option {
    padding: 0.75rem;
  }
  .tag {
    font-size: 0.75rem;
    padding: 0.375rem 0.625rem;
  }
}
@media (min-width: 640px) and (max-width: 1023px) {
  .form-card {
    padding: 1.25rem;
  }
  .form-card h3 {
    font-size: 1.0625rem;
  }
  input[type="text"],
  input[type="number"],
  select,
  textarea {
    padding: 0.75rem 1rem;
  }
  .size-option {
    padding: 0.5rem 1rem;
  }
  .upload-area {
    padding: 1.5rem;
  }
}
@media (min-width: 1024px) {
  .form-card {
    padding: 1.5rem;
  }
  .form-card h3 {
    font-size: 1.125rem;
    margin-bottom: 1rem;
  }
  input[type="text"],
  input[type="number"],
  select,
  textarea {
    padding: 0.75rem 1rem;
  }
  input[type="text"]:hover,
  input[type="number"]:hover,
  select:hover,
  textarea:hover {
    border-color: #e2e8f0;
  }
  .size-option:hover {
    border-color: #f97316;
    background-color: #fff7ed;
  }
  .color-swatch:hover {
    transform: scale(1.1);
  }
  .upload-area:hover {
    border-color: #f97316;
    background-color: #fffbf5;
  }
  .thumbnail:hover .remove-btn {
    opacity: 1;
  }
  .action-btn:hover {
    transform: translateY(-1px);
  }
}
@media (min-width: 1280px) {
  .form-card {
    padding: 1.5rem 2rem;
  }
}
input,
select,
textarea,
button {
  transition: all 0.2s ease;
}
input:focus,
select:focus,
textarea:focus {
  outline: none;
}
input[type="file"] {
  display: none;
}
.upload-area.drag-active {
  border-color: #f97316;
  background-color: #fff7ed;
}
input[type="checkbox"],
input[type="radio"] {
  accent-color: #f97316;
}
.tag {
  transition: all 0.2s ease;
}
.tag:hover {
  background-color: #ffedd5;
}
.thumbnail .remove-btn {
  opacity: 0.8;
  transition: opacity 0.2s ease;
}
.btn-loading {
  position: relative;
  color: transparent;
}
.btn-loading::after {
  content: "";
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
@media print {
  .action-buttons,
  .sidebar {
    display: none !important;
  }
}
</style>
