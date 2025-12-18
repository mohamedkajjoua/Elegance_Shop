import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useWishlistStore = defineStore('wishlist', () => {
    // State
    const items = ref(JSON.parse(localStorage.getItem('wishlist')) || [])

    // Getters
    const totalItems = computed(() => items.value.length)

    function isInWishlist(productId) {
        return items.value.some(item => item.id === productId)
    }

    // Actions
    function addToWishlist(product) {
        if (!isInWishlist(product.id)) {
            items.value.push({ ...product })
            saveToStorage()
        }
    }

    function removeFromWishlist(productId) {
        items.value = items.value.filter(item => item.id !== productId)
        saveToStorage()
    }

    function toggleWishlist(product) {
        if (isInWishlist(product.id)) {
            removeFromWishlist(product.id)
            return false
        } else {
            addToWishlist(product)
            return true
        }
    }

    function saveToStorage() {
        localStorage.setItem('wishlist', JSON.stringify(items.value))
    }

    return {
        items,
        totalItems,
        isInWishlist,
        addToWishlist,
        removeFromWishlist,
        toggleWishlist
    }
})
