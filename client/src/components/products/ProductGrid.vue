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
        :disabled="currentPage === 1"
      >
        Previous
      </button>
      <span>Page {{ currentPage }} of {{ totalPages }}</span>
      <button
        class="pagination-button"
        @click="nextPage"
        :disabled="currentPage === totalPages"
      >
        Next
      </button>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { useStore } from "vuex";
import axios from "axios";
import ProductCard from "../products/ProductCard.vue";
import PreorderBanner from "../ui/PreorderBanner.vue";

const store = useStore();

// State variables
const products = ref([]);
const totalProducts = ref(0);
const loading = ref(true);

// Computed properties from Vuex store
const currentPage = computed(() => store.getters.currentPage);
const totalPages = computed(() => store.getters.totalPages);

const fetchProducts = async () => {
  loading.value = true;
  try {
    // Fetch products with pagination
    const response = await axios.get(
      `${import.meta.env.VITE_API_URL}/products`,
      {
        params: { page: currentPage.value },
      }
    );

    // Set products and pagination info
    products.value = response.data.products.data;
    totalProducts.value = response.data.total;
    store.dispatch("setTotalPages", response.data.products.last_page);
  } catch (error) {
    console.error("Error fetching products:", error);
  } finally {
    loading.value = false;
  }
};

// Pagination methods
const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    store.dispatch("setCurrentPage", currentPage.value + 1);
    fetchProducts();
  }
};

const previousPage = () => {
  if (currentPage.value > 1) {
    store.dispatch("setCurrentPage", currentPage.value - 1);
    fetchProducts();
  }
};

// Fetch products when component mounts
onMounted(() => {
  fetchProducts();
});
</script>
