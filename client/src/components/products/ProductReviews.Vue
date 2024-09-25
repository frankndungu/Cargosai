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

    <div v-if="reviews.length === 0 && product">No reviews available.</div>
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

    <ReviewModal :isOpen="isModalOpen" @close="closeReviewModal" />
  </div>
</template>

<script setup>
import { computed } from "vue";
import { useStore } from "vuex";
import ReviewModal from "../ui/ReviewModal.vue";

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

const store = useStore();
const isModalOpen = computed(() => store.getters.isModalOpen);

const openReviewModal = () => {
  store.dispatch("openModal");
};

const closeReviewModal = () => {
  store.dispatch("closeModal");
};
</script>
