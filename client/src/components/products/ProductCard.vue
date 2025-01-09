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
        <div class="price-container">
          <span class="price">${{ product.price }}</span>
        </div>
        <button @click="addToCart(product)" class="add-to-cart">
          Add to Cart
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
.product-card {
  color: var(--dark-color);
  padding: 15px;
  border-radius: 12px;
  transition: all 0.3s ease;
  height: auto;
  display: flex;
  flex-direction: column;
}

.image-container {
  position: relative;
  width: 100%;
  padding-top: 100%; /* Creates a perfect square */
  margin-bottom: 1rem;
  border-radius: 8px;
  overflow: hidden;
}

.product-image {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.image-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.02);
  display: flex;
  justify-content: center;
  align-items: center;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.image-container:hover .image-overlay {
  opacity: 1;
}

.image-container:hover .product-image {
  transform: scale(1.05);
}

.quick-add {
  background: white;
  color: var(--dark-color);
  padding: 12px 24px;
  border-radius: 25px;
  border: none;
  font-weight: 600;
  cursor: pointer;
  transform: translateY(20px);
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.image-container:hover .quick-add {
  transform: translateY(0);
}

.quick-add:hover {
  background: var(--dark-tint);
  color: white;
}

.product-info {
  text-align: left;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.name-rating-container {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
}

.product-name-link {
  text-decoration: none;
  flex: 1;
}

.product-name {
  font-size: 1.125rem;
  font-weight: 600;
  color: var(--dark-color);
  margin: 0;
  line-height: 1.4;
}

.vendor-name {
  color: var(--dark-color);
  font-size: 0.875rem;
  opacity: 0.85;
  font-weight: 500;
}

.rating {
  display: flex;
  align-items: center;
  background: #f8f8f8;
  padding: 4px 8px;
  border-radius: 4px;
}

.stars {
  color: var(--dark-tint);
  display: flex;
  align-items: center;
  gap: 0.25rem;
  font-weight: 600;
}

.price-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: auto;
}

.price-container {
  display: flex;
  flex-direction: column;
}

.price {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--accent-color);
}

.free-shipping {
  font-size: 0.75rem;
  color: #22c55e;
  font-weight: 600;
}

.add-to-cart {
  background: var(--dark-tint);
  color: white;
  padding: 10px 20px;
  border-radius: 6px;
  border: none;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.2s ease;
}

.add-to-cart:hover {
  background: var(--dark-color);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.add-to-cart:active {
  transform: translateY(0);
}

@media (max-width: 768px) {
  .product-card {
    padding: 12px;
  }

  .quick-add {
    padding: 10px 20px;
    font-size: 0.875rem;
  }
}
</style>
