<template>
  <section>
    <PreorderBanner />
    <div class="product-grid" id="product-grid">
      <ProductCard
        v-for="product in products"
        :key="product.id"
        :product="product"
      />
    </div>
  </section>
</template>

<script setup>
import ProductCard from "../products/ProductCard.vue";
import PreorderBanner from "../ui/PreorderBanner.vue";
import { ref, onMounted } from "vue";
import axios from "axios";

// Access the API URL from environment variables
const API_URL = import.meta.env.VITE_API_URL;

const products = ref([]);
const loading = ref(true);

const fetchProducts = async () => {
  try {
    const { data } = await axios.get(`${API_URL}/products`);
    products.value = data;
  } catch (error) {
    console.error("Error fetching products:", error);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchProducts();
});
</script>
