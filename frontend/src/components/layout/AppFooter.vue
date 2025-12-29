<script setup>
const currentYear = new Date().getFullYear()

const footerLinks = {
  shop: [
    { name: 'New Arrivals', to: '/shop' },
    { name: 'Best Sellers', to: '/shop' },
    { name: 'Sale', to: '/shop' },
    { name: 'All Products', to: '/shop' }
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
  { icon: 'fa-brands fa-facebook-f', url: '#', label: 'Facebook' },
  { icon: 'fa-brands fa-twitter', url: '#', label: 'Twitter' },
  { icon: 'fa-brands fa-instagram', url: '#', label: 'Instagram' },
  { icon: 'fa-brands fa-tiktok', url: '#', label: 'TikTok' },
  { icon: 'fa-brands fa-youtube', url: '#', label: 'YouTube' }
]

const paymentMethods = [
  'fa-brands fa-cc-visa',
  'fa-brands fa-cc-mastercard',
  'fa-brands fa-cc-amex',
  'fa-brands fa-cc-paypal',
  'fa-brands fa-cc-apple-pay',
  'fa-brands fa-google-pay'
]
</script>

<template>
  <footer class="footer-wrapper">
    <!-- Main Footer -->
    <div class="bg-gradient-to-b from-gray-900 to-gray-950 text-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-8">
          <!-- Brand Section -->
          <div class="lg:col-span-4">
            <div class="flex items-center gap-3 mb-6">
              <div class="w-12 h-12 bg-gradient-to-br from-primary to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-primary/30">
                <i class="fa-solid fa-shapes text-white text-xl"></i>
              </div>
              <span class="text-2xl font-black text-white">Elegance Shop</span>
            </div>
            <p class="text-gray-400 text-sm leading-relaxed mb-8 max-w-sm">
              Your ultimate destination for trendy and quality fashion. We bring the latest styles right to your doorstep with premium quality and unbeatable prices.
            </p>
            
            <!-- Social Links -->
            <div class="flex gap-3">
              <a 
                v-for="social in socialLinks"
                :key="social.icon"
                :href="social.url"
                :aria-label="social.label"
                class="w-11 h-11 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 transition-all duration-300 hover:bg-primary hover:border-primary hover:text-white hover:scale-110 hover:shadow-lg hover:shadow-primary/30"
              >
                <i :class="social.icon"></i>
              </a>
            </div>
          </div>

          <!-- Shop Links -->
          <div class="lg:col-span-2">
            <h4 class="font-bold text-white text-lg mb-6 flex items-center gap-2">
              <span class="w-8 h-0.5 bg-primary rounded-full"></span>
              Shop
            </h4>
            <ul class="space-y-4">
              <li v-for="link in footerLinks.shop" :key="link.name">
                <router-link 
                  :to="link.to" 
                  class="text-gray-400 text-sm hover:text-white hover:pl-2 transition-all duration-300 flex items-center gap-2 group"
                >
                  <i class="fa-solid fa-chevron-right text-[8px] opacity-0 group-hover:opacity-100 transition-opacity text-primary"></i>
                  {{ link.name }}
                </router-link>
              </li>
            </ul>
          </div>

          <!-- Help Links -->
          <div class="lg:col-span-2">
            <h4 class="font-bold text-white text-lg mb-6 flex items-center gap-2">
              <span class="w-8 h-0.5 bg-primary rounded-full"></span>
              Help
            </h4>
            <ul class="space-y-4">
              <li v-for="link in footerLinks.help" :key="link.name">
                <router-link 
                  :to="link.to" 
                  class="text-gray-400 text-sm hover:text-white hover:pl-2 transition-all duration-300 flex items-center gap-2 group"
                >
                  <i class="fa-solid fa-chevron-right text-[8px] opacity-0 group-hover:opacity-100 transition-opacity text-primary"></i>
                  {{ link.name }}
                </router-link>
              </li>
            </ul>
          </div>

          <!-- Company Links -->
          <div class="lg:col-span-2">
            <h4 class="font-bold text-white text-lg mb-6 flex items-center gap-2">
              <span class="w-8 h-0.5 bg-primary rounded-full"></span>
              Company
            </h4>
            <ul class="space-y-4">
              <li v-for="link in footerLinks.company" :key="link.name">
                <router-link 
                  :to="link.to" 
                  class="text-gray-400 text-sm hover:text-white hover:pl-2 transition-all duration-300 flex items-center gap-2 group"
                >
                  <i class="fa-solid fa-chevron-right text-[8px] opacity-0 group-hover:opacity-100 transition-opacity text-primary"></i>
                  {{ link.name }}
                </router-link>
              </li>
            </ul>
          </div>

          <!-- Contact Info -->
          <div class="lg:col-span-2">
            <h4 class="font-bold text-white text-lg mb-6 flex items-center gap-2">
              <span class="w-8 h-0.5 bg-primary rounded-full"></span>
              Contact
            </h4>
            <ul class="space-y-4">
              <li class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <i class="fa-solid fa-location-dot text-primary text-sm"></i>
                </div>
                <span class="text-gray-400 text-sm">123 Fashion Street, New York, NY 10001</span>
              </li>
              <li class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0">
                  <i class="fa-solid fa-phone text-primary text-sm"></i>
                </div>
                <span class="text-gray-400 text-sm">+1 (555) 123-4567</span>
              </li>
              <li class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0">
                  <i class="fa-solid fa-envelope text-primary text-sm"></i>
                </div>
                <span class="text-gray-400 text-sm">hello@eleganceshop.com</span>
              </li>
            </ul>
          </div>
        </div>

        <!-- App Download Section -->
        <div class="mt-16 pt-10 border-t border-white/10">
          <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
            <div class="text-center lg:text-left">
              <h4 class="font-bold text-white text-xl mb-2">Download Our App</h4>
              <p class="text-gray-400 text-sm">Get exclusive app-only deals and shop on the go!</p>
            </div>
            <div class="flex flex-wrap justify-center gap-4">
              <a href="#" class="flex items-center gap-3 px-5 py-3 bg-white/5 border border-white/10 rounded-xl hover:bg-white/10 transition-colors group">
                <i class="fa-brands fa-apple text-2xl text-white"></i>
                <div class="text-left">
                  <div class="text-[10px] text-gray-400 uppercase tracking-wide">Download on the</div>
                  <div class="text-sm font-semibold text-white">App Store</div>
                </div>
              </a>
              <a href="#" class="flex items-center gap-3 px-5 py-3 bg-white/5 border border-white/10 rounded-xl hover:bg-white/10 transition-colors group">
                <i class="fa-brands fa-google-play text-2xl text-white"></i>
                <div class="text-left">
                  <div class="text-[10px] text-gray-400 uppercase tracking-wide">Get it on</div>
                  <div class="text-sm font-semibold text-white">Google Play</div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Footer -->
    <div class="bg-gray-950 border-t border-white/5">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
          <!-- Copyright -->
          <p class="text-gray-500 text-sm text-center md:text-left">
            © {{ currentYear }} Elegance Shop. All rights reserved. Made with 
            <i class="fa-solid fa-heart text-red-500 text-xs mx-1"></i> 
            for fashion lovers.
          </p>

          <!-- Payment Methods -->
          <div class="flex items-center gap-4">
            <span class="text-gray-500 text-xs">We accept:</span>
            <div class="flex gap-2">
              <i 
                v-for="method in paymentMethods" 
                :key="method" 
                :class="method"
                class="text-2xl text-gray-500 hover:text-white transition-colors cursor-pointer"
              ></i>
            </div>
          </div>

          <!-- Legal Links -->
          <div class="flex gap-6 text-sm">
            <a href="#" class="text-gray-500 hover:text-white transition-colors">Privacy Policy</a>
            <a href="#" class="text-gray-500 hover:text-white transition-colors">Terms of Service</a>
            <a href="#" class="text-gray-500 hover:text-white transition-colors">Cookies</a>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<style scoped>
/* Add smooth reveal animation for footer */
.footer-wrapper {
  position: relative;
}

.footer-wrapper::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(95, 46, 234, 0.5), transparent);
}
</style>
