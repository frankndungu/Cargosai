<template>
  <div class="product-card">
    <img :src="product.imageUrl" alt="Product Image" class="product-image" />
    <div class="product-info">
      <span class="discount-tag">{{ product.vendor }}</span>
      <!-- Product Name Link -->
      <router-link to="/" class="product-name-link">
        <h3 class="product-name">{{ product.name }}</h3>
      </router-link>
      <div class="rating">
        <span class="stars">⭐ {{ product.rating }}</span>
        <span class="reviews">({{ product.reviews }})</span>
      </div>
      <div class="price-section">
        <span class="price">${{ product.price }}</span>
        <button @click="addToCart(product)" class="add-to-cart">
          Add to cart
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useStore } from "vuex";

const props = defineProps({
  product: {
    type: Object,
    required: true,
  },
});

const store = useStore();

const addToCart = (product) => {
  store.dispatch("addToCart", product);
};
</script>
