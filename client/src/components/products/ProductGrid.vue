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

// Computed properties from Vuex store
const currentPage = computed(() => store.getters.currentPage);
const totalPages = computed(() => store.getters.totalPages);
const totalProducts = computed(() => store.getters.totalProducts); // New getter for total products

// State variable for products
const products = ref([]);

// Fetch products based on the current page
const fetchProducts = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_API_URL}/products`,
      {
        params: { page: currentPage.value },
      }
    );

    // Set products in local state
    products.value = response.data.products.data;

    // Set total products and total pages in Vuex store
    store.dispatch("setTotalPages", response.data.products.last_page);
    store.dispatch("setTotalProducts", response.data.total); // Dispatch total products
  } catch (error) {
    console.error("Error fetching products:", error);
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

// Fetch products when the component mounts
onMounted(() => {
  fetchProducts();
});
</script>
