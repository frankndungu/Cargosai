<template>
  <div class="modal-overlay" v-if="isModalOpen">
    <div class="modal-container">
      <div class="modal-header">
        <h2>Add Review</h2>
        <button class="close-btn" @click="closeModal">
          <i class="fa-solid fa-x"></i>
        </button>
      </div>
      <form @submit.prevent="submitReview" class="form-modal">
        <!-- Name Field -->
        <div class="modal-form-group">
          <label for="name">Name</label>
          <input type="text" id="name" v-model="review.name" />
          <span v-if="errors.name" class="error-msg">{{ errors.name }}</span>
        </div>

        <!-- Rating Field -->
        <div class="modal-form-group">
          <label for="rating">Rating</label>
          <div class="rating-stars">
            <i
              v-for="star in 5"
              :key="star"
              class="fa fa-star"
              :class="{ filled: star <= review.rating }"
              @click="updateRating(star)"
            ></i>
          </div>
          <span v-if="errors.rating" class="error-msg">{{
            errors.rating
          }}</span>
        </div>

        <!-- Review Title Field -->
        <div class="modal-form-group">
          <label for="title">Review Title</label>
          <input type="text" id="title" v-model="review.title" />
          <span v-if="errors.title" class="error-msg">{{ errors.title }}</span>
        </div>

        <!-- Review Content Field -->
        <div class="modal-form-group">
          <label for="review">Review</label>
          <textarea id="review" v-model="review.content" rows="4"></textarea>
          <span v-if="errors.content" class="error-msg">{{
            errors.content
          }}</span>
        </div>

        <!-- Submit and Cancel Buttons -->
        <div class="form-buttons">
          <button type="submit" class="add-review-btn">Add Review</button>
          <button type="button" class="cancel-btn" @click="closeModal">
            Cancel
          </button>
        </div>

        <!-- Terms and Conditions -->
        <p class="terms-text">
          By publishing this review, you agree with our
          <router-link to="/terms-of-service" class="terms-link"
            >terms and conditions</router-link
          >
        </p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useStore } from "vuex";

// Access Vuex store
const store = useStore();

// Fetch modal open state from Vuex store
const isModalOpen = computed(() => store.getters.isModalOpen);

// Fetch review data from Vuex store
const review = computed(() => store.state.review);

// Local errors ref
const errors = ref({
  name: null,
  rating: null,
  title: null,
  content: null,
});

// Dispatch action to close modal
const closeModal = () => {
  store.dispatch("closeModal");
};

// Submit the review form
const submitReview = () => {
  if (validateForm()) {
    console.log("Review submitted:", review.value);
    closeModal(); // Close modal after submitting
  }
};

// Validate the form
const validateForm = () => {
  let isValid = true;
  errors.value = { name: null, rating: null, title: null, content: null };

  if (!review.value.name) {
    errors.value.name = "Name is required.";
    isValid = false;
  }
  if (!review.value.rating) {
    errors.value.rating = "Please select a rating.";
    isValid = false;
  }
  if (!review.value.title) {
    errors.value.title = "Review title is required.";
    isValid = false;
  }
  if (!review.value.content) {
    errors.value.content = "Please write your review.";
    isValid = false;
  }

  return isValid;
};

// Dispatch an action to update the rating in the Vuex store
const updateRating = (rating) => {
  store.dispatch("updateReviewRating", rating);
};
</script>
