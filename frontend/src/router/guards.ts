import authService from "@/services/AuthService";
import { useAuthStore } from "./../stores/auth/auth";
import type { Router } from "vue-router";

export function setupRouterGuards(router: Router) {
  router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();

    if (!authStore.user && authService.getToken()) {
      try {
        await authStore.loadCurrentUser();
      } catch (err: any) {
        console.log("failed to restore session", err);

        // authStore.logout();
        // return next({ name: 'login' });
      }
    }

    const isAuth = authStore.isAuthenticated;
    const requiresAuth = to.matched.some((record) => record.meta.requiresAuth);
    const isGuestRoute = to.matched.some((record) => record.meta.guest);

    const requiredPermissions = to.meta.permissions as string[] | undefined;
    const requiredRole = to.meta.role as string | undefined;

    // ---------------------------------------------------
    // (Routing Rules)
    // ---------------------------------------------------

    if (isGuestRoute && isAuth) {
      return next({ name: "home" });
    }

    if (requiresAuth && !isAuth) {
      return next({ name: "login", query: { redirect: to.fullPath } });
    }

    if (requiredPermissions && !authStore.canAny(requiredPermissions)) {
      return next({ name: "forbidden" });
    }

    if (requiredRole && !authStore.hasRole(requiredRole as any)) {
      return next({ name: "forbidden" });
    }

    next();
  });
}
