<template>
  <section>
    <PreorderBanner />

    <!-- Product Grid -->
    <div class="product-grid" id="product-grid">
      <ProductCard
        v-for="product in products"
        :key="product.id"
        :product="product"
      />
    </div>

    <!-- Pagination Controls and Result Count -->
    <div class="pagination-controls">
      <!-- Show result count -->
      <span class="result-count">
        Showing {{ products.length }} of {{ totalProducts }} products
      </span>
      <button
        class="pagination-button"
        @click="previousPage"
        :disabled="page === 1"
      >
        Previous
      </button>
      <span>Page {{ page }} of {{ totalPages }}</span>
      <button
        class="pagination-button"
        @click="nextPage"
        :disabled="page === totalPages"
      >
        Next
      </button>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import ProductCard from "../products/ProductCard.vue";
import PreorderBanner from "../ui/PreorderBanner.vue";

// State variables for products, pagination, and loading
const products = ref([]);
const totalProducts = ref(0);
const loading = ref(true);
const page = ref(1);
const totalPages = ref(0);

const fetchProducts = async () => {
  loading.value = true;
  try {
    // Fetch products with pagination
    const response = await axios.get(
      `${import.meta.env.VITE_API_URL}/products`,
      {
        params: { page: page.value },
      }
    );

    // Set products and pagination info
    products.value = response.data.products.data;
    totalProducts.value = response.data.total; // Total products count
    totalPages.value = response.data.products.last_page; // Total pages
  } catch (error) {
    console.error("Error fetching products:", error);
  } finally {
    loading.value = false;
  }
};

// Pagination methods
const nextPage = () => {
  if (page.value < totalPages.value) {
    page.value++;
    fetchProducts();
  }
};

const previousPage = () => {
  if (page.value > 1) {
    page.value--;
    fetchProducts();
  }
};

// Fetch products when component mounts
onMounted(() => {
  fetchProducts();
});
</script>
