<script setup>
defineProps({
  item: {
    type: Object,
    required: true,
  },
  index: {
    type: Number,
    required: true,
  },
});
const BASE_URL = "http://127.0.0.1:8000";
const getImageUrl = (img) => {
  if (!img) return "/images/placeholder.jpg";

  if (typeof img === "object" && img.url) {
    return img.url;
  }

  const path = typeof img === "object" ? img.image_url : img;

  if (!path) return "/images/placeholder.jpg";

  if (path.startsWith("http")) return path;

  return `${BASE_URL}/storage/${path.replace(/^\//, "")}`;
};

const emit = defineEmits(["updateQuantity", "remove"]);

function updateQty(change) {
  emit("updateQuantity", change);
}

function removeItem() {
  emit("remove");
}
</script>

<template>
  <div class="cart-item">
    <div class="cart-item-image">
      <img
        :src="getImageUrl(item.product_variant?.product?.images?.[0]?.image_url)"
        :alt="item.product_variant?.product?.name"
      />
    </div>

    <div class="cart-item-details">
      <div>
        <h3 class="cart-item-title">
          {{ item.product_variant?.product?.name }}
        </h3>

        <p class="cart-item-variant">
          Size: {{ item.product_variant?.size }} • Color: {{ item.product_variant?.color }}
        </p>
      </div>

      <div class="cart-item-actions">
        <div class="qty-input" style="padding: 8px 16px">
          <button class="qty-btn" @click="updateQty(-1)">
            <i class="fa-solid fa-minus"></i>
          </button>

          <span class="qty-val">{{ item.quantity }}</span>

          <button class="qty-btn" @click="updateQty(1)">
            <i class="fa-solid fa-plus"></i>
          </button>
        </div>

        <span v-if="item.discount" class="cart-item-price">
          ${{ ((item.product_variant?.final_price || 0) * item.quantity).toFixed(2) }}
        </span>
        <span v-else class="cart-item-price">
          ${{ ((item.product_variant?.price || 0) * item.quantity).toFixed(2) }}
        </span>
      </div>
    </div>

    <button class="cart-item-remove" @click="removeItem">
      <i class="fa-solid fa-trash"></i>
    </button>
  </div>
</template>
