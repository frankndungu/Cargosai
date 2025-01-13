<template>
  <div v-if="isLoading" class="loading-state" data-aos="fade">
    <div class="loading-spinner"></div>
    <p class="loading-text">Loading product overview...</p>
  </div>

  <div class="product-overview-wrapper" v-else-if="product" data-aos="fade-up">
    <!-- Product Image Section -->
    <div class="product-overview-container" data-aos="fade-left">
      <div class="product-overview-image">
        <div class="image-wrapper">
          <img :src="currentImage" alt="Product Image" />
        </div>
      </div>

      <!-- Product Details Section -->
      <div class="product-overview-details" data-aos="fade-right">
        <div class="product-status">
          <span v-if="product.stock > 0" class="product-status-badge">
            In stock
          </span>
          <span v-else class="product-status-badge-out">Out of stock</span>
        </div>

        <div class="product-header">
          <h1 class="product-title">{{ product.name }}</h1>
          <p class="product-description">{{ product.description }}</p>
        </div>

        <div class="product-price-rating">
          <span class="product-price-overview">${{ product.price }}</span>
          <div class="product-rating">
            <span class="product-average-rating">⭐ ({{ averageRating }})</span>
            <span class="product-reviews">
              <a
                href="#"
                @click.prevent="scrollToReviews"
                class="product-review-link"
                >{{ reviews.length }} Reviews</a
              >
            </span>
          </div>
        </div>

        <button
          @click="addToCart"
          class="product-add-to-cart"
          :disabled="product.stock <= 0"
        >
          {{ product.stock > 0 ? "Add to cart" : "Out of stock" }}
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
      data-aos="fade-up"
    >
      <div
        v-for="(thumbnail, index) in formattedThumbnails"
        :key="index"
        class="thumbnail-wrapper"
      >
        <img
          :src="thumbnail"
          :alt="`Thumbnail ${index + 1}`"
          :class="['product-thumbnail', { active: currentImage === thumbnail }]"
          @click="setCurrentImage(thumbnail)"
        />
      </div>
    </div>

    <div class="product-info">
      <ProductInformation />
      <Vendor />
      <ProductReturns />
      <CustomerReviews :reviews="reviews" :product="product" />
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import axios from "axios";
import AOS from "aos";
import "aos/dist/aos.css";
import { useStore } from "vuex";
import { useToast } from "vue-toast-notification";
import ProductInformation from "./ProductInformation.vue";
import Vendor from "./Vendor.vue";
import CustomerReviews from "./CustomerReviews.vue";
import ProductReturns from "./ProductReturns.vue";

const API_URL = import.meta.env.VITE_API_URL;
const storageBaseURL = import.meta.env.VITE_STORAGE_BASE_URL;

const route = useRoute();
const store = useStore();
const toast = useToast();
const product = ref(null);
const reviews = ref([]);
const averageRating = ref(null);
const currentImage = ref("");
const isLoading = ref(true);

const updateDocumentTitle = () => {
  document.title = product.value?.name
    ? `${product.value.name} | Maasai Market Online`
    : "Product Details | Maasai Market Online";
};

const fetchProduct = async () => {
  isLoading.value = true;
  try {
    const response = await axios.get(
      `${API_URL}/products/slug/${route.params.slug}`
    );
    product.value = response.data;
    currentImage.value = `${storageBaseURL}${product.value.main_image}`;
    updateDocumentTitle();
    await Promise.all([
      fetchReviews(product.value.id),
      fetchAverageRating(product.value.id),
    ]);
  } catch (error) {
    console.error("Failed to fetch product:", error);
    toast.error("Failed to load product details");
    document.title = "Product Details - Maasai Market Online";
  } finally {
    isLoading.value = false;
  }
};

const fetchReviews = async (productId) => {
  try {
    const response = await axios.get(
      `${API_URL}/reviews/products/${productId}`
    );
    reviews.value = response.data;
  } catch (error) {
    console.error("Failed to fetch reviews:", error);
    toast.error("Failed to load product reviews");
  }
};

const fetchAverageRating = async (productId) => {
  try {
    const response = await axios.get(
      `${API_URL}/reviews/products/${productId}/average`
    );
    averageRating.value = response.data.average_rating;
  } catch (error) {
    console.error("Failed to fetch average rating:", error);
    toast.error("Failed to load product rating");
  }
};

const formattedThumbnails = computed(() => {
  if (!product.value?.thumbnails) return [];
  const thumbnails = [...product.value.thumbnails];
  if (!thumbnails.includes(product.value.main_image)) {
    thumbnails.unshift(product.value.main_image);
  }
  return thumbnails.map((thumbnail) => `${storageBaseURL}${thumbnail}`);
});

const setCurrentImage = (image) => {
  currentImage.value = image;
};

const addToCart = () => {
  if (product.value && product.value.stock > 0) {
    const formattedProduct = {
      ...product.value,
      price: Number(product.value.price),
      quantity: 1,
    };
    store.dispatch("addToCart", formattedProduct);
    toast.success(`${formattedProduct.name} has been added to your cart!`);
  } else {
    toast.error("This product is currently out of stock.");
  }
};

const scrollToReviews = () => {
  const reviewsSection = document.querySelector(
    ".product-info > .product-reviews-section"
  );
  if (reviewsSection) {
    reviewsSection.scrollIntoView({ behavior: "smooth" });
  }
};

