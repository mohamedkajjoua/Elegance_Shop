<script setup>
import { ref, onMounted } from "vue";

import { useAuthStore } from "@/stores/auth/auth";
import Breadcrumb from "@/components/layout/Breadcrumb.vue";
import { useAddressStore } from "@/stores/user/AddressStore";

const userStore = useAuthStore();
const addressStore = useAddressStore();

const breadcrumbItems = [{ label: "My Profile" }];

const isEditing = ref(false);
const photoInput = ref(null);
const avatarPreview = ref("");

const initForm = () => {
  const user = userStore.user || {};

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
    avatar: user.avatar,
  };
};

const editForm = ref({});
onMounted(async () => {
  if (userStore.user) {
    editForm.value = initForm();
  }

  await addressStore.fetchAddresses();
});

async function toggleEdit() {
  if (isEditing.value) {
    try {
      const res = await userStore.updateUser(userStore.user.id, editForm.value);

      if (res && res.success) {
        alert("Profile saved successfully!");
        isEditing.value = false;

        editForm.value = initForm();
      } else {
        alert(res?.message || "Failed to update profile");
      }
    } catch (e) {
      console.error(e);
      alert("Error saving profile");
    }
  } else {
    editForm.value = initForm();
    isEditing.value = true;
  }
}

function cancelEdit() {
  editForm.value = initForm();
  isEditing.value = false;
}

function triggerPhotoUpload() {
  if (!isEditing.value) {
    toggleEdit();
  }
  setTimeout(() => photoInput.value?.click(), 0);
}

function handlePhotoChange(event) {
  const file = event.target.files[0];
  if (!file) return;

  if (!file.type.startsWith("image/")) {
    alert("Please select an image file");
    return;
  }
  if (file.size > 5 * 1024 * 1024) {
    alert("Image size should be less than 5MB");
    return;
  }

  editForm.value.avatar = file;
  avatarPreview.value = URL.createObjectURL(file);
}

const showAddressForm = ref(false);
const editingAddressId = ref(null);
const addressForm = ref({
  label: "",
  phone: "",
  street: "",
  city: "",
  state: "",
  post_code: "",
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
    phone: "",
    street: "",
    city: "",
    state: "",
    post_code: "",
    country: "",
  };
}

function editAddress(address) {
  editingAddressId.value = address.id;
  addressForm.value = { ...address };
  showAddressForm.value = true;
}

async function saveAddress() {
  if (!addressForm.value.label || !addressForm.value.street || !addressForm.value.city) {
    alert("Please fill required fields (Label, Street, and City)");
    return;
  }

  const addressData = {
    ...addressForm.value,
    recipient: addressForm.value.recipient || userStore.user?.first_name || "User",
    phone: addressForm.value.phone || userStore.user?.phone,
  };

  const isUpdating = editingAddressId.value;

  try {
    if (isUpdating) {
      await addressStore.updateAddress(editingAddressId.value, addressData);
    } else {
      await addressStore.addAddress(addressData);
    }

    if (!addressStore.error) {
      alert(isUpdating ? "Address Updated Successfully!" : "Address Created Successfully!");

      showAddressForm.value = false;
      resetAddressForm();
    } else {
      alert(addressStore.error);
    }
  } catch (e) {
    console.error(e);
    alert("Something went wrong");
  }
}

async function deleteAddress(id) {
  if (!confirm("Are you sure you want to delete this address?")) {
    return;
  }

  try {
    await addressStore.deleteAddress(id);
  } catch (error) {
    console.error(error);
    alert("Failed to delete address");
  }
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

        <div class="bg-white rounded-2xl p-6 sm:p-8 mt-6">
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
                  >ZIP / Postal Code</label
                >
                <input
                  v-model="addressForm.post_code"
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
                <i v-if="addressStore.isLoading" class="fa-solid fa-spinner fa-spin mr-2"></i>
                <i v-else class="fa-solid fa-check mr-2"></i>
                {{ editingAddressId ? "Update Address" : "Save Address" }}
              </button>
            </div>
          </div>

          <div v-if="addressStore.addresses && addressStore.addresses.length > 0" class="space-y-4">
            <div
              v-for="address in addressStore.addresses"
              :key="address.id"
              class="flex flex-col sm:flex-row items-start gap-4 p-4 border border-border rounded-xl"
            >
              <div
                class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center text-primary shrink-0"
              >
                <i class="fa-solid fa-location-dot"></i>
              </div>
              <div class="flex-1 min-w-0">
                <h4 class="font-bold mb-1">{{ address.label }}</h4>
                <p class="text-sm text-text-light">{{ address.recipient }} - {{ address.phone }}</p>
                <p class="text-sm text-text-light mt-1 break-words">
                  {{ address.street }}, {{ address.city }}, {{ address.country }}
                </p>
              </div>
              <div class="flex gap-2 shrink-0">
                <button
                  class="w-8 h-8 border border-border rounded-lg flex items-center justify-center text-text-light hover:text-primary hover:border-primary transition-colors"
                  @click="editAddress(address)"
                >
                  <i class="fa-solid fa-pen text-xs"></i>
                </button>
                <button
                  class="w-8 h-8 border border-border rounded-lg flex items-center justify-center text-text-light hover:text-danger hover:border-danger transition-colors"
                  @click="deleteAddress(address.id)"
                >
                  <i class="fa-solid fa-trash text-xs"></i>
                </button>
              </div>
            </div>
          </div>

          <div v-else-if="!showAddressForm" class="text-center py-8 text-text-light">
            <i class="fa-solid fa-location-dot text-4xl mb-4 opacity-30"></i>
            <p>No saved addresses yet</p>
            <button class="mt-4 text-primary hover:underline" @click="showAddressForm = true">
              Add your first address
            </button>
          </div>
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
