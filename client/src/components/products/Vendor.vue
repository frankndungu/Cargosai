<template>
  <div class="vendor-info-section">
    <div class="vendor-info-header" @click="isOpen = !isOpen">
      <span>Vendor</span>
      <i
        :class="isOpen ? 'fa fa-chevron-up' : 'fa fa-chevron-down'"
        class="chevron"
      ></i>
    </div>
    <div v-if="isOpen" class="vendor-info-content">
      <p>Name: {{ vendor.vendor_name }}</p>
      <p>Email: {{ vendor.vendor_email }}</p>
      <p>Location: {{ vendor.vendor_location }}</p>
    </div>
    <hr class="separator" />
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import axios from "axios";

const route = useRoute();
const isOpen = ref(false);
const vendor = ref({}); // Initialize vendor data

const API_URL = import.meta.env.VITE_API_URL;

const fetchVendorInfo = async () => {
  try {
    const response = await axios.get(
      `${API_URL}/products/${route.params.slug}`
    );
    vendor.value = {
      vendor_name: response.data.vendor_name,
      vendor_email: response.data.vendor_email,
      vendor_location: response.data.vendor_location,
    }; // Assign the fetched vendor data
  } catch (error) {
    console.error("Error fetching vendor information:", error);
  }
};

// Fetch vendor info when the component mounts
onMounted(fetchVendorInfo);
</script>
