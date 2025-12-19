import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useUserStore = defineStore('user', () => {
    // State
    const profile = ref(JSON.parse(localStorage.getItem('userProfile')) || {
        name: 'John Doe',
        email: 'john.doe@email.com',
        phone: '+1 234 567 890',
        avatar: 'https://ui-avatars.com/api/?name=John+Doe&background=5F2EEA&color=fff'
    })

    const sampleAddresses = [
        {
            id: 1,
            label: 'Home',
            recipient: 'John Doe',
            phone: '+1 234 567 890',
            text: '123 Main Street, Apt 4B, New York, NY 10001, USA',
            street: '123 Main Street, Apt 4B',
            city: 'New York',
            state: 'NY',
            zipCode: '10001',
            country: 'USA'
        },
        {
            id: 2,
            label: 'Office',
            recipient: 'John Doe',
            phone: '+1 234 567 891',
            text: '456 Business Ave, Suite 200, Los Angeles, CA 90001, USA',
            street: '456 Business Ave, Suite 200',
            city: 'Los Angeles',
            state: 'CA',
            zipCode: '90001',
            country: 'USA'
        }
    ]

    const storedAddresses = JSON.parse(localStorage.getItem('addresses'))
    const addresses = ref(storedAddresses && storedAddresses.length > 0 ? storedAddresses : sampleAddresses)

    const isLoggedIn = ref(true) // Default to logged in for demo

    // Getters
    const initials = computed(() => {
        return profile.value.name.split(' ').map(n => n[0]).join('').toUpperCase()
    })

    // Actions
    function updateProfile(data) {
        profile.value = { ...profile.value, ...data }
        localStorage.setItem('userProfile', JSON.stringify(profile.value))
    }

    function addAddress(address) {
        const newAddress = { ...address, id: Date.now() }
        addresses.value.push(newAddress)
        localStorage.setItem('addresses', JSON.stringify(addresses.value))
    }

    function removeAddress(id) {
        addresses.value = addresses.value.filter(a => a.id !== id)
        localStorage.setItem('addresses', JSON.stringify(addresses.value))
    }

    function updateAddress(id, data) {
        const index = addresses.value.findIndex(a => a.id === id)
        if (index > -1) {
            addresses.value[index] = { ...addresses.value[index], ...data }
            localStorage.setItem('addresses', JSON.stringify(addresses.value))
        }
    }

    function logout() {
        isLoggedIn.value = false
        localStorage.removeItem('userProfile')
    }

    function login(email, password) {
        // Mock login
        isLoggedIn.value = true
        return true
    }

    return {
        profile,
        addresses,
        isLoggedIn,
        initials,
        updateProfile,
        addAddress,
        removeAddress,
        updateAddress,
        logout,
        login
    }
})
