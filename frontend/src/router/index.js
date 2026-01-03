import { createRouter, createWebHistory } from "vue-router";
import { setupRouterGuards } from "./guards";

import HomeView from "@/views/HomeView.vue";
import ShopView from "@/views/ShopView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    // ====================================================
    //  Public Routes
    // ====================================================
    {
      path: "/",
      name: "home",
      component: HomeView,
      meta: { title: "Home - Elegance Shop" },
    },
    {
      path: "/shop",
      name: "shop",
      component: ShopView,
      meta: { title: "Shop - Elegance Shop" },
    },
    {
      path: "/product/:id",
      name: "product",
      component: () => import("@/views/ProductDetailView.vue"),
      meta: { title: "Product Details - Elegance Shop" },
    },
    {
      path: "/contact",
      name: "contact",
      component: () => import("@/views/ContactView.vue"),
      meta: { title: "Contact Us - Fashion Store" },
    },
    {
      path: "/about",
      name: "about",
      component: () => import("@/views/AboutView.vue"),
      meta: { title: "About Us - Fashion Store" },
    },
    {
      path: "/forbidden",
      name: "forbidden",
      component: () => import("@/views/ForbiddenView.vue"),
      meta: { title: "Access Forbidden - Fashion Store" },
    },

    // ====================================================
    //  Guest Only Routes
    // ====================================================
    {
      path: "/login",
      name: "login",
      component: () => import("@/views/LoginView.vue"),
      meta: {
        title: "Login - Elegance Shop",
        guest: true,
      },
    },
    {
      path: "/register",
      name: "register",
      component: () => import("@/views/RegisterView.vue"),
      meta: {
        title: "Register - Fashion Store",
        guest: true,
      },
    },

    // ====================================================
    //  Authenticated User Routes
    // ====================================================
    {
      path: "/cart",
      name: "cart",
      component: () => import("@/views/CartView.vue"),
      meta: {
        title: "Shopping Cart - Fashion Store",
        requiresAuth: true,
      },
    },
    {
      path: "/checkout",
      name: "checkout",
      component: () => import("@/views/CheckoutView.vue"),
      meta: {
        title: "Checkout - Fashion Store",
        requiresAuth: true,
      },
    },
    {
      path: "/payment",
      name: "payment",
      component: () => import("@/views/PaymentView.vue"),
      meta: {
        title: "Payment - Fashion Store",
        requiresAuth: true,
      },
    },
    {
      path: "/order-confirmation",
      name: "order-confirmation",
      component: () => import("@/views/OrderConfirmationView.vue"),
      meta: {
        title: "Order Confirmed - Fashion Store",
        requiresAuth: true,
      },
    },
    {
      path: "/profile",
      name: "profile",
      component: () => import("@/views/ProfileView.vue"),
      meta: {
        title: "My Profile - Fashion Store",
        requiresAuth: true,
      },
    },
    {
      path: "/orders",
      name: "orders",
      component: () => import("@/views/OrdersView.vue"),
      meta: {
        title: "My Orders - Fashion Store",
        requiresAuth: true,
      },
    },
    {
      path: "/wishlist",
      name: "wishlist",
      component: () => import("@/views/WishlistView.vue"),
      meta: {
        title: "Wishlist - Fashion Store",
        requiresAuth: true,
      },
    },

    // ====================================================
    //  Admin Routes
    // ====================================================
    {
      path: "/admin",
      name: "admin-dashboard",
      component: () => import("@/views/admin/AdminDashboardView.vue"),
      meta: {
        title: "Dashboard - Admin Panel",
        requiresAuth: true,
        role: "admin",
      },
    },
    {
      path: "/admin/products",
      name: "admin-products",
      component: () => import("@/views/admin/AdminProductsView.vue"),
      meta: {
        title: "Products - Admin Panel",
        requiresAuth: true,
        role: "admin",
      },
    },
    {
      path: "/admin/products/create",
      name: "admin-products-create",
      component: () => import("@/views/admin/AdminCreateProductView.vue"),
      meta: {
        title: "Create Product - Admin Panel",
        requiresAuth: true,
        role: "admin",
      },
    },
    {
      path: "/admin/products/:id",
      name: "admin-product-detail",
      component: () => import("@/views/admin/AdminProductDetailView.vue"),
      meta: {
        title: "Product Details - Admin Panel",
        requiresAuth: true,
        role: "admin",
      },
    },
    {
      path: "/admin/category",
      name: "admin-category",
      component: () => import("@/views/admin/AdminCategoryView.vue"),
      meta: {
        title: "Categories - Admin Panel",
        requiresAuth: true,
        role: "admin",
      },
    },
    {
      path: "/admin/category/create",
      name: "admin-category-create",
      component: () => import("@/views/admin/AdminCategoryCreateView.vue"),
      meta: {
        title: "Create Category - Admin Panel",
        requiresAuth: true,
        role: "admin",
      },
    },
    {
      path: "/admin/category/edit",
      name: "admin-category-edit",
      component: () => import("@/views/admin/AdminCategoryEditView.vue"),
      meta: {
        title: "Edit Category - Admin Panel",
        requiresAuth: true,
        role: "admin",
      },
    },
    {
      path: "/admin/brand",
      name: "admin-brand",
      component: () => import("@/views/admin/AdminBrandView.vue"),
      meta: {
        title: "Brands - Admin Panel",
        requiresAuth: true,
        role: "admin",
      },
    },
    {
      path: "/admin/brand/create",
      name: "admin-brand-create",
      component: () => import("@/views/admin/AdminBrandCreateView.vue"),
      meta: {
        title: "Create Brand - Admin Panel",
        requiresAuth: true,
        role: "admin",
      },
    },
    {
      path: "/admin/brand/edit",
      name: "admin-brand-edit",
      component: () => import("@/views/admin/AdminBrandEditView.vue"),
      meta: {
        title: "Edit Brand - Admin Panel",
        requiresAuth: true,
        role: "admin",
      },
    },
    {
      path: "/admin/users",
      name: "admin-users",
      component: () => import("@/views/admin/AdminUsersView.vue"),
      meta: {
        title: "Users - Admin Panel",
        requiresAuth: true,
        role: "admin",
      },
    },
    {
      path: "/admin/users/edit",
      name: "admin-user-edit",
      component: () => import("@/views/admin/AdminUserEditView.vue"),
      meta: {
        title: "Edit User - Admin Panel",
        requiresAuth: true,
        role: "admin",
      },
    },
    {
      path: "/admin/orders",
      name: "admin-orders",
      component: () => import("@/views/admin/AdminOrdersView.vue"),
      meta: {
        title: "Orders - Admin Panel",
        requiresAuth: true,
        role: "admin",
      },
    },
    {
      path: "/admin/orders/:id",
      name: "admin-order-detail",
      component: () => import("@/views/admin/AdminOrderDetailView.vue"),
      meta: {
        title: "Order Details - Admin Panel",
        requiresAuth: true,
        role: "admin",
      },
    },
    {
      path: "/admin/profile",
      name: "admin-profile",
      component: () => import("@/views/admin/AdminProfileView.vue"),
      meta: {
        title: "Profile - Admin Panel",
        requiresAuth: true,
        role: "admin",
      },
    },
    {
      path: "/admin/settings",
      name: "admin-settings",
      component: () => import("@/views/admin/AdminSettingsView.vue"),
      meta: {
        title: "Settings - Admin Panel",
        requiresAuth: true,
        role: "admin",
      },
    },

    /*  {
      path: "/forbidden",
      name: "forbidden",
      component: () => import("@/views/errors/403.vue"),
      meta: { title: "Access Denied" },
    }, */
  ],
});

setupRouterGuards(router);

// Navigation guard to update document title

router.beforeEach((to, from, next) => {
  document.title = to.meta.title || "Fashion Store";

  next();
});

export default router;
