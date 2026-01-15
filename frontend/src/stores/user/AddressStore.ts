import addressService from "@/services/AddressService";
import { defineStore } from "pinia";
import { ref } from "vue";

export const useAddressStore = defineStore("address", () => {
  const addresses = ref([]);
  const isLoading = ref(false);
  const error = ref(null);

  const fetchAddresses = async () => {
    isLoading.value = true;
    error.value = null;

    try {
      const res = await addressService.getAddresses();
      addresses.value = res.data.data || res.data;
    } catch (err: any) {
      error.value = err.message || "Failed to fetch addresses";
    } finally {
      isLoading.value = false;
    }
  };

  const fetchAddressesById = async (id: number) => {
    try {
      return await addressService.getAddressById(id);
    } catch (err: any) {
      error.value = err.message || "failed to fetch address ";
      throw err;
    }
  };

  const addAddress = async (data: any) => {
    isLoading.value = true;
    error.value = null;

    try {
      await addressService.createAddress(data);
      await fetchAddresses();
    } catch (err: any) {
      error.value = err.message || "Failed to create address";
    } finally {
      isLoading.value = false;
    }
  };

  const updateAddress = async (id: number, data: any) => {
    isLoading.value = true;
    error.value = null;

    try {
      await addressService.updateAddress(id, data);
      await fetchAddresses();
    } catch (err: any) {
      error.value = err.message || "Failed to update address";
    } finally {
      isLoading.value = false;
    }
  };
  const deleteAddress = async (id: number) => {
    isLoading.value = true;
    error.value = null;

    try {
      await addressService.deleteAddress(id);
      addresses.value = addresses.value.filter((addr) => addr.id !== id);
    } catch (err: any) {
      error.value = err.message || "Failed to delete address";
    } finally {
      isLoading.value = false;
    }
  };

  return {
    addresses,
    isLoading,
    error,
    addAddress,
    fetchAddressesById,
    updateAddress,
    deleteAddress,
    fetchAddresses,
  };
});
