<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import api from "@/services/api";
import { useCartStore } from "@/stores/cart";
import { useUserStore } from "@/stores/user";
import Breadcrumb from "@/components/layout/Breadcrumb.vue";

const router = useRouter();
const cartStore = useCartStore();
const userStore = useUserStore();

const breadcrumbItems = [{ label: "Checkout" }];

// form  Shipping
const shippingForm = ref({
  firstName: userStore.profile?.first_name || "",
  lastName: userStore.profile?.last_name || "",
  email: userStore.profile?.email || "",
  phone: userStore.profile?.phone || "",
  country: "Morocco",
  city: "",
  address: "",
  zip: "",
});

const shippingMethod = ref("standard");
const shippingOptions = [
  { id: "standard", name: "Standard Shipping", price: 10, days: "5-7 business days" },
  { id: "express", name: "Express Shipping", price: 25, days: "2-3 business days" },
  { id: "overnight", name: "Overnight Shipping", price: 50, days: "1 business day" },
];

// function   pour enregistrer Address
async function proceedToPayment() {
  // validation
  if (
    !shippingForm.value.address ||
    !shippingForm.value.city ||
    !shippingForm.value.zip ||
    !shippingForm.value.phone
  ) {
    alert("Please fill in all required fields");
    return;
  }

  try {
    // payload  backend
    const payload = {
      country: shippingForm.value.country,
      city: shippingForm.value.city,
      street: shippingForm.value.address,
      post_code: shippingForm.value.zip,
      phone: shippingForm.value.phone,
    };

    const res = await api.post("/addresses", payload);

    // enregister  address_id dans  localStorage
    localStorage.setItem("address_id", res.data.data.id);

    router.push("/payment");
  } catch (error) {
    console.error(error);
    alert("Error while saving address");
  }
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
}
</script>

<template>
  <div class="pb-12">
    <Breadcrumb :items="breadcrumbItems" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 lg:gap-8">
      <!-- Checkout Form -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-xl md:rounded-2xl p-4 sm:p-6 md:p-8 mb-4 md:mb-6">
          <h2 class="text-xl md:text-2xl font-bold mb-4 md:mb-6">Shipping Information</h2>

          <form class="space-y-4 md:space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
              <div>
                <label class="block text-sm font-medium mb-2">First Name *</label>
                <input
                  v-model="shippingForm.firstName"
                  type="text"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                />
              </div>
              <div>
                <label class="block text-sm font-medium mb-2">Last Name *</label>
                <input
                  v-model="shippingForm.lastName"
                  type="text"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                />
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
              <div>
                <label class="block text-sm font-medium mb-2">Email *</label>
                <input
                  v-model="shippingForm.email"
                  type="email"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                />
              </div>
              <div>
                <label class="block text-sm font-medium mb-2">Phone *</label>
                <input
                  v-model="shippingForm.phone"
                  type="tel"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                />
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium mb-2">Address *</label>
              <input
                v-model="shippingForm.address"
                type="text"
                class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none"
              />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-4">
              <div>
                <label class="block text-sm font-medium mb-2">City *</label>
                <input
                  v-model="shippingForm.city"
                  type="text"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                />
              </div>
              <div>
                <label class="block text-sm font-medium mb-2">ZIP Code *</label>
                <input
                  v-model="shippingForm.zip"
                  type="text"
                  class="w-full px-4 py-3 border border-border rounded-xl focus:border-primary focus:ring-1 focus:ring-primary outline-none"
                />
              </div>
            </div>
          </form>
        </div>

        <!-- Shipping Method -->
        <div class="bg-white rounded-xl md:rounded-2xl p-4 sm:p-6 md:p-8">
          <h2 class="text-xl md:text-2xl font-bold mb-4 md:mb-6">Shipping Method</h2>
          <div class="space-y-4">
            <label
              v-for="option in shippingOptions"
              :key="option.id"
              :class="[
                'flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer transition-all',
                shippingMethod === option.id
                  ? 'border-primary bg-primary/5'
                  : 'border-border hover:border-gray-300',
              ]"
            >
              <div class="flex items-center gap-4">
                <input
                  v-model="shippingMethod"
                  type="radio"
                  :value="option.id"
                  class="w-5 h-5 text-primary"
                />
                <div>
                  <p class="font-semibold">{{ option.name }}</p>
                  <p class="text-sm text-text-light">{{ option.days }}</p>
                </div>
              </div>
              <span class="font-bold">${{ option.price.toFixed(2) }}</span>
            </label>
          </div>
        </div>
      </div>

      <!-- Order Summary -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 sticky top-5">
          <h3 class="text-lg md:text-xl font-bold mb-4 md:mb-6">Order Summary</h3>

          <div class="space-y-4 mb-6">
            <div v-for="item in cartStore.items" :key="item.id" class="flex gap-4">
              <pre>
                {{ item }}
              </pre>
              <div class="flex-1">
                <img
                  :src="getImageUrl(item.image.image_url)"
                  :alt="item.product_variant?.product?.name"
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
              <span
                >${{ shippingOptions.find((o) => o.id === shippingMethod).price.toFixed(2) }}</span
              >
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-text-light">Tax</span>
              <span>${{ cartStore.tax.toFixed(2) }}</span>
            </div>
            <div class="flex justify-between font-bold text-lg pt-3 border-t border-border">
              <span>Total</span>
              <span
                >${{
                  (
                    cartStore.subtotal +
                    shippingOptions.find((o) => o.id === shippingMethod).price +
                    cartStore.tax
                  ).toFixed(2)
                }}</span
              >
            </div>
          </div>

          <button
            class="w-full bg-primary text-white py-4 rounded-xl font-bold mt-6 hover:bg-primary-dark transition-colors"
            @click="proceedToPayment"
          >
            Continue to Payment
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
