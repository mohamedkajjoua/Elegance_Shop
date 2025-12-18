<script setup>
defineProps({
  items: {
    type: Array,
    required: true
  }
})
</script>

<template>
  <nav class="breadcrumb flex items-center gap-2 text-sm text-text-light mb-5">
    <router-link to="/" class="hover:text-primary transition-colors">
      <i class="fa-solid fa-house text-xs"></i> Main Page
    </router-link>
    <template v-for="(item, index) in items" :key="index">
      <span>›</span>
      <router-link 
        v-if="item.to"
        :to="item.to"
        class="hover:text-primary transition-colors"
      >
        {{ item.label }}
      </router-link>
      <span v-else class="current text-text font-semibold">{{ item.label }}</span>
    </template>
  </nav>
</template>
