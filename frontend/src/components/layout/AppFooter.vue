<script setup>
const currentYear = new Date().getFullYear()

const footerLinks = {
  shop: [
    { name: 'New Arrivals', to: '/' },
    { name: 'Best Sellers', to: '/' },
    { name: 'Sale', to: '/' },
    { name: 'All Products', to: '/' }
  ],
  help: [
    { name: 'Customer Service', to: '/contact' },
    { name: 'Track Order', to: '/orders' },
    { name: 'Returns & Exchanges', to: '/contact' },
    { name: 'Shipping Info', to: '/contact' }
  ],
  company: [
    { name: 'About Us', to: '/about' },
    { name: 'Careers', to: '/about' },
    { name: 'Press', to: '/about' },
    { name: 'Contact', to: '/contact' }
  ]
}

const socialLinks = [
  { icon: 'fa-brands fa-facebook-f', url: '#', color: 'hover:text-blue-600' },
  { icon: 'fa-brands fa-twitter', url: '#', color: 'hover:text-sky-500' },
  { icon: 'fa-brands fa-instagram', url: '#', color: 'hover:text-pink-500' },
  { icon: 'fa-brands fa-tiktok', url: '#', color: 'hover:text-black' }
]

const paymentMethods = [
  'fa-brands fa-cc-visa',
  'fa-brands fa-cc-mastercard',
  'fa-brands fa-cc-amex',
  'fa-brands fa-cc-paypal',
  'fa-brands fa-cc-apple-pay'
]
</script>

<template>
  <footer class="bg-white mt-16 border-t border-border">
    <!-- Main Footer -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8">
        <!-- Brand Section -->
        <div class="lg:col-span-2">
          <div class="flex items-center gap-2 mb-4">
            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center">
              <i class="fa-solid fa-bag-shopping text-white"></i>
            </div>
            <span class="text-xl font-bold text-text">LapakBaju</span>
          </div>
          <p class="text-text-light text-sm leading-relaxed mb-6 max-w-xs">
            Your ultimate destination for trendy and quality fashion. We bring the latest styles right to your doorstep.
          </p>
          
          <!-- Social Links -->
          <div class="flex gap-3">
            <a 
              v-for="social in socialLinks"
              :key="social.icon"
              :href="social.url"
              class="w-10 h-10 rounded-xl bg-background flex items-center justify-center text-text-light transition-all hover:bg-primary hover:text-white"
            >
              <i :class="social.icon"></i>
            </a>
          </div>
        </div>

        <!-- Shop Links -->
        <div>
          <h4 class="font-bold text-text mb-4">Shop</h4>
          <ul class="space-y-3">
            <li v-for="link in footerLinks.shop" :key="link.name">
              <router-link 
                :to="link.to" 
                class="text-text-light text-sm hover:text-primary transition-colors"
              >
                {{ link.name }}
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Help Links -->
        <div>
          <h4 class="font-bold text-text mb-4">Help</h4>
          <ul class="space-y-3">
            <li v-for="link in footerLinks.help" :key="link.name">
              <router-link 
                :to="link.to" 
                class="text-text-light text-sm hover:text-primary transition-colors"
              >
                {{ link.name }}
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Company Links -->
        <div>
          <h4 class="font-bold text-text mb-4">Company</h4>
          <ul class="space-y-3">
            <li v-for="link in footerLinks.company" :key="link.name">
              <router-link 
                :to="link.to" 
                class="text-text-light text-sm hover:text-primary transition-colors"
              >
                {{ link.name }}
              </router-link>
            </li>
          </ul>
        </div>
      </div>

      <!-- Newsletter -->
      <div class="mt-12 pt-8 border-t border-border">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
          <div>
            <h4 class="font-bold text-text mb-1">Subscribe to our newsletter</h4>
            <p class="text-text-light text-sm">Get updates on new arrivals and special offers!</p>
          </div>
          <div class="flex w-full md:w-auto gap-2">
            <input 
              type="email" 
              placeholder="Enter your email"
              class="flex-1 md:w-64 px-4 py-3 border border-border rounded-xl text-sm focus:border-primary outline-none"
            >
            <button class="px-6 py-3 bg-primary text-white rounded-xl font-semibold text-sm hover:bg-primary-dark transition-colors whitespace-nowrap">
              Subscribe
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Footer -->
    <div class="border-t border-border">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
          <!-- Copyright -->
          <p class="text-text-light text-sm text-center md:text-left">
            © {{ currentYear }} LapakBaju. All rights reserved.
          </p>

          <!-- Payment Methods -->
          <div class="flex items-center gap-3">
            <span class="text-text-light text-xs">We accept:</span>
            <div class="flex gap-2">
              <i 
                v-for="method in paymentMethods" 
                :key="method" 
                :class="method"
                class="text-2xl text-text-light hover:text-text transition-colors"
              ></i>
            </div>
          </div>

          <!-- Legal Links -->
          <div class="flex gap-4 text-sm">
            <a href="#" class="text-text-light hover:text-primary transition-colors">Privacy Policy</a>
            <a href="#" class="text-text-light hover:text-primary transition-colors">Terms of Service</a>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>
