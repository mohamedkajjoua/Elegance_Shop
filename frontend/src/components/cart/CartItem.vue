<script setup>
defineProps({
  item: {
    type: Object,
    required: true
  },
  index: {
    type: Number,
    required: true
  }
})

const emit = defineEmits(['updateQuantity', 'remove'])

function updateQty(change) {
  emit('updateQuantity', change)
}

function removeItem() {
  emit('remove')
}
</script>

<template>
  <div class="cart-item">
    <div class="cart-item-image">
      <img :src="item.image" :alt="item.name || item.title">
    </div>
    <div class="cart-item-details">
      <div>
        <h3 class="cart-item-title">{{ item.name || item.title }}</h3>
        <p class="cart-item-variant">Size: {{ item.size }} • Color: {{ item.color }}</p>
      </div>
      <div class="cart-item-actions">
        <div class="qty-input" style="padding: 8px 16px;">
          <button class="qty-btn" @click="updateQty(-1)">
            <i class="fa-solid fa-minus"></i>
          </button>
          <span class="qty-val">{{ item.quantity }}</span>
          <button class="qty-btn" @click="updateQty(1)">
            <i class="fa-solid fa-plus"></i>
          </button>
        </div>
        <span class="cart-item-price">${{ (item.price * item.quantity).toFixed(2) }}</span>
      </div>
    </div>
    <button class="cart-item-remove" @click="removeItem">
      <i class="fa-solid fa-trash"></i>
    </button>
  </div>
</template>
