<template>
  <div class="product-reviews-section">
    <div class="product-reviews-header" v-if="product">
      <div class="reviews-header-title">
        <h1>{{ product.name }}</h1>
        <span class="reviews-count">{{ reviews.length }} result(s)</span>
      </div>
      <button class="write-review-btn" @click="openReviewModal">
        Write a review
      </button>
    </div>

    <div v-if="reviews.length === 0 && product">
      Be the first one to review this product.
    </div>
    <div v-else>
      <div class="review-card" v-for="review in reviews" :key="review.id">
        <div class="review-header">
          <span class="reviewer-name">{{ review.reviewer_name }}</span>
          <div class="review-stars">
            <i
              class="fa fa-star"
              :class="{ filled: rating <= review.rating }"
              v-for="rating in 5"
              :key="rating"
            ></i>
          </div>
        </div>
        <div class="review-content">
          <h4 class="review-title">{{ review.title }}</h4>
          <p class="review-text">{{ review.content }}</p>
        </div>
      </div>
    </div>

    <!-- Only render the ReviewModal if product is defined -->
    <ReviewModal
      v-if="product"
      :productId="product.id"
      :isOpen="isModalOpen"
      @close="closeReviewModal"
    />
  </div>
</template>

<script setup>
import { computed } from "vue";
import { useStore } from "vuex";
import ReviewModal from "@/components/ui/ReviewModal.vue";

// Props
const props = defineProps({
  reviews: {
    type: Array,
    required: true,
  },
  product: {
    type: Object,
    required: true,
  },
});

// Access Vuex store
const store = useStore();

// Modal state
const isModalOpen = computed(() => store.getters.isModalOpen);

// Open the review modal
const openReviewModal = () => {
  store.dispatch("openModal");
};

// Close the review modal
const closeReviewModal = () => {
  store.dispatch("closeModal");
};
</script>

<style scoped>
/* Product Reviews */
.product-reviews-section {
  margin-top: 40px;
  padding: 10px 0px;
}

.product-reviews-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.reviews-header-title {
  display: flex;
  align-items: center;
  gap: 20px;
}

.product-reviews-header h1 {
  font-size: 22px;
  font-weight: bold;
  margin-right: 10px; /* Small space between title and count */
}

.reviews-count {
  font-size: 14px;
  color: var(--reviews-count);
}

.product-name-title h3 {
  font-size: 16px;
  font-weight: 700;
  margin-right: 10px; /* Small space between title and count */
  margin-bottom: 20px;
}

.write-review-btn {
  background: var(--dark-tint);
  color: var(--background-color);
  padding: 8px 16px;
  font-size: 14px;
  border: none;
  cursor: pointer;
  border-radius: 5px;
}

.write-review-btn:hover {
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.write-review-btn:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}

/* Review card layout */
.review-card {
  background-color: var(--card-color);
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 20px;
}

.review-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

/* Reviewer name */
.reviewer-name {
  font-size: 16px;
  font-weight: bold;
}

/* Star ratings */
.review-stars {
  display: flex;
}

.review-stars i {
  font-size: 16px;
  color: var(--rating-color);
}

.review-stars i.filled {
  color: var(--rating-color); /* Filled star color */
}

.review-stars i {
  color: var(--rating-empty); /* Empty star color */
}

/* Review content */
.review-content {
  margin-top: 10px;
}

.review-title {
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 5px;
}

.review-text {
  font-size: 14px;
  color: var(--dark-color);
}

/* ----- Responsive CSS ----- */
@media (max-width: 768px) {
  .product-reviews-section {
    padding: 10px 0;
  }

  .product-reviews-header,
  .product-name-title {
    flex-direction: column;
    align-items: flex-start;
    padding: 10px 0px;
  }

  /* Ensure button aligns to the left in mobile view */
  .write-review-btn {
    margin-top: 10px;
    width: 100%;
  }
  .product-reviews-header h1 {
    font-size: 18px;
  }
  .product-reviews-header h3 {
    font-size: 20px;
  }

  .reviews-count {
    font-size: 13px;
  }

  .review-title {
    font-size: 16px;
  }

  .review-text {
    font-size: 13px;
  }
}

@media (max-width: 480px) {
  .product-reviews-header h3 {
    font-size: 18px;
  }

  .reviews-count {
    font-size: 12px;
  }

  .review-title {
    font-size: 14px;
  }

  .review-text {
    font-size: 12px;
  }

  /* Padding for the entire section on mobile */
  .product-reviews-section {
    padding: 10px 0;
  }

  /* Decrease padding inside the review card */
  .review-card {
    padding: 15px;
  }
}
</style>
