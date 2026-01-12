<script setup lang="ts">
import { computed, onMounted, ref, watch } from "vue";
import AdminHeader from "@/components/admin/AdminHeader.vue";
import AdminNav from "@/components/admin/AdminNav.vue";
import { useAuthStore } from "@/stores/auth/auth";
import { storeToRefs } from "pinia";
import { useRoute, useRouter } from "vue-router";

const sidebarOpen = ref(false);
const route = useRoute();
const router = useRouter();

const userStore = useAuthStore();

onMounted(async () => {
  const page = route.query.page ? Number(route.query.page) : 1;
  await userStore.fetchUsersList(page);
});

// pagination
const { meta } = storeToRefs(userStore);

watch(
  () => route.query.page,
  (newPage) => {
    const page = newPage ? Number(newPage) : 1;
    userStore.fetchUsersList(page);
  }
);

const displayedPages = computed(() => {
  const current = meta.value.current_page;
  const last = meta.value.last_page;
  const delta = 1;
  const range = [];
  const rangeWithDots: (number | string)[] = [];
  let l;

  range.push(1);
  for (let i = current - delta; i <= current + delta; i++) {
    if (i < last && i > 1) {
      range.push(i);
    }
  }
  range.push(last);

  for (let i of range) {
    if (l) {
      if (i - l === 2) {
        rangeWithDots.push(l + 1);
      } else if (i - l !== 1) {
        rangeWithDots.push("...");
      }
    }
    rangeWithDots.push(i);
    l = i;
  }
  return rangeWithDots;
});

const changePage = (page: number) => {
  if (page < 1 || page > meta.value.last_page || page === meta.value.current_page) {
    return;
  }
  router.push({
    query: { ...route.query, page: page },
  });
  window.scrollTo({ top: 0, behavior: "smooth" });
};

