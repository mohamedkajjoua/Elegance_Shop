<script setup>
import { ref, onMounted } from "vue";
// 1. قمنا بإلغاء استدعاء userStore واستدعينا authStore فقط
import { useAuthStore } from "@/stores/auth/auth";
import Breadcrumb from "@/components/layout/Breadcrumb.vue";

// 2. قمنا بتعيين المتغير userStore ليحمل قيمة useAuthStore
// هذا يضمن أن باقي الكود والـ Template يعملون بدون تغييرات كثيرة
const userStore = useAuthStore();

const breadcrumbItems = [{ label: "My Profile" }];

const isEditing = ref(false);
const photoInput = ref(null);

// متغير لعرض الصورة (المعاينة)
const avatarPreview = ref("");

// دالة تهيئة النموذج
const initForm = () => {
  const user = userStore.user || {}; // تجنب الخطأ إذا لم تكن البيانات قد وصلت

  // إعداد رابط المعاينة (Preview)
  if (user.avatar) {
    avatarPreview.value = user.avatar.startsWith("http")
      ? user.avatar
      : `http://localhost:8000/storage/${user.avatar}`;
  } else {
    avatarPreview.value = `https://ui-avatars.com/api/?name=${user.first_name || "User"}`;
  }

  return {
    first_name: user.first_name || "",
    last_name: user.last_name || "",
    email: user.email || "",
    phone: user.phone || "",
    // 3. هنا طلبك: وضع الصورة الحالية في النموذج مبدئياً
    // ملاحظة: عند التعديل سيتم استبدال هذه القيمة بملف (File)
    avatar: user.avatar,
  };
};

const editForm = ref({});

// عند التحميل، املأ النموذج
onMounted(() => {
  if (userStore.user) {
    editForm.value = initForm();
  }
});

// دالة الحفظ الموحدة (بيانات + صورة)
async function toggleEdit() {
  if (isEditing.value) {
    try {
      // إرسال البيانات (بما فيها الصورة إذا تغيرت) إلى دالة التحديث في AuthStore
      const res = await userStore.updateUser(userStore.user.id, editForm.value);

      if (res && res.success) {
        alert("Profile saved successfully!");
        isEditing.value = false;
        // تحديث البيانات في الواجهة
        editForm.value = initForm();
      } else {
        alert(res?.message || "Failed to update profile");
      }
    } catch (e) {
      console.error(e);
      alert("Error saving profile");
    }
  } else {
    // تفعيل وضع التعديل
    editForm.value = initForm();
    isEditing.value = true;
  }
}

function cancelEdit() {
  editForm.value = initForm(); // التراجع عن التغييرات
  isEditing.value = false;
}

// التعامل مع اختيار ملف الصورة
function triggerPhotoUpload() {
  // إذا لم يكن في وضع التعديل، قم بتفعيله أولاً ليتمكن من الحفظ
  if (!isEditing.value) {
    toggleEdit();
  }
  // فتح نافذة اختيار الملفات
  setTimeout(() => photoInput.value?.click(), 0);
}

function handlePhotoChange(event) {
  const file = event.target.files[0];
  if (!file) return;

  // التحقق من الملف
  if (!file.type.startsWith("image/")) {
    alert("Please select an image file");
    return;
  }
  if (file.size > 5 * 1024 * 1024) {
    // 5MB limit
    alert("Image size should be less than 5MB");
    return;
  }

  // 4. وضع الملف الجديد داخل النموذج بدلاً من الرابط النصي القديم
  editForm.value.avatar = file;

  // تحديث المعاينة فوراً ليراها المستخدم
  avatarPreview.value = URL.createObjectURL(file);
}

// --- قسم العناوين (Addresses) ---
// ملاحظة: بما أننا نستخدم useAuthStore الآن
// يجب التأكد أن الدوال (addAddress, updateAddress, removeAddress) موجودة في ملف auth.ts
// أو يمكنك نقل منطق العناوين هنا مباشرة باستخدام axios إذا لم تكن موجودة في الستور.

const showAddressForm = ref(false);
const editingAddressId = ref(null);
const addressForm = ref({
  label: "",
  recipient: "",
  phone: "",
  street: "",
  city: "",
  state: "",
  zipCode: "",
  country: "",
});

