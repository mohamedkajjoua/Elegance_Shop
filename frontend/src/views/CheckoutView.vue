<script setup>
import { onMounted, ref, computed } from "vue";
import { useRouter } from "vue-router";
import { useCartStore } from "@/stores/cart";
import Breadcrumb from "@/components/layout/Breadcrumb.vue";
import { useAddressStore } from "@/stores/user/AddressStore";
import { useAuthStore } from "@/stores/auth/auth";
import { useOrderStore } from "@/stores/user/OrderStore";

const router = useRouter();
const cartStore = useCartStore();
const userStore = useAuthStore();
const addressStore = useAddressStore();
const orderStore = useOrderStore();

//

onMounted(async () => {
  await addressStore.fetchAddresses();
});

//  FIX 1: Define BASE_URL so the image function works
const BASE_URL = "http://127.0.0.1:8000";

const breadcrumbItems = [{ label: "Checkout" }];

//  FIX 2: Moved getImageUrl OUT of proceedToPayment so the template can use it
const getImageUrl = (img) => {
  if (!img) return "/images/placeholder.jpg";

  // If we pass the whole image object
  if (typeof img === "object" && img.url) {
    return img.url;
  }

  // If backend returns partial path
  const path = typeof img === "object" ? img.image_url || img.url : img;

  if (!path) return "/images/placeholder.jpg";
  if (path.startsWith("http")) return path;

  return `${BASE_URL}/storage/${path.replace(/^\//, "")}`;
};

const selectedAddressId = ref(null);

onMounted(async () => {
  await addressStore.fetchAddresses();
  if (addressStore.addresses.length > 0) {
    selectedAddressId.value = addressStore.addresses[0].id;
  }
});

//create order

const handleCheckout = async () => {
  if (!selectedAddressId.value) {
    console.log("Please select a shipping address first.");
    return;
  }

  const payload = {
    addresse_id: selectedAddressId.value,

    payment_method: "cod",
  };

  try {
    const newOrder = await orderStore.createOrder(payload);

    localStorage.setItem("address_id", selectedAddressId.value);
    localStorage.setItem("last_order_id", newOrder.id);

    console.log("Order created successfully!");
    router.push("/payment");
  } catch (err) {
    if (err.response && err.response.status === 422) {
      console.log("Validation Errors:", err.response.data.errors);

      console.log("Error: " + err.response.data.errors.payment_method[0]);
    } else {
      console.error("Order failed:", err);
      console.log("Failed to create order");
    }
  }
};

// calcule shipping
const calculateShipping = () => {
  if (!cartStore.items || cartStore.items.length === 0) return 0;

  let totalShipping = 0;
  cartStore.items.forEach((item) => {
    if (item.product_variant?.product?.shipping) {
      totalShipping += parseFloat(item.product_variant.product.shipping) * item.quantity;
    }
  });
  return totalShipping;
};
//total Amount
const totalAmount = computed(() => {
  const subtotal = parseFloat(cartStore.subtotal || 0);
  const shipping = calculateShipping();
  return subtotal + shipping;
});

const formatCurrency = (amount) => {
  return parseFloat(amount).toFixed(2);
};
</script>

<template>
  <div class="pb-12">
    <Breadcrumb :items="breadcrumbItems" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 lg:gap-8">
      <div class="lg:col-span-2">
        <!-- addresses -->
        <div class="bg-white rounded-xl md:rounded-2xl p-4 sm:p-6 md:p-8 mb-4 md:mb-6">
          <h2 class="text-xl md:text-2xl font-bold mb-4 md:mb-6">Shipping Information</h2>

          <div class="space-y-4">
            <label
              v-for="address in addressStore.addresses"
              :key="address.id"
              :class="[
                'flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer transition-all',
                selectedAddressId === address.id
                  ? 'border-primary bg-primary/5'
                  : 'border-border hover:border-gray-300',
              ]"
            >
              <div class="flex items-start gap-4">
                <input
                  v-model="selectedAddressId"
                  type="radio"
                  :value="address.id"
                  class="w-5 h-5 mt-1 text-primary"
                />
                <div class="min-w-0">
                  <h4 class="font-bold flex items-center gap-2">
                    {{ address.label }}
                    <span
                      v-if="selectedAddressId === address.id"
                      class="text-[10px] bg-primary text-white px-2 py-0.5 rounded-full uppercase"
                      >Selected</span
                    >
                  </h4>
                  <p class="text-sm text-text-light">
                    {{ address.recipient }} - {{ address.phone }}
                  </p>
                  <p class="text-sm text-text-light mt-1 break-words">
                    {{ address.street }}, {{ address.city }}, {{ address.country }}
                  </p>
                </div>
              </div>

              <div class="text-primary/40">
                <i class="fa-solid fa-location-dot text-xl"></i>
              </div>
            </label>

            <div
              v-if="addressStore.addresses.length === 0"
              class="text-center py-6 border-2 border-dashed border-border rounded-xl"
            >
              <router-link
                :to="'/profile/' + userStore.user.id"
                class="text-primary font-bold underline"
              >
                Add New Address in Profile
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <div class="lg:col-span-1">
        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 sticky top-5">
          <h3 class="text-lg md:text-xl font-bold mb-4 md:mb-6">Order Summary</h3>

          <div class="space-y-4 mb-6">
            <div v-for="item in cartStore.items" :key="item.id" class="flex gap-4">
              <div class="flex-1">
                <img
                  :src="getImageUrl(item.product_variant?.product?.images?.[0])"
                  :alt="item.product_variant?.product?.name"
                  class="w-16 h-16 object-cover rounded-lg mb-2"
                />
                <p class="font-medium text-sm">{{ item.product_variant?.product?.name }}</p>
                <p class="text-xs text-text-light">
                  {{ item.product_variant?.size }} / {{ item.product_variant?.color }}
                </p>
                <p class="text-sm font-bold mt-1">
                  ${{ item.product_variant?.price }} × {{ item.quantity }}
                </p>
              </div>
            </div>
          </div>

          <div class="border-t border-border pt-4 space-y-3">
            <div class="flex justify-between text-sm">
              <span class="text-text-light">Subtotal</span>
              <span>${{ cartStore.subtotal.toFixed(2) }}</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-text-light">Shipping</span>
              <span>{{ formatCurrency(calculateShipping()) }}DH</span>
            </div>
            <div class="flex justify-between font-bold text-lg pt-3 border-t border-border">
              <span>Total</span>
              <span>{{ formatCurrency(totalAmount) }}DH</span>
            </div>
          </div>

          <button
            :disabled="cartStore.items.length === 0"
            :class="[
              'w-full py-4 rounded-xl font-bold mt-6 transition-colors',
              cartStore.items.length > 0
                ? 'bg-primary text-white hover:bg-primary-dark'
                : 'bg-gray-300 text-gray-500 cursor-not-allowed',
            ]"
            @click="handleCheckout"
          >
            Continue to Payment
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
