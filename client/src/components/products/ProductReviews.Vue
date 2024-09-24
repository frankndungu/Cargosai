<template>
  <div class="product-reviews-section">
    <!-- Heading for the reviews -->
    <div class="product-reviews-header">
      <div class="reviews-header-title">
        <h3>Product Reviews</h3>
        <span class="reviews-count">1 result</span>
      </div>
      <button class="write-review-btn" @click="openReviewModal">
        Write a review
      </button>
    </div>

    <!-- Individual review section -->
    <div class="review-card">
      <div class="review-header">
        <span class="reviewer-name">Jenny Graham</span>
        <div class="review-stars">
          <i class="fa fa-star filled"></i>
          <i class="fa fa-star filled"></i>
          <i class="fa fa-star filled"></i>
          <i class="fa fa-star"></i>
          <i class="fa fa-star"></i>
        </div>
      </div>
      <div class="review-content">
        <h4 class="review-title">
          Beautifully Handcrafted and Culturally Rich
        </h4>
        <p class="review-text">
          I recently purchased this Maasai beaded necklace from Maasai Market
          Online, and I couldn't be happier with my purchase! The craftsmanship
          is absolutely stunning, and you can tell that every bead was carefully
          placed with precision and attention to detail.
        </p>
      </div>
    </div>

    <!-- Modal Component -->
    <ReviewModal :isOpen="isModalOpen" @close="closeReviewModal" />
  </div>
</template>

<script setup>
import { computed } from "vue";
import { useStore } from "vuex";
import ReviewModal from "../ui/ReviewModal.vue";

const store = useStore();

// Access modal state from Vuex
const isModalOpen = computed(() => store.getters.isModalOpen);

// Open modal by dispatching Vuex action
const openReviewModal = () => {
  store.dispatch("openModal");
};

// Close modal by dispatching Vuex action
const closeReviewModal = () => {
  store.dispatch("closeModal");
};
</script>