function toggleAddressForm() {
  showAddressForm.value = !showAddressForm.value;
  if (!showAddressForm.value) resetAddressForm();
}

function resetAddressForm() {
  editingAddressId.value = null;
  addressForm.value = {
    label: "",
    recipient: "",
    phone: "",
    street: "",
    city: "",
    state: "",
    zipCode: "",
    country: "",
  };
}

function editAddress(address) {
  editingAddressId.value = address.id;
  addressForm.value = { ...address };
  showAddressForm.value = true;
}

async function saveAddress() {
  // التحقق من الحقول المطلوبة
  if (!addressForm.value.label || !addressForm.value.street) {
    alert("Please fill required fields");
    return;
  }

  const addressData = {
    ...addressForm.value,
    recipient: addressForm.value.recipient || userStore.user.first_name,
    phone: addressForm.value.phone || userStore.user.phone,
    text: `${addressForm.value.street}, ${addressForm.value.city}`,
  };

  // استدعاء الدوال من AuthStore (تأكد من وجودها هناك)
  if (editingAddressId.value) {
    if (userStore.updateAddress) await userStore.updateAddress(editingAddressId.value, addressData);
  } else {
    if (userStore.addAddress) await userStore.addAddress(addressData);
  }

  showAddressForm.value = false;
  resetAddressForm();
}
</script>

