import authService from "@/services/AuthService";
import type { ILoginRequest, IRegisterRequest, IUser } from "@/types/authTypes";
import { defineStore } from "pinia";
import { computed, ref } from "vue";

export const useAuthStore = defineStore("auth", () => {
  const user = ref<IUser | null>(authService.getStoredUser());
  const token = ref<string | null>(authService.getToken());
  const permissions = ref<string[]>(authService.getStoredPermissions());
  const permissionsByModule = ref<Record<string, string[]>>(
    authService.getStoredPermissionsByModule()
  );

  const isLoading = ref(false);
  const error = ref<string | null>(null);
  const success = ref<string | null>(null);
  const showPassword = ref(false);
  const allUsers = ref<IUser[]>([]);
  const meta = ref({
    current_page: 1,
    last_page: 1,
    total: 0,
    per_page: 10,
  });

  // Is the user logged in?
  const isAuthenticated = computed(() => !!token.value && !!user.value);
  // full name

  const fullName = computed(() =>
    user.value ? `${user.value.first_name} ${user.value.last_name}` : ""
  );
  // Is the user admin
  const isAdmin = computed(() => user.value?.role === "admin");

  async function register(data: IRegisterRequest) {
    isLoading.value = true;
    error.value = null;
    success.value = null;

    try {
      const res = await authService.register(data);
      if (res.success && res.user && res.token) {
        user.value = res.user;
        token.value = res.token;
        permissions.value = res.permissions || [];
        permissionsByModule.value = res.permissions_by_module || {};

        success.value = res.message || "Registration successful";
      } else {
        if (res.errors) {
          error.value = "Please fix the errors below";
        } else {
          error.value = res.message || "Registration failed";
        }
      }
      return res;
    } catch (err: any) {
      error.value = "An unexpected error occurred";
      throw err;
    } finally {
      isLoading.value = false;
    }
  }

  async function login(data: ILoginRequest) {
    isLoading.value = true;
    error.value = null;

    try {
      const res = await authService.login(data);

      if (res.success && res.user && res.token) {
        user.value = res.user;
        token.value = res.token;
        permissions.value = res.permissions || [];
        permissionsByModule.value = res.permissions_by_module || {};
      } else {
        if (res.errors) {
          error.value = "Please fix the errors below";
        } else {
          error.value = res.message || "Login failed";
        }
      }
      return res;
    } catch (err: any) {
    } finally {
      isLoading.value = false;
    }
  }

  async function logout() {
    isLoading.value = true;
    try {
      await authService.logout();
    } finally {
      clearAuth();
      isLoading.value = false;
    }
  }

  async function loadCurrentUser() {
    if (!token.value) return false;

    isLoading.value = true;
    try {
      const res = await authService.getCurrentUser();
      if (res.success && res.user) {
        user.value = res.user;
        permissions.value = res.permissions || [];
        permissionsByModule.value = res.permissions_by_module || {};
        return true;
      }
      clearAuth();
      return false;
    } catch (err: any) {
      clearAuth();
      return false;
    } finally {
      isLoading.value = false;
    }
  }
  async function getUser(id: number) {
    isLoading.value = true;
    try {
      const res = await authService.getUserById(id);
      return res;
    } catch (err) {
      console.error("Failed to fetch user", err);
      return null;
    } finally {
      isLoading.value = false;
    }
  }
  async function fetchUsersList(page = 1) {
    isLoading.value = true;
    error.value = null;

    try {
      const res = await authService.getAllUsers(page);

      if (res.success && res.data) {
        allUsers.value = res.data.data || [];

        meta.value = {
          current_page: res.data.current_page || 1,
          last_page: res.data.last_page || 1,
          total: res.data.total || 0,
          per_page: res.data.per_page || 10,
        };
      }

      return res;
    } catch (err) {
      error.value = "Failed to load users";
    } finally {
      isLoading.value = false;
    }
  }
  async function deleteUser(id: number) {
    isLoading.value = true;
    error.value = null;
    confirm("Are you sure you want to delete this category?");

    try {
      const res = await authService.delete(id);

      if (res.success) {
        allUsers.value = allUsers.value.filter((user) => user.id !== id);

        if (meta.value.total > 0) {
          meta.value.total--;
        }

        console.log("User deleted successfully");
      }
      fetchUsersList();
    } catch (err: any) {
      error.value = err?.response?.data?.message || "Failed to delete user";
      console.error("failed to delete user:", error.value);
    } finally {
      isLoading.value = false;
    }
  }

  async function updateUser(id: number, data: any) {
    isLoading.value = true;
    try {
      const formData = new FormData();
      formData.append("_method", "PUT");

      Object.keys(data).forEach((key) => {
        const value = data[key];

        // ✅ هذا الشرط سيحل التضارب بين الأدمن والبروفايل:
        // إذا كان الحقل هو avatar والقيمة نصية (String)، فهذا يعني أنها الصورة القديمة
        // لذا "نتجاهلها" ولا نرسلها، لكي لا يرفضها السيرفر (Validation Error 422)
        if (key === "avatar" && typeof value === "string") {
          return;
        }

        if (value !== null && value !== undefined) {
          formData.append(key, value);
        }
      });

      const res = await authService.updateUser(id, formData);

      if (res.success) {
        // 1. تحديث القائمة للأدمن
        const index = allUsers.value.findIndex((u) => u.id === id);
        if (index !== -1) {
          allUsers.value[index] = { ...allUsers.value[index], ...res.data };
        }

        if (user.value && user.value.id === id) {
          user.value = { ...user.value, ...res.data };
        }
      }
      return res;
    } catch (err: any) {
      console.error("Update User Error:", err);
      throw err;
    } finally {
      isLoading.value = false;
    }
  }

  //Token renewal
  async function refreshToken() {
    try {
      const response = await authService.refreshToken();
      if (response.success && response.token) {
        token.value = response.token;
        return true;
      }
      return false;
    } catch (err) {
      return false;
    }
  }

  function clearAuth() {
    user.value = null;
    token.value = null;
    permissions.value = [];
    permissionsByModule.value = {};
    error.value = null;
  }

  /*   
 //Helpers 

*/

  // check permissions
  function canAny(permissionList: string[]): boolean {
    return permissionList.some((p) => permissions.value.includes(p));
  }
  // check role
  function hasRole(role: "customer" | "editor" | "admin"): boolean {
    return user.value?.role === role;
  }

  return {
    //state
    user,
    token,
    permissions,
    permissionsByModule,
    isLoading,
    error,
    success,

    //getters
    isAuthenticated,
    fullName,
    isAdmin,
    showPassword,
    allUsers,
    meta,

    //actions
    register,
    login,
    logout,
    loadCurrentUser,
    fetchUsersList,
    refreshToken,
    deleteUser,
    updateUser,
    getUser,

    //Helpers
    canAny,
    hasRole,
  };
});
