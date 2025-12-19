import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useWishlistStore = defineStore('wishlist', () => {
    // Sample wishlist data
    const sampleWishlistItems = [
        {
            id: 4,
            name: 'Limited Edition Bomber Jacket',
            price: 129.99,
            image: 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=200&h=200&fit=crop',
            category: 'Jackets'
        },
        {
            id: 5,
            name: 'Cotton Graphic Tee',
            price: 35.00,
            image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=200&h=200&fit=crop',
            category: 'T-Shirts'
        },
        {
            id: 6,
            name: 'Denim Oversized Shirt',
            price: 78.50,
            image: 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=200&h=200&fit=crop',
            category: 'Shirts'
        },
        {
            id: 7,
            name: 'Fleece Zip Hoodie',
            price: 95.00,
            image: 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=200&h=200&fit=crop',
            category: 'Hoodies'
        }
    ]

    // State - load from localStorage or use sample data
    const storedWishlist = JSON.parse(localStorage.getItem('wishlist'))
    const items = ref(storedWishlist && storedWishlist.length > 0 ? storedWishlist : sampleWishlistItems)

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