<template>
  <div class="pb-12">
    <Breadcrumb :items="breadcrumbItems" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
      <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl p-6 sm:p-8 mb-6">
          <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6"
          >
            <h2 class="text-xl sm:text-2xl font-bold">Personal Information</h2>
            <div class="flex gap-2">
              <button
                v-if="isEditing"
                class="px-4 py-2 border border-border rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors"
                @click="cancelEdit"
              >
                Cancel
              </button>
              <button
                class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors"
                @click="toggleEdit"
              >
                {{ isEditing ? "Save Changes" : "Edit Profile" }}
              </button>
            </div>
          </div>

          <input
            ref="photoInput"
            type="file"
            accept="image/*"
            class="hidden"
            @change="handlePhotoChange"
          />

          <div class="flex items-center gap-4 sm:gap-6 mb-8">
            <div class="relative group">
              <img
                :src="avatarPreview"
                class="w-20 h-20 sm:w-28 sm:h-28 rounded-full object-cover border-4 border-white shadow-lg"
              />

              <div
                class="absolute inset-0 bg-black/50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 cursor-pointer"
                @click="triggerPhotoUpload"
              >
                <i class="fa-solid fa-camera text-white"></i>
              </div>
            </div>

            <div>
              <h3 class="text-lg sm:text-xl font-bold">
                {{ userStore.user?.first_name }} {{ userStore.user?.last_name }}
              </h3>
              <p class="text-text-light text-sm sm:text-base">{{ userStore.user?.email }}</p>

              <button
                class="mt-2 text-primary text-sm font-medium hover:underline"
                @click="triggerPhotoUpload"
              >
                <i class="fa-solid fa-camera mr-1"></i> Change Photo
              </button>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
            <div>
              <label class="block text-sm font-medium text-text-light mb-2">First Name</label>
              <input
                v-model="editForm.first_name"
                type="text"
                :readonly="!isEditing"
                :class="[
                  'w-full py-3 text-text font-medium transition-all',
                  isEditing
                    ? 'px-4 border border-border rounded-xl focus:border-primary outline-none'
                    : 'bg-transparent border-none',
                ]"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-text-light mb-2">Last Name</label>
              <input
                v-model="editForm.last_name"
                type="text"
                :readonly="!isEditing"
                :class="[
                  'w-full py-3 text-text font-medium transition-all',
                  isEditing
                    ? 'px-4 border border-border rounded-xl focus:border-primary outline-none'
                    : 'bg-transparent border-none',
                ]"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-text-light mb-2">Email</label>
              <input
                v-model="editForm.email"
                type="email"
                :readonly="!isEditing"
                :class="[
                  'w-full py-3 text-text font-medium transition-all',
                  isEditing
                    ? 'px-4 border border-border rounded-xl focus:border-primary outline-none'
                    : 'bg-transparent border-none',
                ]"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-text-light mb-2">Phone</label>
              <input
                v-model="editForm.phone"
                type="tel"
                :readonly="!isEditing"
                :class="[
                  'w-full py-3 text-text font-medium transition-all',
                  isEditing
                    ? 'px-4 border border-border rounded-xl focus:border-primary outline-none'
                    : 'bg-transparent border-none',
                ]"
              />
            </div>
          </div>
        </div>
        <div class="bg-white rounded-2xl p-6 sm:p-8">
          <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6"
          >
            <h2 class="text-xl sm:text-2xl font-bold">Saved Addresses</h2>
            <button
              class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors"
              @click="toggleAddressForm"
            >
              <i :class="['fa-solid mr-2', showAddressForm ? 'fa-xmark' : 'fa-plus']"></i>
              {{ showAddressForm ? "Cancel" : "Add New" }}
            </button>
          </div>

          <div
            v-if="showAddressForm"
            class="mb-6 p-4 sm:p-6 bg-background rounded-xl border border-border"
          >
            <h3 class="font-bold mb-4">
              {{ editingAddressId ? "Edit Address" : "Add New Address" }}
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-text-light mb-2">Label *</label>
                <input
                  v-model="addressForm.label"
                  type="text"
                  placeholder="e.g., Home, Office, Parent's House"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary outline-none text-sm"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-text-light mb-2">Recipient Name</label>
                <input
                  v-model="addressForm.recipient"
                  type="text"
                  :placeholder="userStore.user?.first_name"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary outline-none text-sm"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-text-light mb-2">Phone</label>
                <input
                  v-model="addressForm.phone"
                  type="tel"
                  :placeholder="userStore.user?.phone"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary outline-none text-sm"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-text-light mb-2">Country</label>
                <input
                  v-model="addressForm.country"
                  type="text"
                  placeholder="e.g., United States"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary outline-none text-sm"
                />
              </div>
              <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-text-light mb-2"
                  >Street Address *</label
                >
                <input
                  v-model="addressForm.street"
                  type="text"
                  placeholder="e.g., 123 Main Street, Apt 4B"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary outline-none text-sm"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-text-light mb-2">City *</label>
                <input
                  v-model="addressForm.city"
                  type="text"
                  placeholder="e.g., New York"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary outline-none text-sm"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-text-light mb-2"
                  >State / Province</label
                >
                <input
                  v-model="addressForm.state"
                  type="text"
                  placeholder="e.g., NY"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary outline-none text-sm"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-text-light mb-2"
                  >ZIP / Postal Code</label
                >
                <input
                  v-model="addressForm.zipCode"
                  type="text"
                  placeholder="e.g., 10001"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary outline-none text-sm"
                />
              </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
              <button
                class="px-6 py-2.5 border border-border rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors"
                @click="toggleAddressForm"
              >
                Cancel
              </button>
              <button
                class="px-6 py-2.5 bg-primary text-white rounded-xl text-sm font-medium hover:bg-primary-dark transition-colors"
                @click="saveAddress"
              >
                <i class="fa-solid fa-check mr-2"></i
                >{{ editingAddressId ? "Update Address" : "Save Address" }}
              </button>
            </div>
          </div>

          <div v-if="userStore.addresses && userStore.addresses.length > 0">
            <p class="text-sm text-gray-500">Addresses loaded from store...</p>
          </div>
          <div v-else class="text-center py-4 text-gray-400">No addresses saved yet.</div>
        </div>
      </div>

      <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl p-6">
          <h3 class="font-bold mb-4">Quick Links</h3>
          <div class="space-y-2">
            <router-link
              to="/orders"
              class="flex items-center gap-3 p-3 rounded-lg text-text-light hover:bg-background hover:text-text transition-colors"
            >
              <i class="fa-solid fa-box w-5"></i> My Orders
            </router-link>
            <router-link
              to="/wishlist"
              class="flex items-center gap-3 p-3 rounded-lg text-text-light hover:bg-background hover:text-text transition-colors"
            >
              <i class="fa-solid fa-heart w-5"></i> Wishlist
            </router-link>
            <router-link
              to="/cart"
              class="flex items-center gap-3 p-3 rounded-lg text-text-light hover:bg-background hover:text-text transition-colors"
            >
              <i class="fa-solid fa-cart-shopping w-5"></i> Shopping Cart
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
