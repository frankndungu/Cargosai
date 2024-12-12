<template>
  <div class="product-card">
    <div class="image-container">
      <img :src="imageUrl" :alt="product.name" class="product-image" />
    </div>
    <div class="product-info">
      <div class="name-rating-container">
        <router-link
          :to="{ name: 'ProductPage', params: { slug: product.slug } }"
          class="product-name-link"
        >
          <h3 class="product-name">{{ product.name }}</h3>
        </router-link>
        <div class="rating">
          <div class="stars">
            <span>★</span>
            <span>{{ product.reviews_avg_rating }}</span>
          </div>
        </div>
      </div>
      <span class="vendor-name">
        {{ product.vendor_name }}
      </span>
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
import { computed } from "vue";
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

const imageUrl = computed(
  () => `${import.meta.env.VITE_STORAGE_BASE_URL}${props.product.main_image}`
);
</script>

<style scoped>
/* Product Card Container */
.product-card {
  color: var(--dark-color);
  padding: 15px;
  border-radius: 5px; /* Soft rounding for modern feel */
  transition: all 0.3s ease-in-out;
  height: 450px; /* Adjusted height for compactness */
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

/* Image Container */
.image-container {
  position: relative;
  width: 100%;
  height: auto; /* Set the desired height for the image */
  margin-bottom: 1rem;
  overflow: hidden;
  display: flex;
  justify-content: center;
  align-items: center;
}

/* Product Image */
.product-image {
  width: 100%; /* Let the image take full width of the container */
  height: 100%; /* Let the image take full height of the container */
  object-fit: cover; /* Ensure the image fills the container and is cropped if necessary */
  border-radius: 10px;
}

/* Product Info Section */
.product-info {
  text-align: left;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

/* Name and Rating Container */
.name-rating-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.5rem;
}

/* Vendor Badge */
.vendor-name {
  color: var(--dark-color);
  border-radius: 4px;
  font-size: 0.875rem;
  margin-bottom: 0.5rem;
  display: inline-flex;
  font-weight: 300;
  align-items: center;
  width: 50%;
}

.vendor-icon {
  margin-right: 0.5rem;
}

/* Product Name */
.product-name-link {
  text-decoration: none;
  display: block;
  flex-grow: 1;
  margin-right: 1rem;
}

.product-name {
  font-size: 1.125rem;
  font-weight: 600;
  color: var(--dark-color);
  margin: 0;
  line-height: 1.4;
}

.product-name-link:hover .product-name {
  color: var(--accent-color);
  text-decoration: underline;
}

/* Rating Section */
.rating {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 1.115rem;
}

.stars {
  color: var(--dark-color);
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

.reviews {
  color: var(--dark-color);
  opacity: 0.8;
}

/* Price and Action Section */
.price-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.price {
  font-size: 1.45rem;
  font-weight: 700;
  color: var(--accent-color);
}

.add-to-cart {
  background: var(--dark-tint);
  color: var(--background-color);
  padding: 0.5rem 1rem;
  border-radius: 4px;
  border: none;
  cursor: pointer;
  transition: background-color 0.3s ease-in-out;
}

.add-to-cart:hover {
  background: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.add-to-cart:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}
</style>
