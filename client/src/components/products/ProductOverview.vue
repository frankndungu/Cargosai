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
          <span class="product-price-overview">{{ product.price }}</span>
          <div class="product-rating">
            <span class="product-stars">⭐⭐⭐</span>
            <span class="product-reviews">(3.0)</span>
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
    <div class="product-overview-thumbnails">
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

const route = useRoute();
const store = useStore();
const product = ref(null);
const currentImage = ref("");

const fetchProduct = async () => {
  try {
    const response = await axios.get(
      `http://localhost:8000/api/products/${route.params.id}`
    );
    product.value = response.data;
    currentImage.value = product.value.image;
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
