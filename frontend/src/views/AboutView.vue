<script setup>
import Breadcrumb from '@/components/layout/Breadcrumb.vue'

const breadcrumbItems = [{ label: 'About Us' }]

const stats = [
  { value: '10K+', label: 'Happy Customers' },
  { value: '500+', label: 'Products' },
  { value: '50+', label: 'Brands' },
  { value: '15+', label: 'Countries' }
]

const team = [
  {
    name: 'Sarah Johnson',
    role: 'Founder & CEO',
    image: 'https://ui-avatars.com/api/?name=Sarah+Johnson&background=5F2EEA&color=fff&size=200'
  },
  {
    name: 'Michael Chen',
    role: 'Creative Director',
    image: 'https://ui-avatars.com/api/?name=Michael+Chen&background=34C759&color=fff&size=200'
  },
  {
    name: 'Emily Davis',
    role: 'Head of Marketing',
    image: 'https://ui-avatars.com/api/?name=Emily+Davis&background=FF9F43&color=fff&size=200'
  },
  {
    name: 'David Wilson',
    role: 'Customer Relations',
    image: 'https://ui-avatars.com/api/?name=David+Wilson&background=3B82F6&color=fff&size=200'
  }
]

const values = [
  {
    icon: 'fa-solid fa-heart',
    title: 'Quality First',
    description: 'We source only the finest materials and work with trusted manufacturers to deliver products that last.'
  },
  {
    icon: 'fa-solid fa-leaf',
    title: 'Sustainability',
    description: 'We are committed to reducing our environmental footprint through eco-friendly practices and materials.'
  },
  {
    icon: 'fa-solid fa-users',
    title: 'Community',
    description: 'We believe in building a community of fashion lovers who share our passion for style and quality.'
  },
  {
    icon: 'fa-solid fa-truck-fast',
    title: 'Fast Delivery',
    description: 'Quick and reliable shipping to get your favorite fashion pieces to your doorstep in no time.'
  }
]
</script>

<template>
  <div class="pb-12">
    <Breadcrumb :items="breadcrumbItems" />
    
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary via-purple-600 to-pink-500 rounded-2xl p-8 sm:p-16 mb-10 text-white text-center relative overflow-hidden">
      <div class="absolute inset-0 bg-black/10"></div>
      <div class="relative z-10">
        <h1 class="text-3xl sm:text-5xl font-bold mb-4">About LapakBaju</h1>
        <p class="text-white/90 max-w-2xl mx-auto text-lg">
          We're on a mission to make quality fashion accessible to everyone. 
          Discover our story, our values, and the team behind the brand.
        </p>
      </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-12">
      <div 
        v-for="stat in stats" 
        :key="stat.label"
        class="bg-white rounded-2xl p-6 text-center"
      >
        <div class="text-3xl sm:text-4xl font-bold text-primary mb-1">{{ stat.value }}</div>
        <div class="text-sm text-text-light">{{ stat.label }}</div>
      </div>
    </div>

    <!-- Our Story -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
      <div class="bg-white rounded-2xl p-6 sm:p-8">
        <h2 class="text-xl sm:text-2xl font-bold mb-4">Our Story</h2>
        <div class="space-y-4 text-text-light leading-relaxed">
          <p>
            Founded in 2020, LapakBaju started with a simple idea: to bring high-quality, 
            trendy fashion to everyone at affordable prices. What began as a small online 
            boutique has grown into a beloved destination for fashion enthusiasts worldwide.
          </p>
          <p>
            Our journey has been driven by a passion for style and a commitment to customer 
            satisfaction. We carefully curate each piece in our collection, ensuring that 
            every item meets our high standards for quality, design, and value.
          </p>
          <p>
            Today, we're proud to serve customers in over 15 countries, offering a diverse 
            range of clothing and accessories that cater to every style and occasion.
          </p>
        </div>
      </div>
      
      <div class="bg-gradient-to-br from-primary/10 to-purple-100 rounded-2xl p-6 sm:p-8 flex items-center justify-center">
        <div class="text-center">
          <div class="w-32 h-32 bg-primary rounded-2xl flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-bag-shopping text-white text-5xl"></i>
          </div>
          <h3 class="text-2xl font-bold text-text mb-2">LapakBaju</h3>
          <p class="text-text-light">Fashion for Everyone</p>
        </div>
      </div>
    </div>

    <!-- Our Values -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 mb-12">
      <h2 class="text-xl sm:text-2xl font-bold mb-8 text-center">Our Values</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div 
          v-for="value in values" 
          :key="value.title"
          class="text-center"
        >
          <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-4">
            <i :class="value.icon" class="text-primary text-2xl"></i>
          </div>
          <h4 class="font-bold text-text mb-2">{{ value.title }}</h4>
          <p class="text-sm text-text-light">{{ value.description }}</p>
        </div>
      </div>
    </div>

    <!-- Our Team -->
    <div class="bg-white rounded-2xl p-6 sm:p-8">
      <h2 class="text-xl sm:text-2xl font-bold mb-8 text-center">Meet Our Team</h2>
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        <div 
          v-for="member in team" 
          :key="member.name"
          class="text-center group"
        >
          <div class="relative mb-4 mx-auto w-28 h-28 sm:w-32 sm:h-32">
            <img 
              :src="member.image" 
              :alt="member.name"
              class="w-full h-full rounded-2xl object-cover group-hover:scale-105 transition-transform duration-300"
            >
          </div>
          <h4 class="font-bold text-text">{{ member.name }}</h4>
          <p class="text-sm text-text-light">{{ member.role }}</p>
        </div>
      </div>
    </div>
  </div>
</template>
