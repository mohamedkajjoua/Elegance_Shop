<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth/auth";
import AdminHeader from "@/components/admin/AdminHeader.vue";
import AdminNav from "@/components/admin/AdminNav.vue";

const sidebarOpen = ref(false);

const route = useRoute();
const router = useRouter();
const userStore = useAuthStore();
const userId = Number(route.params.id);

const form = ref({
  first_name: "",
  last_name: "",
  email: "",
  phone: "",
  role: "customer",
  avatar: null as File | null,
});

const avatarPreview = ref("");
const fileInput = ref<HTMLInputElement | null>(null);

onMounted(async () => {
  const res = await userStore.getUser(userId);
  if (res && res.success) {
    const user = res.data;

    form.value.first_name = user.first_name;
    form.value.last_name = user.last_name;
    form.value.email = user.email;
    form.value.phone = user.phone;
    form.value.role = user.role;

    if (user.avatar) {
      const baseUrl = "http://localhost:8000/storage/";
      avatarPreview.value = user.avatar.startsWith("http") ? user.avatar : baseUrl + user.avatar;
    } else {
      avatarPreview.value = `https://ui-avatars.com/api/?name=${user.first_name}+${user.last_name}&background=3B82F6&color=fff&size=96`;
    }
  } else {
    router.push("/admin/users");
  }
});

const triggerFileInput = () => fileInput.value?.click();

const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files[0]) {
    const file = target.files[0];
    form.value.avatar = file;
    avatarPreview.value = URL.createObjectURL(file);
  }
};

const handleUpdate = async () => {
  try {
    const res = await userStore.updateUser(userId, form.value);
    if (res.success) {
      router.push("/admin/users");
    } else {
      alert("Failed to update user");
    }
  } catch (e) {
    console.error(e);
  }
};

const handleDelete = async () => {
  if (confirm("Are you sure?")) {
    await userStore.deleteUser(userId);
    router.push("/admin/users");
  }
};
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex">
    <AdminNav :isOpen="sidebarOpen" @close="sidebarOpen = false" />

    <div class="flex-1 lg:ml-64">
      <AdminHeader title="EDIT USER" @toggle-sidebar="sidebarOpen = true">
        <div class="relative hidden md:block">
          <input
            type="text"
            placeholder="Search..."
            class="w-32 lg:w-48 pl-10 pr-4 py-2 bg-gray-100 rounded-lg text-sm"
          />
          <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
      </AdminHeader>

      <main class="p-4 md:p-6">
        <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
          <router-link to="/admin/users" class="hover:text-orange-500">Users</router-link>
          <i class="fa-solid fa-chevron-right text-xs"></i>
          <span class="text-slate-800 font-medium">Edit User</span>
        </div>

        <div class="max-w-2xl">
          <div class="bg-white rounded-xl p-6 shadow-sm mb-6">
            <div class="flex items-center gap-6">
              <div
                class="w-24 h-24 rounded-full bg-blue-500 overflow-hidden relative border-2 border-white shadow"
              >
                <img :src="avatarPreview" alt="User Avatar" class="w-full h-full object-cover" />
              </div>
              <div>
                <h3 class="text-xl font-semibold text-slate-800">
                  {{ form.first_name }} {{ form.last_name }}
                </h3>
                <p class="text-slate-500 mt-1">{{ form.email }}</p>

                <input
                  type="file"
                  ref="fileInput"
                  class="hidden"
                  accept="image/*"
                  @change="handleFileChange"
                />

                <button
                  @click="triggerFileInput"
                  class="mt-3 px-4 py-2 text-sm bg-gray-100 text-slate-600 rounded-lg hover:bg-gray-200"
                >
                  <i class="fa-solid fa-camera mr-2"></i> Change Avatar
                </button>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-800 mb-6">User Information</h3>

            <form @submit.prevent="handleUpdate">
              <div class="grid grid-cols-2 gap-5">
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">First Name</label>
                  <input
                    v-model="form.first_name"
                    type="text"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">Last Name</label>
                  <input
                    v-model="form.last_name"
                    type="text"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                  <input
                    v-model="form.email"
                    type="email"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">Phone Number</label>
                  <input
                    v-model="form.phone"
                    type="tel"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-2">Role</label>
                  <select
                    v-model="form.role"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  >
                    <option value="admin">Admin</option>
                    <option value="editor">Editor</option>
                    <option value="customer">Customer</option>
                  </select>
                </div>
              </div>

              <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-100">
                <button
                  type="button"
                  @click="handleDelete"
                  class="px-4 py-2 text-red-500 hover:bg-red-50 rounded-lg font-medium"
                >
                  <i class="fa-solid fa-trash mr-2"></i> Delete User
                </button>
                <div class="flex gap-3">
                  <router-link
                    to="/admin/users"
                    class="px-6 py-3 border border-gray-200 rounded-lg font-medium text-slate-600 hover:bg-gray-50"
                  >
                    Cancel
                  </router-link>

                  <button
                    type="submit"
                    :disabled="userStore.isLoading"
                    class="px-6 py-3 bg-orange-500 text-white rounded-lg font-medium hover:bg-orange-600 disabled:opacity-70 flex items-center gap-2"
                  >
                    <span v-if="userStore.isLoading"
                      ><i class="fa-solid fa-circle-notch fa-spin"></i> Saving...</span
                    >
                    <span v-else><i class="fa-solid fa-save mr-2"></i> Save Changes</span>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>
