import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useCartStore = defineStore('cart', () => {
    // State
    const items = ref(JSON.parse(localStorage.getItem('cart')) || [])

    // Getters
    const totalItems = computed(() => {
        return items.value.reduce((acc, item) => acc + item.quantity, 0)
    })

    const subtotal = computed(() => {
        return items.value.reduce((acc, item) => acc + (item.price * item.quantity), 0)
    })

    const tax = computed(() => subtotal.value * 0.05)

    const shipping = computed(() => items.value.length > 0 ? 10 : 0)

    const total = computed(() => subtotal.value + tax.value + shipping.value)

    // Actions
    function addToCart(product) {
        const existing = items.value.findIndex(
            item => item.id === product.id && item.size === product.size && item.color === product.color
        )

        if (existing > -1) {
            items.value[existing].quantity += product.quantity
        } else {
            items.value.push({ ...product })
        }

        saveToStorage()
    }

    function removeFromCart(index) {
        items.value.splice(index, 1)
        saveToStorage()
    }

    function updateQuantity(index, change) {
        if (items.value[index]) {
            items.value[index].quantity += change
            if (items.value[index].quantity < 1) {
                items.value[index].quantity = 1
            }
            saveToStorage()
        }
    }

    function clearCart() {
        items.value = []
        saveToStorage()
    }

    function saveToStorage() {
        localStorage.setItem('cart', JSON.stringify(items.value))
    }

    return {
        items,
        totalItems,
        subtotal,
        tax,
        shipping,
        total,
        addToCart,
        removeFromCart,
        updateQuantity,
        clearCart
    }
})
