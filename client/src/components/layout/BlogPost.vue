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
      <div class="popular-products-grid">
        <button
          v-for="product in products"
          :key="product.name"
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
import { ref } from "vue";

const products = ref([
  { name: "Beaded Earrings" },
  { name: "Maasai Sandals" },
  { name: "Hand-carved Chess Board" },
  { name: "Decorative Mirrors" },
  { name: "Traditional Dresses" },
]);
</script>
