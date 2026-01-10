import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./App.vue";
import router from "./router";
import "./assets/styles/main.css";
import { useCartStore } from "@/stores/cart";
import { setupRouterGuards } from "./router/guards";
const app = createApp(App);

app.use(createPinia());
setupRouterGuards(router);
app.use(router);

//const cartStore = useCartStore()
//cartStore.fetchCart()
app.mount("#app");
