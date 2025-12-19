import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/HomeView.vue'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'home',
            component: HomeView
        },
        {
            path: '/product/:id',
            name: 'product',
            component: () => import('@/views/ProductDetailView.vue')
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('@/views/LoginView.vue')
        },
        {
            path: '/register',
            name: 'register',
            component: () => import('@/views/RegisterView.vue')
        },
        {
            path: '/cart',
            name: 'cart',
            component: () => import('@/views/CartView.vue')
        },
        {
            path: '/checkout',
            name: 'checkout',
            component: () => import('@/views/CheckoutView.vue')
        },
        {
            path: '/payment',
            name: 'payment',
            component: () => import('@/views/PaymentView.vue')
        },
        {
            path: '/order-confirmation',
            name: 'order-confirmation',
            component: () => import('@/views/OrderConfirmationView.vue')
        },
        {
            path: '/profile',
            name: 'profile',
            component: () => import('@/views/ProfileView.vue')
        },
        {
            path: '/orders',
            name: 'orders',
            component: () => import('@/views/OrdersView.vue')
        },
        {
            path: '/wishlist',
            name: 'wishlist',
            component: () => import('@/views/WishlistView.vue')
        },
        {
            path: '/contact',
            name: 'contact',
            component: () => import('@/views/ContactView.vue')
        },
        {
            path: '/about',
            name: 'about',
            component: () => import('@/views/AboutView.vue')
        }
    ]
})

export default router
