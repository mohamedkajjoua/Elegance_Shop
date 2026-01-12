import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./App.vue";
import router from "./router";
import "./assets/styles/main.css";
import { useCartStore } from "@/stores/cart";
import { setupRouterGuards } from "./router/guards";
const app = createApp(App);

app.use(createPinia());
app.use(router);
setupRouterGuards(router);
app.mount("#app");
