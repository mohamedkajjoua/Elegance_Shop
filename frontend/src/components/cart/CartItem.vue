<script setup>
const props = defineProps({ item: Object, index: Number })
const emit = defineEmits(['updateQuantity','remove'])

function updateQty(change){ emit('updateQuantity', change) }
function removeItem(){ emit('remove') }
</script>

<template>
  <div class="cart-item flex justify-between items-center p-2 border-b">
    <img :src="item.productVariant.image" alt="" class="w-16 h-16 object-cover"/>
    <div class="flex-1 px-2">
      <p>{{ item.productVariant.name }}</p>
      <p>Size: {{ item.size }} | Color: {{ item.color }}</p>
    </div>
    <div class="flex items-center gap-2">
      <button @click="updateQty(-1)">-</button>
      <span>{{ item.quantity }}</span>
      <button @click="updateQty(1)">+</button>
      <span>${{ (item.quantity * item.productVariant.price).toFixed(2) }}</span>
      <button @click="removeItem">🗑️</button>
    </div>
  </div>
</template>
