<template>
  <div class="product-overview-wrapper">
    <div class="product-overview-container">
      <!-- Product Image Section -->
      <div class="product-overview-image">
        <img :src="currentImage" alt="Product Image" />
      </div>

      <!-- Product Details Section -->
      <div class="product-overview-details">
        <div class="product-status">
          <span class="product-status-badge">In stock</span>
        </div>
        <!-- Combined Title and Description -->
        <h1 class="product-title-overview">
          {{ product.name }}, {{ product.description }}
        </h1>

        <!-- Price and Rating -->
        <div class="product-price-rating">
          <span class="product-price-overview">{{ product.price }}</span>
          <div class="product-rating">
            <span class="product-stars">⭐⭐⭐</span>
            <span class="product-reviews">(3.0)</span>
            <a href="#" class="product-review-link">Add a review</a>
          </div>
        </div>

        <!-- Add to Cart Button -->
        <button @click="addToCart" class="product-add-to-cart">
          Add to cart
        </button>

        <!-- Vendor Info -->
        <div class="product-vendor-info">
          <p>
            Crafted and sold by <strong>{{ product.vendor }}</strong>
          </p>
          <span>Live on Maasai Market since 2024</span>
        </div>
      </div>
    </div>

    <!-- Product Thumbnail Section -->
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
import { reactive, ref } from "vue";

// Product Data
const product = reactive({
  name: "Handcrafted Brass Necklace, Adjustable Length",
  description: "Intricate Design, Durable and Stylish, Gold Finish",
  price: "$9.99",
  image:
    "https://res.cloudinary.com/kwishi/image/upload/v1725290282/IMG_1841_pxxkgj.jpg",
  thumbnails: [
    {
      src: "https://res.cloudinary.com/kwishi/image/upload/v1723877597/IMG_1868_pz49ft.jpg",
      alt: "Front view",
    },
    {
      src: "https://res.cloudinary.com/kwishi/image/upload/v1723876880/A08FAA78-332F-4BC8-B5B9-91C2AA28B634_pu9wfi.jpg",
      alt: "Back view",
    },
    {
      src: "https://res.cloudinary.com/kwishi/image/upload/v1723451902/62180B51-9E28-438B-9C93-5BD7227193CC_wnlwco.jpg",
      alt: "Left view",
    },
    {
      src: "https://res.cloudinary.com/kwishi/image/upload/v1723449027/IMG_1831_cdtsrb.png",
      alt: "Right view",
    },
  ],
  vendor: "Cultured Brass",
});

// State for currently selected image
const currentImage = ref(product.image);

// Function to change the main image
const setCurrentImage = (image) => {
  currentImage.value = image;
};

// Add to Cart Function
const addToCart = () => {
  console.log("Product added to cart!");
};
</script>
