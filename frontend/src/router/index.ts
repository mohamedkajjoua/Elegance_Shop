import { createRouter, createWebHistory } from "vue-router";

const routes = [
  {
    path: "/home",
    name: "home",
    component: () => import("@/page/HomeView.vue"),
    meta: { title: "Home" },
  },
  // AUTH
  {
    path: "/auth",
    component: () => import("@/layouts/AuthLayout.vue"),
    children: [
      { path: "login", name: "login", component: () => import("@/page/auth/LoginView.vue") },
      {
        path: "register",
        name: "register",
        component: () => import("@/page/auth/RegisterView.vue"),
      },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
