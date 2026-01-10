import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./App.vue";
import router from "./router";
import "./assets/styles/main.css";

const app = createApp(App);

app.use(createPinia());
setupRouterGuards(router);
app.use(router);

app.mount("#app");
