<script setup>
import { ref } from 'vue'
import Breadcrumb from '@/components/layout/Breadcrumb.vue'

const breadcrumbItems = [{ label: 'Contact Us' }]

const contactForm = ref({
  name: '',
  email: '',
  subject: '',
  message: ''
})

const isSubmitting = ref(false)

function submitForm() {
  if (!contactForm.value.name || !contactForm.value.email || !contactForm.value.message) {
    alert('Please fill in all required fields')
    return
  }
  
  isSubmitting.value = true
  
  setTimeout(() => {
    alert('Thank you for your message! We will get back to you soon.')
    contactForm.value = { name: '', email: '', subject: '', message: '' }
    isSubmitting.value = false
  }, 1500)
}

const contactInfo = [
  {
    icon: 'fa-solid fa-location-dot',
    title: 'Visit Us',
    lines: ['123 Fashion Street', 'New York, NY 10001', 'United States']
  },
  {
    icon: 'fa-solid fa-phone',
    title: 'Call Us',
    lines: ['+1 (555) 123-4567', '+1 (555) 987-6543']
  },
  {
    icon: 'fa-solid fa-envelope',
    title: 'Email Us',
    lines: ['support@lapakbaju.com', 'sales@lapakbaju.com']
  },
  {
    icon: 'fa-solid fa-clock',
    title: 'Working Hours',
    lines: ['Mon - Fri: 9AM - 6PM', 'Sat: 10AM - 4PM', 'Sun: Closed']
  }
]
</script>

<template>
  <div class="pb-12">
    <Breadcrumb :items="breadcrumbItems" />
    
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary to-purple-600 rounded-2xl p-8 sm:p-12 mb-8 text-white text-center">
      <h1 class="text-3xl sm:text-4xl font-bold mb-4">Get In Touch</h1>
      <p class="text-white/80 max-w-xl mx-auto">
        Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
      </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Contact Form -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl p-6 sm:p-8">
          <h2 class="text-xl font-bold mb-6">Send us a Message</h2>
          
          <form @submit.prevent="submitForm" class="space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
              <div>
                <label class="block text-sm font-medium text-text-light mb-2">Your Name *</label>
                <input 
                  v-model="contactForm.name"
                  type="text"
                  placeholder="John Doe"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary outline-none transition-colors"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-text-light mb-2">Your Email *</label>
                <input 
                  v-model="contactForm.email"
                  type="email"
                  placeholder="john@example.com"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary outline-none transition-colors"
                >
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-text-light mb-2">Subject</label>
              <input 
                v-model="contactForm.subject"
                type="text"
                placeholder="How can we help?"
                class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary outline-none transition-colors"
              >
            </div>
            
            <div>
              <label class="block text-sm font-medium text-text-light mb-2">Message *</label>
              <textarea 
                v-model="contactForm.message"
                rows="5"
                placeholder="Write your message here..."
                class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary outline-none transition-colors resize-none"
              ></textarea>
            </div>
            
            <button 
              type="submit"
              :disabled="isSubmitting"
              :class="[
                'w-full sm:w-auto px-8 py-3.5 rounded-xl font-semibold transition-all',
                isSubmitting 
                  ? 'bg-gray-300 text-gray-500 cursor-not-allowed' 
                  : 'bg-primary text-white hover:bg-primary-dark'
              ]"
            >
              <i v-if="isSubmitting" class="fa-solid fa-spinner fa-spin mr-2"></i>
              {{ isSubmitting ? 'Sending...' : 'Send Message' }}
            </button>
          </form>
        </div>
      </div>

      <!-- Contact Info -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl p-6 sm:p-8">
          <h2 class="text-xl font-bold mb-6">Contact Information</h2>
          
          <div class="space-y-6">
            <div 
              v-for="info in contactInfo" 
              :key="info.title"
              class="flex gap-4"
            >
              <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                <i :class="info.icon" class="text-primary text-lg"></i>
              </div>
              <div>
                <h4 class="font-semibold text-text mb-1">{{ info.title }}</h4>
                <p 
                  v-for="line in info.lines" 
                  :key="line"
                  class="text-sm text-text-light"
                >
                  {{ line }}
                </p>
              </div>
            </div>
          </div>

          <!-- Social Links -->
          <div class="mt-8 pt-6 border-t border-border">
            <h4 class="font-semibold text-text mb-4">Follow Us</h4>
            <div class="flex gap-3">
              <a href="#" class="w-10 h-10 rounded-xl bg-background flex items-center justify-center text-text-light hover:bg-primary hover:text-white transition-all">
                <i class="fa-brands fa-facebook-f"></i>
              </a>
              <a href="#" class="w-10 h-10 rounded-xl bg-background flex items-center justify-center text-text-light hover:bg-primary hover:text-white transition-all">
                <i class="fa-brands fa-twitter"></i>
              </a>
              <a href="#" class="w-10 h-10 rounded-xl bg-background flex items-center justify-center text-text-light hover:bg-primary hover:text-white transition-all">
                <i class="fa-brands fa-instagram"></i>
              </a>
              <a href="#" class="w-10 h-10 rounded-xl bg-background flex items-center justify-center text-text-light hover:bg-primary hover:text-white transition-all">
                <i class="fa-brands fa-linkedin-in"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
