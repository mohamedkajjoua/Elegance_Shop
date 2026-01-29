<script setup>
import { onMounted, ref } from "vue";
import { useCartStore } from "@/stores/cart";
import CartItem from "@/components/cart/CartItem.vue";
import CartSummary from "@/components/cart/CartSummary.vue";
import Breadcrumb from "@/components/layout/Breadcrumb.vue";
import { useRouter } from "vue-router";

const cartStore = useCartStore();
const router = useRouter();

// Définir breadcrumb
const breadcrumbItems = ref([{ label: "Shopping Cart" }]);

// Récupérer le panier au montage
onMounted(() => cartStore.fetchCart());

// Modifier la quantité
function handleUpdateQuantity(index, change) {
  const item = cartStore.items[index];
  const newQty = item.quantity + change;
  if (newQty > 0) {
    cartStore.updateItem(item.id, newQty);
  }
}

// Supprimer un item
function handleRemoveItem(index) {
  const item = cartStore.items[index];

  cartStore.removeItem(item.id);
}

// Checkout
function handleCheckout() {
  router.push("/checkout");
}
</script>

<template>
  <div class="container" style="max-width: 100%; padding: 0">
    <Breadcrumb :items="breadcrumbItems" />

    <div class="cart-layout flex gap-4">
      <!-- Cart Items -->
      <div class="cart-items flex-1">
        <div class="cart-header flex justify-between items-center mb-4">
          <h2 class="text-2xl font-bold">Your Cart</h2>
          <span class="cart-count">{{ cartStore.items.length }} Items</span>
        </div>

        <template v-if="cartStore.items.length">
          <CartItem
            v-for="(item, index) in cartStore.items"
            :key="item.id"
            :item="item"
            :index="index"
            @updateQuantity="(change) => handleUpdateQuantity(index, change)"
            @remove="() => handleRemoveItem(index)"
          />
        </template>

        <div v-else class="empty-cart text-center py-12 text-gray-400">
          <i class="fa-solid fa-cart-shopping text-5xl mb-4 opacity-30"></i>
          <p class="text-lg">Your cart is empty</p>
          <router-link
            to="/"
            class="block w-full text-center border border-border text-text py-3 rounded-xl font-medium mt-4 hover:border-primary hover:text-primary transition-colors"
          >
            Continue Shopping
          </router-link>
        </div>
      </div>

      <!-- Summary -->
      <CartSummary @checkout="handleCheckout" />
    </div>
  </div>
</template>
