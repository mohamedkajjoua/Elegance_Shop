import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useCartStore = defineStore('cart', () => {
    // Sample cart data
    const sampleCartItems = [
        {
            id: 1,
            name: 'Oversize Sweatshirt Premium',
            price: 89.99,
            image: 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=200&h=200&fit=crop',
            size: 'L',
            color: 'Black',
            quantity: 2
        },
        {
            id: 2,
            name: 'Vintage Hoodie Classic',
            price: 75.50,
            image: 'https://images.unsplash.com/photo-1578768079052-aa76e52ff62e?w=200&h=200&fit=crop',
            size: 'M',
            color: 'Grey',
            quantity: 1
        },
        {
            id: 3,
            name: 'Streetwear Crewneck',
            price: 65.00,
            image: 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=200&h=200&fit=crop',
            size: 'XL',
            color: 'Navy',
            quantity: 1
        }
    ]

    // State - load from localStorage or use sample data
    const storedCart = JSON.parse(localStorage.getItem('cart'))
    const items = ref(storedCart && storedCart.length > 0 ? storedCart : sampleCartItems)

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