const formatDate = (dateString: string) => {
  if (!dateString) return "N/A";
  return new Date(dateString).toLocaleDateString("en-US", { month: "short", year: "numeric" });
};
const getRoleClass = (role: any) => {
  switch (role) {
    case "admin":
      return "bg-purple-100 text-red-600";
    case "editor":
      return "bg-blue-100 text-blue-600";
    default:
      return "bg-gray-100 text-purple-600";
  }
};
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    <AdminNav :isOpen="sidebarOpen" @close="sidebarOpen = false" />

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64">
      <!-- Header -->
      <AdminHeader title="USER MANAGEMENT" @toggle-sidebar="sidebarOpen = true">
        <div class="relative hidden md:block">
          <input
            type="text"
            placeholder="Search..."
            class="w-32 lg:w-48 pl-10 pr-4 py-2 bg-gray-100 rounded-lg text-sm"
          />
          <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
      </AdminHeader>
      <div v-if="userStore.isLoading" class="text-center py-10">
        <i class="fa-solid fa-spinner fa-spin text-3xl text-orange-500"></i>
      </div>

      <main v-else class="p-3 md:p-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 md:mb-6">
          <div>
            <h2 class="text-lg md:text-2xl font-bold text-slate-800">User List</h2>
            <p class="text-xs md:text-sm text-slate-500 mt-1">Manage all registered users</p>
          </div>
        </div>

        <!-- Mobile Cards View -->
        <div class="md:hidden space-y-3">
          <!-- User Card -->
          <div
            v-for="user in userStore.allUsers"
            :key="user.id"
            class="bg-white rounded-xl p-4 shadow-sm"
          >
            <div class="flex items-start justify-between mb-3">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-blue-500 overflow-hidden flex-shrink-0">
                  <img
                    :src="
                      user.avatar
                        ? `http://localhost:8000/storage/${user.avatar}`
                        : `https://ui-avatars.com/api/?name=${user.first_name}+${user.last_name}&background=3B82F6&color=fff`
                    "
                    :alt="`${user.first_name} ${user.last_name}`"
                  />
                </div>
                <div>
                  <p class="font-semibold text-slate-800">
                    {{ user.first_name }} {{ user.last_name }}
                  </p>
                  <p class="text-xs text-slate-400">Joined {{ formatDate(user.created_at) }}</p>
                </div>
              </div>
              <span
                :class="['px-2 py-1 rounded-full text-xs font-medium', getRoleClass(user.role)]"
              >
                {{ user.role }}
              </span>
            </div>
            <div class="space-y-2 text-sm">
              <div class="flex items-center gap-2 text-slate-600">
                <i class="fa-solid fa-envelope w-4 text-slate-400 text-xs"></i>
                <span class="truncate">{{ user.email }}</span>
              </div>
              <div class="flex items-center gap-2 text-slate-600">
                <i class="fa-solid fa-phone w-4 text-slate-400 text-xs"></i>
                <span>{{ user.phone }}</span>
              </div>
            </div>
            <div class="flex items-center justify-end gap-2 mt-3 pt-3 border-t border-gray-100">
              <router-link
                to="/admin/users/edit"
                class="px-3 py-2 rounded-lg border border-gray-200 text-blue-500 hover:bg-blue-50 flex items-center gap-1 text-sm"
              >
                <i class="fa-solid fa-pen text-xs"></i>
                <span>Edit</span>
              </router-link>
              <button
                @click="userStore.deleteUser(user.id)"
                class="px-3 py-2 rounded-lg border border-gray-200 text-red-500 hover:bg-red-50 flex items-center gap-1 text-sm"
              >
                <i class="fa-solid fa-trash text-xs"></i>
                <span>Delete</span>
              </button>
            </div>
          </div>

          <!-- Mobile Pagination -->
          <div
            v-if="meta.total > 0"
            class="bg-white rounded-xl p-4 shadow-sm flex items-center justify-between"
          >
            <p class="text-xs text-slate-500">
              Showing {{ (meta.current_page - 1) * meta.per_page + 1 }}-{{
                Math.min(meta.current_page * meta.per_page, meta.total)
              }}
              of {{ meta.total }}
            </p>
            <div class="flex items-center gap-1">
              <button
                @click="changePage(meta.current_page - 1)"
                :disabled="meta.current_page === 1"
                class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <i class="fa-solid fa-chevron-left text-xs"></i>
              </button>
              <button
                v-for="page in meta.last_page"
                :key="page"
                @click="changePage(page)"
                :class="[
                  'w-8 h-8 rounded-lg flex items-center justify-center text-sm',
                  meta.current_page === page
                    ? 'bg-orange-500 text-white'
                    : 'border border-gray-200 text-slate-500 hover:bg-gray-100',
                ]"
              >
                {{ page }}
              </button>
              <button
                @click="changePage(meta.current_page + 1)"
                :disabled="meta.current_page === meta.last_page"
                class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <i class="fa-solid fa-chevron-right text-xs"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block bg-white rounded-xl shadow-sm overflow-hidden">
          <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="px-6 py-4 text-left">
                  <input type="checkbox" class="w-4 h-4 rounded border-gray-300" />
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">
                  User
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">
                  Email
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">
                  Phone
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase">
                  Role
                </th>
                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase">
                  Action
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr v-for="user in userStore.allUsers" :key="user.id" class="hover:bg-gray-50">
                <td class="px-6 py-4">
                  <input type="checkbox" class="w-4 h-4 rounded border-gray-300" />
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-500 overflow-hidden flex-shrink-0">
                      <img
                        :src="
                          user.avatar
                            ? `http://localhost:8000/storage/${user.avatar}`
                            : `https://ui-avatars.com/api/?name=${user.first_name}+${user.last_name}&background=3B82F6&color=fff`
                        "
                        :alt="`${user.first_name} ${user.last_name}`"
                      />
                    </div>
                    <div>
                      <p class="font-medium text-slate-800">
                        {{ user.first_name }} {{ user.last_name }}
                      </p>
                      <p class="text-xs text-slate-400">Joined {{ formatDate(user.created_at) }}</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">{{ user.email }}</td>
                <td class="px-6 py-4 text-sm text-slate-600">{{ user.phone }}</td>
                <td class="px-6 py-4">
                  <span
                    :class="['px-2 py-1 rounded-full text-xs font-medium', getRoleClass(user.role)]"
                  >
                    {{ user.role }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-center gap-2">
                    <router-link
                      :to="`/admin/users/${user.id}/edit`"
                      class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-blue-500 hover:bg-blue-50"
                    >
                      <i class="fa-solid fa-pen text-sm"></i>
                    </router-link>
                    <button
                      @click="userStore.deleteUser(user.id)"
                      class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-red-500 hover:bg-red-50"
                    >
                      <i class="fa-solid fa-trash text-sm"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Desktop Pagination -->
          <div
            v-if="meta.total > 0"
            class="px-4 md:px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-gray-100"
          >
            <p class="text-sm text-slate-500">
              Showing
              <span class="font-medium text-slate-900">{{
                (meta.current_page - 1) * meta.per_page + 1
              }}</span>
              to
              <span class="font-medium text-slate-900">{{
                Math.min(meta.current_page * meta.per_page, meta.total)
              }}</span>
              of
              <span class="font-medium text-slate-900">{{ meta.total }}</span>
              entries
            </p>

            <div class="flex items-center gap-1 md:gap-2">
              <button
                @click="changePage(meta.current_page - 1)"
                :disabled="meta.current_page === 1"
                :class="[
                  'w-8 h-8 rounded-lg border flex items-center justify-center transition-colors',
                  meta.current_page === 1
                    ? 'border-gray-100 text-gray-300 cursor-not-allowed'
                    : 'border-gray-200 text-slate-500 hover:bg-gray-100',
                ]"
              >
                <i class="fa-solid fa-chevron-left text-xs"></i>
              </button>

              <template v-for="(page, index) in displayedPages" :key="index">
                <button
                  v-if="page !== '...'"
                  @click="changePage(Number(page))"
                  :class="[
                    'w-8 h-8 rounded-lg flex items-center justify-center text-sm font-medium transition-all',
                    meta.current_page === page
                      ? 'bg-orange-500 text-white shadow-sm'
                      : 'border border-gray-200 text-slate-500 hover:bg-gray-100',
                  ]"
                >
                  {{ page }}
                </button>
                <span
                  v-else
                  class="w-8 h-8 flex items-center justify-center text-slate-400 font-bold pb-2"
                >
                  ...
                </span>
              </template>

              <button
                @click="changePage(meta.current_page + 1)"
                :disabled="meta.current_page === meta.last_page"
                :class="[
                  'w-8 h-8 rounded-lg border flex items-center justify-center transition-colors',
                  meta.current_page === meta.last_page
                    ? 'border-gray-100 text-gray-300 cursor-not-allowed'
                    : 'border-gray-200 text-slate-500 hover:bg-gray-100',
                ]"
              >
                <i class="fa-solid fa-chevron-right text-xs"></i>
              </button>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>
