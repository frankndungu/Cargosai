<template>
  <div class="product-card">
    <img :src="product.image_url" alt="Product Image" class="product-image" />
    <div class="product-info">
      <span class="discount-tag">{{ product.vendor }}</span>
      <!-- Product Name Link -->
      <router-link to="/shop/product" class="product-name-link">
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
import { useToast } from "vue-toast-notification";

const store = useStore();
const toast = useToast();

const props = defineProps({
  product: {
    type: Object,
    required: true,
  },
});

const addToCart = (product) => {
  store.dispatch("addToCart", product);
  toast.success(`${product.name} has been added to the cart!`, {
    position: "top-right",
    timeout: 3000,
    closeOnClick: true,
    pauseOnHover: true,
  });
};
</script>