// Watch for route changes
watch(
  () => route.params.slug,
  async (newSlug, oldSlug) => {
    if (newSlug !== oldSlug) {
      await fetchProduct();
    }
  }
);

onMounted(() => {
  fetchProduct();
  AOS.init();
});
</script>

<style>
.loading-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 400px;
  gap: 20px;
}

.loading-spinner {
  width: 50px;
  height: 50px;
  border: 5px solid var(--secondary-color);
  border-top: 5px solid var(--accent-color);
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

.loading-text {
  font-size: 1.2rem;
  color: var(--dark-color);
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.product-overview-wrapper {
  display: flex;
  flex-direction: column;
  background: var(--background-color);
  padding: 40px 50px;
  border-radius: 10px;
}

.product-overview-container {
  display: flex;
  flex-wrap: wrap;
  gap: 50px;
  align-items: flex-start;
}

.product-overview-image {
  flex: 1;
  min-width: 300px;
  max-width: 500px;
}

.image-wrapper {
  position: relative;
  width: 100%;
  padding-bottom: 100%;
  overflow: hidden;
  border-radius: 8px;
}

.image-wrapper img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: opacity 0.3s ease;
}

.product-overview-thumbnails {
  display: flex;
  gap: 10px;
  margin-top: -5px;
  flex-wrap: wrap;
}

.thumbnail-wrapper {
  position: relative;
  width: 80px;
  height: 80px;
  overflow: hidden;
  border-radius: 5px;
}

.product-thumbnail {
  width: 100%;
  height: 100%;
  object-fit: cover;
  cursor: pointer;
  border: 2px solid transparent;
  transition: all 0.3s ease;
}

.product-thumbnail.active {
  border: 2px solid var(--dark-color);
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
}

.product-thumbnail:hover {
  transform: scale(1.05);
}

.product-overview-details {
  flex: 2;
  min-width: 300px;
}

.product-header {
  margin: 20px 0;
}

.product-title {
  font-size: 2.2rem;
  font-weight: var(--font-bold);
  color: var(--dark-color);
  margin-bottom: 10px;
  line-height: 1.2;
}

.product-description {
  font-size: 1.1rem;
  color: var(--text-color);
  line-height: 1.6;
  margin-top: 10px;
}

.product-status-badge {
  background: var(--green-color);
  color: var(--background-color);
  padding: 5px 15px;
  border-radius: 5px;
  font-size: 0.9rem;
  display: inline-block;
  font-weight: 500;
}

.product-status-badge-out {
  background: var(--error-message);
  color: var(--background-color);
  padding: 5px 15px;
  border-radius: 5px;
  font-size: 0.9rem;
  display: inline-block;
  font-weight: 500;
}

.product-price-rating {
  display: flex;
  align-items: center;
  gap: 20px;
  margin: 20px 0;
  padding: 15px 0;
  border-top: 1px solid var(--border-color);
  border-bottom: 1px solid var(--border-color);
}

.product-price-overview {
  font-size: 2rem;
  font-weight: bold;
  color: var(--accent-color);
}

.product-rating {
  display: flex;
  align-items: center;
  gap: 20px;
}

.product-average-rating {
  font-size: 1.2rem;
}

.product-review-link {
  color: var(--accent-color);
  text-decoration: none;
  font-weight: 500;
  transition: color 0.3s ease;
}

.product-review-link:hover {
  color: var(--dark-color);
  text-decoration: underline;
}

.product-add-to-cart {
  background: var(--dark-tint);
  color: var(--background-color);
  padding: 15px 30px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1.1rem;
  font-weight: 500;
  transition: all 0.3s ease;
  width: 100%;
  max-width: 400px;
}

.product-add-to-cart:hover:not(:disabled) {
  background-color: var(--dark-color);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.product-add-to-cart:disabled {
  background: var(--secondary-color);
  cursor: not-allowed;
  opacity: 0.7;
}

.product-vendor-info {
  margin-top: 30px;
  padding: 20px;
  background: var(--secondary-color);
  border-radius: 8px;
  border: 1px solid var(--border-color);
  max-width: 400px;
}

.product-vendor-info p {
  margin-bottom: 8px;
}

.product-vendor-info strong {
  color: var(--accent-color);
}

.product-info {
  margin-top: 40px;
  padding-top: 20px;
  border-top: 1px solid var(--border-color);
}

@media (max-width: 768px) {
  .product-overview-wrapper {
    padding: 20px;
  }

  .product-overview-container {
    flex-direction: column;
    align-items: center;
    gap: 30px;
  }

  .product-overview-image {
    width: 100%;
    max-width: 400px;
  }

  .product-overview-details {
    width: 100%;
  }

  .product-title {
    font-size: 1.8rem;
  }

  .product-description {
    font-size: 1rem;
  }

  .thumbnail-wrapper {
    width: 60px;
    height: 60px;
  }

  .product-price-rating {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }

  .product-price-overview {
    font-size: 1.8rem;
  }

  .product-overview-thumbnails {
    justify-content: center;
    margin-top: 20px;
  }

  .product-add-to-cart {
    width: 100%;
    max-width: none;
  }

  .product-vendor-info {
    width: 100%;
    max-width: none;
  }

  .loading-spinner {
    width: 40px;
    height: 40px;
  }

  .loading-text {
    font-size: 1rem;
  }
}
</style>
