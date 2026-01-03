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

  // Is the user logged in?
  const isAuthenticated = computed(() => !!token.value && !!user.value);
  // full name
  const fullName = computed(() =>
    user.value ? `${user.value.first_name} ${user.value.first_name}` : ""
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
    isLoading.value = true;
    error.value = null;
    success.value = null;

    try {
      if (!authService.getToken()) {
        clearAuth();
        return false;
      }

      const res = await authService.getCurrentUser();
      if (res.success && res.user) {
        user.value = res.user;
        permissions.value = res.permissions || [];
        permissionsByModule.value = res.permissions_by_module || {};
        return false;
      } else {
        clearAuth();
        return false;
      }
    } catch (err: any) {
      clearAuth();
      return false;
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

    //actions
    register,
    login,
    logout,
    loadCurrentUser,
    refreshToken,

    //Helpers
    canAny,
    hasRole,
  };
});
