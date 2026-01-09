import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import './assets/styles/main.css'
import { useCartStore } from '@/stores/cart'
const app = createApp(App)

app.use(createPinia())
app.use(router)
const cartStore = useCartStore()
cartStore.fetchCart()
app.mount('#app')
