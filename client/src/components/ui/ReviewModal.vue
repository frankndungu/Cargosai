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
import { ref, computed, defineProps } from "vue";
import { useStore } from "vuex";
import axios from "axios"; // Import axios

// Retrieve CSRF token from the meta tag
const csrfToken = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute("content");
axios.defaults.headers.common["X-CSRF-TOKEN"] = csrfToken;

// Define props
const props = defineProps({
  productId: {
    type: Number,
    required: true,
  },
});

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
    // Submit review to API
    submitReviewToAPI();
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

// Function to submit the review to the API
const submitReviewToAPI = async () => {
  try {
    const response = await axios.post(
      `${import.meta.env.VITE_API_URL}/reviews/products/${props.productId}`,
      {
        product_id: props.productId,
        reviewer_name: review.value.name,
        rating: review.value.rating,
        title: review.value.title,
        content: review.value.content,
      }
    );

    // Dispatch action to reset review data in Vuex
    store.dispatch("resetReview"); // Reset review data after submission

    // Optional: You can log or handle the response if needed
    console.log("Review submitted successfully:", response.data);

    closeModal(); // Close the modal after submitting
  } catch (error) {
    console.error("Failed to submit review:", error);
  }
};
</script>

<style scoped>
/* Review Modal */
.modal-overlay {
  margin: 0 auto;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.7);
  backdrop-filter: blur(10px);
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 10px; /* Padding to prevent content from touching screen edges */
}

.modal-container {
  background: var(--modal-color);
  border-radius: 8px;
  padding: 20px;
  max-width: 500px;
  width: 100%;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

h2 {
  margin: 0;
  font-size: 1.5em;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.5em;
  cursor: pointer;
}

.form-modal {
  display: flex;
  flex-direction: column;
  gap: 15px; /* Adds spacing between form elements */
}

.modal-form-group {
  display: flex;
  flex-direction: column;
}

label {
  margin-bottom: 5px;
  font-weight: bold;
}

input[type="text"],
textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid var(--dark-color);
  border-radius: 5px;
  box-sizing: border-box;
  background: var(--modal-color);
  transition: border-color 0.3s ease;
}

/* Highlight input fields with errors */
input[type="text"].error,
textarea.error {
  border-color: var(--error-border); /* Error border color */
  background-color: var(--error-background); /* Subtle error background */
}

.rating-stars {
  display: flex;
  margin-top: 5px;
}

.rating-stars .fa-star {
  font-size: 1.5em;
  margin-right: 5px;
  color: var(--rating-empty);
  cursor: pointer;
}

.rating-stars .fa-star.filled {
  color: var(--rating-color);
}

.error-msg {
  color: var(--error-message);
  font-size: 0.9em;
  margin-top: 5px;
  font-weight: bold;
}

/* Error message animations for better visibility */
.error-msg {
  animation: shake 0.2s ease-in-out;
}

@keyframes shake {
  0% {
    transform: translateX(0);
  }
  25% {
    transform: translateX(-5px);
  }
  50% {
    transform: translateX(5px);
  }
  75% {
    transform: translateX(-5px);
  }
  100% {
    transform: translateX(0);
  }
}

.form-buttons {
  display: flex;
  justify-content: flex-start;
  gap: 10px; /* Space between buttons */
  margin-top: 20px;
}

.add-review-btn,
.cancel-btn {
  padding: 10px 15px;
  border: none;
  border-radius: 5px;
  font-size: 1em;
  cursor: pointer;
}

.add-review-btn {
  background: var(--dark-tint);
  color: var(--background-color);
}

.add-review-btn:hover {
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.add-review-btn:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}

.cancel-btn {
  background: var(--dark-tint);
  color: var(--background-color);
}

.cancel-btn:hover {
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.cancel-btn:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}

.terms-text {
  font-size: 0.9em;
  margin-top: 15px;
  text-align: center;
}

.terms-link {
  color: var(--accent-color);
  text-decoration: none;
}

.terms-link:hover {
  text-decoration: underline;
}

@media (max-width: 768px) {
  .modal-container {
    width: 95%; /* Make modal container nearly full-width on smaller screens */
    padding: 15px; /* Reduce padding for smaller screens */
  }
}
</style>
