<template>
  <!-- Left Section -->
  <div class="left-section">
    <div class="card blog-posts-card">
      <h2>Blog Posts</h2>
      <p class="blog-intro">
        Discover the stories behind our handcrafted Maasai products and the
        culture that inspires them.
      </p>
    </div>

    <div class="card popular-products-card">
      <div v-if="loading" class="blogintro-loading-circle">
        <div class="blogintro-circle"></div>
      </div>
      <div v-else class="popular-products-grid">
        <button
          v-for="product in products"
          :key="product._id"
          class="popular-product-button"
        >
          {{ product.name }}
        </button>
      </div>
      <router-link to="/shop" class="view-all-link"
        >View All Products</router-link
      >
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { createClient } from "@sanity/client";

// Sanity client configuration
const sanityClient = createClient({
  projectId: import.meta.env.VITE_SANITY_PROJECT_ID,
  dataset: import.meta.env.VITE_SANITY_DATASET,
  useCdn: true,
  apiVersion: "2023-08-22",
});

// State
const products = ref([]);
const loading = ref(true);

// Fetch products from Sanity
const fetchProducts = async () => {
  const query = `*[_type == "product"]{ _id, name }`;
  products.value = await sanityClient.fetch(query);
  loading.value = false;
};

// Lifecycle hook
onMounted(() => {
  fetchProducts();
});
</script>
