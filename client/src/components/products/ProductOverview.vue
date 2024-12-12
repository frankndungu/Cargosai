<template>
  <div class="product-overview-wrapper" v-if="product" data-aos="fade-up">
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
        <h1 class="product-title-overview">
          {{ product.name }}, {{ product.description }}
        </h1>
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
      <ProductReviews :reviews="reviews" :product="product" />
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
import ProductReviews from "./ProductReviews.vue";

const API_URL = import.meta.env.VITE_API_URL;
const storageBaseURL = import.meta.env.VITE_STORAGE_BASE_URL;

const route = useRoute();
const store = useStore();
const toast = useToast();
const product = ref(null);
const reviews = ref([]);
const averageRating = ref(null);
const currentImage = ref("");

const updateDocumentTitle = () => {
  document.title = product.value?.name
    ? `${product.value.name} - Maasai Market Online`
    : "Product Details - Maasai Market Online";
};

const fetchProduct = async () => {
  try {
    const response = await axios.get(
      `${API_URL}/products/slug/${route.params.slug}`
    );
    product.value = response.data;
    currentImage.value = `${storageBaseURL}${product.value.main_image}`;
    updateDocumentTitle();
    await fetchReviews(product.value.id);
    await fetchAverageRating(product.value.id);
  } catch (error) {
    console.error("Failed to fetch product:", error);
    document.title = "Product Details - Maasai Market Online";
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
  if (product.value) {
    const formattedProduct = {
      ...product.value,
      price: Number(product.value.price),
      quantity: 1, // Default quantity to 1 for new additions
    };
    store.dispatch("addToCart", formattedProduct);
    toast.success(`${formattedProduct.name} has been added to your cart!`);
  } else {
    toast.error("Failed to add product to the cart. Please try again.");
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

// Watch for route changes to update the product and title dynamically
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
.product-overview-wrapper {
  display: flex;
  flex-direction: column;
  background: var(--background-color);
  padding: 40px 50px;
  margin: 0 auto;
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
  margin-top: 20px;
  flex-wrap: wrap;
}

.product-info {
  margin-top: 20px;
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

.product-status-badge {
  background: var(--green-color);
  color: var(--background-color);
  padding: 5px 10px;
  border-radius: 5px;
  font-size: 0.9rem;
  display: inline-block;
}

.product-status-badge-out {
  background: var(--error-message);
  color: var(--background-color);
  padding: 5px 10px;
  border-radius: 5px;
  font-size: 0.9rem;
  display: inline-block;
}

.product-title-overview {
  font-size: var(--font-size-large);
  font-weight: var(--font-bold);
  margin: 20px 0;
}

.product-price-rating {
  display: flex;
  align-items: center;
  gap: 20px;
  margin-bottom: 20px;
}

.product-price-overview {
  font-size: 1.9rem;
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
  margin-left: 10px;
  font-size: 1.2rem;
  text-decoration: underline;
  color: var(--dark-color);
  cursor: pointer;
  font-weight: bold;
}

.product-add-to-cart {
  background: var(--dark-tint);
  color: var(--background-color);
  padding: 15px 30px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.product-add-to-cart:hover {
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.product-add-to-cart:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}

.product-vendor-info {
  margin-top: 20px;
  padding: 15px;
  background: var(--secondary-color);
  border-radius: 5px;
  border: 1px solid #ddd;
  max-width: 400px;
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

  .thumbnail-wrapper {
    width: 60px;
    height: 60px;
  }

  .product-price-rating {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }

  .product-overview-thumbnails {
    justify-content: center;
  }
  .product-add-to-cart {
    width: 100%;
  }

  .product-vendor-info {
    width: 100%;
    max-width: none;
  }
}
</style>
