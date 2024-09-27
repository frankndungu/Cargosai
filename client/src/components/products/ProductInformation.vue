<template>
  <div class="product-info-section">
    <div class="product-info-header" @click="isOpen = !isOpen">
      <span>Product Information</span>
      <i
        :class="isOpen ? 'fa fa-chevron-up' : 'fa fa-chevron-down'"
        class="chevron"
      ></i>
    </div>
    <div v-if="isOpen" class="product-info-content">
      <p>Dimensions: {{ product.dimensions }}</p>
      <p>Weight: {{ product.weight }} kg</p>
      <p>Material: {{ product.material }}</p>
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
const product = ref({}); // Initialize product data

const API_URL = import.meta.env.VITE_API_URL;

const fetchProductInfo = async () => {
  const response = await axios.get(
    `${API_URL}/products/slug/${route.params.slug}`
  );
  product.value = response.data; // Assign the fetched product data
};

// Fetch product info when the component mounts
onMounted(fetchProductInfo);
</script>
