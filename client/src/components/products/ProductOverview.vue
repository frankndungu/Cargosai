<template>
  <div class="product-overview-wrapper" v-if="product" data-aos="fade-up">
    <!-- Product Image Section -->
    <div class="product-overview-container" data-aos="fade-left">
      <div class="product-overview-image">
        <img :src="product.main_image" alt="Product Image" />
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
      <img
        v-for="(thumbnail, index) in product.thumbnails"
        :src="thumbnail.src"
        :alt="thumbnail.alt"
        :key="index"
        class="product-thumbnail"
        @click="setCurrentImage(thumbnail.src)"
      />
    </div>
    <div class="product-info">
      <ProductInformation />
      <Vendor />
      <ProductReviews :reviews="reviews" :product="product" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import axios from "axios";
import AOS from "aos";
import "aos/dist/aos.css";
import { useStore } from "vuex";
import { useToast } from "vue-toast-notification";
import ProductInformation from "../products/ProductInformation.vue";
import Vendor from "../products/Vendor.vue";
import ProductReviews from "../products/ProductReviews.vue";

const API_URL = import.meta.env.VITE_API_URL;

const route = useRoute();
const store = useStore();
const toast = useToast(); // Initialize toast
const product = ref(null);
const reviews = ref([]);
const averageRating = ref(null);

// Fetch product data based on the slug
const fetchProduct = async () => {
  try {
    const response = await axios.get(
      `${API_URL}/products/slug/${route.params.slug}`
    );
    product.value = response.data;
    await fetchReviews(product.value.id); // Fetch reviews after getting the product
    await fetchAverageRating(product.value.id); // Fetch average rating
  } catch (error) {
    console.error("Failed to fetch product:", error);
  }
};

// Fetch reviews based on product ID
const fetchReviews = async (productId) => {
  try {
    const response = await axios.get(
      `${API_URL}/reviews/products/${productId}`
    );
    reviews.value = response.data; // Assign fetched reviews to the reviews ref
  } catch (error) {
    console.error("Failed to fetch reviews:", error);
  }
};

// Fetch average rating based on product ID
const fetchAverageRating = async (productId) => {
  try {
    const response = await axios.get(
      `${API_URL}/reviews/products/${productId}/average`
    );
    averageRating.value = response.data.average_rating; // Set average rating
  } catch (error) {
    console.error("Failed to fetch average rating:", error);
  }
};

// Update the product image when a thumbnail is clicked
const setCurrentImage = (image) => {
  product.value.image_url = image;
};

// Add product to cart
const addToCart = () => {
  if (product.value) {
    store.dispatch("addToCart", product.value);
    toast.success(`${product.value.name} has been added to your cart!`); // Show toast notification
  }
};

// Scroll to ProductReviews component
const scrollToReviews = () => {
  const reviewsSection = document.querySelector(
    ".product-info > .product-reviews-section"
  );
  if (reviewsSection) {
    reviewsSection.scrollIntoView({ behavior: "smooth" });
  }
};

// Initialize fetching of product and AOS
onMounted(() => {
  fetchProduct();
  AOS.init(); // Initialize AOS for scroll animations
});
</script>

<style>
/* Product Overview */
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
  max-width: 300px;
}
.product-overview-image img {
  width: 100%;
  height: auto;
  border-radius: 8px;
  object-fit: cover;
}
.product-overview-details {
  flex: 2;
}

/* Product Status Badge */
.product-status {
  margin-bottom: 10px;
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

/* Product Title */
.product-title-overview {
  font-size: var(--font-size-large);
  font-weight: var(--font-bold);
  margin-bottom: 20px;
}

/* Price and Rating Section */
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

/* Add to Cart Button */
.product-add-to-cart {
  background: var(--dark-tint);
  color: var(--background-color);
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1rem;
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
  margin-top: 15px;
  padding: 10px;
  width: 45%;
  background: var(--secondary-color);
  border-radius: 5px;
  border: 1px solid #ddd;
}

/* Thumbnails */
.product-overview-thumbnails {
  display: flex;
  gap: 10px;
  margin-left: -5px;
  justify-content: flex-start;
}
.product-thumbnail {
  width: 70px;
  height: 70px;
  object-fit: cover;
  border: 1px solid var(--dark-color-border);
  border-radius: 5px;
  cursor: pointer;
  transition: border-color 0.3s;
  margin-bottom: 40px;
}
.product-thumbnail:hover {
  border-color: var(--dark-color);
}

/* Responsive Styles */
@media (max-width: 768px) {
  .product-overview-wrapper {
    padding: 40px 20px;
  }
  .product-overview-container {
    flex-direction: column;
    align-items: center;
  }
  .product-overview-image,
  .product-overview-details {
    max-width: 100%;
  }
  .product-add-to-cart {
    width: 100%;
  }
  .product-overview-thumbnails {
    margin-top: 20px;
    justify-content: center;
  }
  .product-vendor-info {
    margin-top: 20px;
    width: 100%;
  }
  .product-title-overview {
    font-size: var(--h2-font-size);
  }
}
</style>
