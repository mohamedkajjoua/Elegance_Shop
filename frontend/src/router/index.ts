import { createRouter, createWebHistory } from "vue-router";

const routes = [
  {
    path: "/home",
    name: "home",
    component: () => import("@/page/Home.vue"),
    meta: { title: "Home" },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
