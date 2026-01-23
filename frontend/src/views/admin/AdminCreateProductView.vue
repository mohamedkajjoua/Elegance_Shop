<script setup lang="ts">
import { ref, reactive, computed, onMounted } from "vue";
import AdminHeader from "@/components/admin/AdminHeader.vue";
import AdminNav from "@/components/admin/AdminNav.vue";
import type { IProductPayload } from "@/types/product";
import { useRouter } from "vue-router";

import { useProductShopStore } from "@/stores/user/ProductShop";
import { useAdminProductStore } from "@/stores/admin/AdminProductStore";

// Mobile sidebar toggle
const sidebarOpen = ref(false);

// File input ref
const fileInput = ref<HTMLInputElement | null>(null);
const isDragging = ref(false);

const router = useRouter();
const adminStore = useAdminProductStore();
const shopStore = useProductShopStore();
const isSubmitting = ref(false);
const errors = ref<Record<string, string[]>>({});

interface IUiForm extends Omit<IProductPayload, "images"> {
  images: { file: File; preview: string; name: string }[];
  variants: { size: string; color: string; stock: number; price?: number }[];
}

// Form data
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

  variants: [{ size: "", color: "", stock: 10 }],
});

onMounted(async () => {
  if (shopStore.categories.length === 0) await shopStore.fetchFilterAttributes();
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

const calculatedFinalPrice = computed(() => {
  if (!form.price || form.price <= 0) return "0.00";
  const discountAmount = (form.price * (form.discount || 0)) / 100;
  const finalPrice = form.price - discountAmount;
  return finalPrice.toFixed(2);
});

const addVariantRow = () => {
  form.variants.push({
    size: "",
    color: "",
    stock: 10,
  });
};

const removeVariantRow = (index: number) => {
  form.variants.splice(index, 1);
};

const triggerFileInput = () => {
  if (fileInput.value) fileInput.value.click();
};

const handleFileSelect = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const files = target.files;
  if (files) processFiles(files);
  target.value = "";
};

const handleDrop = (event: DragEvent) => {
  isDragging.value = false;
  const files = event.dataTransfer?.files;
  if (files) processFiles(files);
};

const processFiles = (files: FileList) => {
  const maxFiles = 4;
  const maxSize = 5 * 1024 * 1024; // 5MB
  const allowedTypes = ["image/jpeg", "image/png", "image/webp", "image/gif"];

  for (let i = 0; i < files.length; i++) {
    if (form.images.length >= maxFiles) {
      alert(`Maximum ${maxFiles} images allowed`);
      break;
    }
    const file = files[i];
    if (!allowedTypes.includes(file.type)) {
      alert(`${file.name} is not a valid image type.`);
      continue;
    }
    if (file.size > maxSize) {
      alert(`${file.name} is too large.`);
      continue;
    }
    const preview = URL.createObjectURL(file);
    form.images.push({ file: file, preview: preview, name: file.name });
  }
};

const removeImage = (index: number) => {
  if (form.images[index].preview) {
    URL.revokeObjectURL(form.images[index].preview);
  }
  form.images.splice(index, 1);
};

// ========== FORM FUNCTIONS ==========

const submitForm = async () => {
  errors.value = {};

  if (!form.name || !form.price || !form.category_id) {
    alert("Please fill in all required fields (Name, Price, Category)");
    return;
  }

  // التحقق من المتغيرات
  const invalidVariants = form.variants.filter((v) => !v.size || !v.color);
  if (invalidVariants.length > 0) {
    alert("Please select Size and Color for all variant rows.");
    return;
  }

  isSubmitting.value = true;
  try {
    const imagesToUpload = form.images.map((img) => img.file);

    // تجهيز المتغيرات مع السعر
    const finalVariants = form.variants.map((v) => ({
      size: v.size,
      color: v.color,
      stock: v.stock,
      price: v.price, // أو يمكنك إضافة حقل سعر خاص لكل متغير إذا أردت
    }));

    const payload: any = {
      ...form,
      images: imagesToUpload,
      category_id: form.category_id === 0 ? null : form.category_id,
      brand_id: form.brand_id === 0 ? null : form.brand_id,
      variants: finalVariants, // إرسال المصفوفة اليدوية
    };

    await adminStore.createProduct(payload);

    alert("Product Created Successfully");
    resetForm();
    router.push("/admin/products");
  } catch (err: any) {
    console.error(err);
    if (err.response && err.response.status === 422) {
      errors.value = err.response.data.errors;
      window.scrollTo({ top: 0, behavior: "smooth" });
    } else {
      alert("Failed to create product");
    }
  } finally {
    isSubmitting.value = false;
  }
};

const resetForm = () => {
  form.images.forEach((img) => URL.revokeObjectURL(img.preview));
  form.category_id = 0;
  form.brand_id = 0;
  form.name = "";
  form.short_description = "";
  form.description = "";
  form.price = 0;
  form.discount = 0;
  form.shipping = 0;
  form.images = [];
  form.variants = [{ size: "", color: "", price: 0, stock: 10 }];
};
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex">
    <AdminNav :isOpen="sidebarOpen" @close="sidebarOpen = false" />

    <div class="flex-1 lg:ml-64">
      <AdminHeader title="CREATE PRODUCT" @toggle-sidebar="sidebarOpen = true">
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
        <form @submit.prevent="submitForm">
          <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
            <a href="#" class="hover:text-orange-500">Products</a>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <span class="text-slate-800 font-medium">Create Product</span>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
            <div class="lg:col-span-2 space-y-4 md:space-y-6">
              <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Basic Information</h3>
                <div class="space-y-4">
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-slate-700 mb-2"
                        >Category <span class="text-red-500">*</span></label
                      >
                      <select
                        v-model="form.category_id"
                        :class="[
                          'w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2',
                          errors.category_id
                            ? 'border-red-500 focus:ring-red-500'
                            : 'border-gray-200 focus:ring-orange-500',
                        ]"
                      >
                        <option :value="0">Select Category</option>
                        <option v-for="cat in shopStore.categories" :key="cat.id" :value="cat.id">
                          {{ cat.name }}
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
                        <option v-for="brand in shopStore.brands" :key="brand.id" :value="brand.id">
                          {{ brand.name }}
                        </option>
                      </select>
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2"
                      >Product Name <span class="text-red-500">*</span></label
                    >
                    <input
                      v-model="form.name"
                      type="text"
                      placeholder="Enter product name"
                      :class="[
                        'w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2',
                        errors.name
                          ? 'border-red-500 focus:ring-red-500'
                          : 'border-gray-200 focus:ring-orange-500',
                      ]"
                    />
                    <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name[0] }}</p>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2"
                      >Short Description</label
                    >
                    <input
                      v-model="form.short_description"
                      type="text"
                      placeholder="Brief summary..."
                      maxlength="150"
                      :class="[
                        'w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2',
                        errors.short_description
                          ? 'border-red-500 focus:ring-red-500'
                          : 'border-gray-200 focus:ring-orange-500',
                      ]"
                    />
                    <p class="text-xs text-slate-400 mt-1">
                      {{ form.short_description?.length || 0 }}/150 characters
                    </p>
                    <p v-if="errors.short_description" class="text-red-500 text-xs mt-1">
                      {{ errors.short_description[0] }}
                    </p>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2"
                      >Description <span class="text-red-500">*</span></label
                    >
                    <textarea
                      v-model="form.description"
                      rows="5"
                      placeholder="Enter detailed product description"
                      :class="[
                        'w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 resize-none',
                        errors.description
                          ? 'border-red-500 focus:ring-red-500'
                          : 'border-gray-200 focus:ring-orange-500',
                      ]"
                    ></textarea>
                    <p v-if="errors.description" class="text-red-500 text-xs mt-1">
                      {{ errors.description[0] }}
                    </p>
                  </div>
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
                        v-model="form.price"
                        type="number"
                        step="0.01"
                        min="0"
                        placeholder="0.00"
                        :class="[
                          'w-full pl-8 pr-4 py-3 border rounded-lg focus:outline-none focus:ring-2',
                          errors.price
                            ? 'border-red-500 focus:ring-red-500'
                            : 'border-gray-200 focus:ring-orange-500',
                        ]"
                      />
                    </div>
                    <p v-if="errors.price" class="text-red-500 text-xs mt-1">
                      {{ errors.price[0] }}
                    </p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2"
                      >Discount (%)</label
                    >
                    <div class="relative">
                      <input
                        v-model="form.discount"
                        type="number"
                        min="0"
                        max="100"
                        placeholder="0"
                        class="w-full px-4 pr-8 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
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
                        :value="calculatedFinalPrice"
                        type="text"
                        readonly
                        class="w-full pl-8 pr-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-slate-600 cursor-not-allowed"
                      />
                    </div>
                  </div>
                </div>
                <div class="mt-4">
                  <label class="block text-sm font-medium text-slate-700 mb-2">Shipping Cost</label>
                  <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">$</span>
                    <input
                      v-model="form.shipping"
                      type="number"
                      step="0.01"
                      min="0"
                      placeholder="0.00"
                      class="w-full pl-8 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
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
                  <div class="col-span-2">Price</div>
                  <div class="col-span-2">Stock</div>
                  <div class="col-span-1 text-center"></div>
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
                        <option v-for="size in availableSizes" :key="size" :value="size">
                          {{ size }}
                        </option>
                      </select>
                    </div>

                    <div class="col-span-2">
                      <select
                        v-model="variant.color"
                        class="w-full px-2 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:border-orange-500 bg-white"
                      >
                        <option value="" disabled>Color</option>
                        <option v-for="color in availableColors" :key="color" :value="color">
                          {{ color }}
                        </option>
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
                        min="0"
                        class="w-full px-2 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:border-orange-500"
                        placeholder="Qty"
                      />
                    </div>

                    <div class="col-span-1 text-center">
                      <button
                        type="button"
                        @click="removeVariantRow(index)"
                        class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-full transition-colors"
                        title="Remove Variant"
                        :disabled="form.variants.length === 1"
                        :class="{ 'opacity-50 cursor-not-allowed': form.variants.length === 1 }"
                      >
                        <i class="fa-solid fa-trash-can"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <div
                  v-if="form.variants.length === 0"
                  class="text-center py-4 text-gray-500 text-sm"
                >
                  Click "Add Option" to add variant.
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
                  accept="image/*"
                  multiple
                  @change="handleFileSelect"
                  class="hidden"
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
                <p v-if="errors.images" class="text-red-500 text-xs text-center mb-2">
                  {{ errors.images[0] }}
                </p>

                <div class="grid grid-cols-4 gap-2">
                  <div
                    v-for="(image, index) in form.images"
                    :key="index"
                    class="aspect-square rounded-lg bg-gray-100 relative overflow-hidden group"
                  >
                    <img
                      :src="image.preview"
                      :alt="'Image ' + (index + 1)"
                      class="w-full h-full object-cover"
                    />
                    <button
                      type="button"
                      @click="removeImage(index)"
                      class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity"
                    >
                      <i class="fa-solid fa-times"></i>
                    </button>
                    <span
                      v-if="index === 0"
                      class="absolute bottom-1 left-1 px-1.5 py-0.5 bg-orange-500 text-white text-xs rounded"
                      >Main</span
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
                <p class="text-xs text-slate-400 mt-2 text-center">
                  {{ form.images.length }}/4 images uploaded
                </p>
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
            </div>
          </div>

          <div
            class="fixed md:static bottom-0 left-0 right-0 bg-white md:bg-transparent border-t md:border-0 border-gray-200 p-4 md:p-0 md:mt-6 z-20"
          >
            <div
              class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3"
            >
              <button
                type="button"
                @click="router.push('/admin/products')"
                class="order-3 sm:order-1 px-4 sm:px-6 py-3 border border-gray-200 rounded-lg font-medium text-slate-600 hover:bg-gray-50 text-sm sm:text-base text-center"
              >
                Cancel
              </button>
              <button
                type="button"
                @click="
                  form.is_active = false;
                  submitForm();
                "
                class="order-2 px-4 sm:px-6 py-3 border border-gray-200 rounded-lg font-medium text-slate-600 hover:bg-gray-50 text-sm sm:text-base"
              >
                Save as Draft
              </button>
              <button
                type="submit"
                :disabled="isSubmitting"
                class="order-1 sm:order-3 px-4 sm:px-6 py-3 bg-orange-500 text-white rounded-lg font-medium hover:bg-orange-600 text-sm sm:text-base flex items-center justify-center gap-2"
              >
                <i v-if="isSubmitting" class="fa-solid fa-circle-notch fa-spin"></i>
                <span v-else><i class="fa-solid fa-plus mr-2"></i>Create Product</span>
              </button>
            </div>
          </div>
          <div class="h-36 md:hidden"></div>
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
