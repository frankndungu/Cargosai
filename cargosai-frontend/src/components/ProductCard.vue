<template>
  <div class="product-card">
    <img :src="product.image" :alt="product.name" class="product-image" />
    <h3 class="product-title">{{ product.name }}</h3>
    <p class="product-description">{{ product.description }}</p>
    <div class="product-footer">
      <p class="product-price">{{ product.price }}</p>
      <button class="preorder-button">Pre-Order</button>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
  product: {
    type: Object,
    required: true,
  },
});

const viewDetails = () => {};
</script>
