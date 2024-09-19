<template>
  <div v-if="product" class="product-overview-wrapper">
    <!-- Product Image Section -->
    <div class="product-overview-container">
      <div class="product-overview-image">
        <img :src="currentImage" alt="Product Image" />
      </div>
      <!-- Product Details Section -->
      <div class="product-overview-details">
        <div class="product-status">
          <span class="product-status-badge">In stock</span>
        </div>
        <h1 class="product-title-overview">
          {{ product.name }}, {{ product.description }}
        </h1>
        <div class="product-price-rating">
          <span class="product-price-overview">${{ product.price }}</span>
          <div class="product-rating">
            <span class="product-stars">⭐⭐⭐</span>
            <span class="product-reviews">({{ product.rating }})</span>
            <a href="#" class="product-review-link">Add a review</a>
          </div>
        </div>
        <button @click="addToCart" class="product-add-to-cart">
          Add to cart
        </button>
        <div class="product-vendor-info">
          <p>
            Crafted and sold by <strong>{{ product.vendor }}</strong>
          </p>
          <span>Live on Maasai Market since 2024</span>
        </div>
      </div>
    </div>
    <div
      v-if="product.thumbnails && product.thumbnails.length > 0"
      class="product-overview-thumbnails"
    >
      <img
        v-for="(thumbnail, index) in product.thumbnails"
        :src="thumbnail.src"
        :alt="thumbnail.alt"
        :key="index"
        class="product-thumbnail"
        @click="setCurrentImage(thumbnail.src)"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import axios from "axios";
import { useStore } from "vuex";

// Use VITE_API_URL from environment variables
const API_URL = import.meta.env.VITE_API_URL;

const route = useRoute();
const store = useStore();
const product = ref(null);
const currentImage = ref("");

// Fetch product data based on the slug
const fetchProduct = async () => {
  try {
    const response = await axios.get(
      `${API_URL}/products/${route.params.slug}`
    );
    product.value = response.data;
    // Set currentImage to the main image or a default image if no thumbnails exist
    currentImage.value =
      product.value.thumbnails && product.value.thumbnails.length > 0
        ? product.value.thumbnails[0].src
        : product.value.image_url || "/path/to/default-image.jpg"; // Default image if `product.image_url` is not available
  } catch (error) {
    console.error("Failed to fetch product:", error);
  }
};

const setCurrentImage = (image) => {
  currentImage.value = image;
};

const addToCart = () => {
  if (product.value) {
    store.dispatch("addToCart", product.value);
  }
};

onMounted(() => {
  fetchProduct();
});
</script>
