<template>
  <div v-if="product" class="product-overview-wrapper">
    <!-- Product Image Section -->
    <div class="product-overview-container">
      <div class="product-overview-image">
        <img :src="product.image_url" alt="Product Image" />
      </div>
      <!-- Product Details Section -->
      <div class="product-overview-details">
        <div class="product-status">
          <span v-if="product.stock > 0" class="product-status-badge">
            In stock
          </span>
          <span v-else class="product-status-badge-out">Out of stock</span>
        </div>
        <h1 class="product-title-overview">
          {{ product.name }}, {{ product.description }}
        </h1>
        <div class="product-price-rating">
          <span class="product-price-overview">${{ product.price }}</span>
          <div class="product-rating">
            <span class="product-stars">⭐⭐⭐</span>
            <span class="product-reviews">({{ product.rating }})</span>
            <a
              href="#"
              @click.prevent="scrollToReviews"
              class="product-review-link"
              >Add a review</a
            >
          </div>
        </div>
        <button @click="addToCart" class="product-add-to-cart">
          Add to cart
        </button>
        <div class="product-vendor-info">
          <p>
            Crafted and sold by <strong>{{ product.vendor_name }}</strong>
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

  <!-- Product information -->
  <div class="product-info-container">
    <ProductInformation />
    <Vendor />
    <ProductReviews ref="productReviews" />
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import axios from "axios";
import { useStore } from "vuex";
import ProductInformation from "../products/ProductInformation.vue";
import Vendor from "../products/Vendor.vue";
import ProductReviews from "../products/ProductReviews.vue";

const API_URL = import.meta.env.VITE_API_URL;

const route = useRoute();
const store = useStore();
const product = ref(null);

// Fetch product data based on the slug
const fetchProduct = async () => {
  try {
    const response = await axios.get(
      `${API_URL}/products/slug/${route.params.slug}`
    );
    console.log("Fetched Product Data:", response.data); // Log the fetched product data
    product.value = response.data;
  } catch (error) {
    console.error("Failed to fetch product:", error);
  }
};

const setCurrentImage = (image) => {
  product.value.image_url = image; // Update the product image when a thumbnail is clicked
};

const addToCart = () => {
  if (product.value) {
    store.dispatch("addToCart", product.value);
  }
};

// Scroll to ProductReviews component
const scrollToReviews = () => {
  const reviewsSection = document.querySelector(
    ".product-info-container > .product-reviews-section"
  );
  if (reviewsSection) {
    reviewsSection.scrollIntoView({ behavior: "smooth" });
  }
};

onMounted(() => {
  fetchProduct();
});
</script>
