<template>
  <div class="modal-overlay" v-if="isOpen">
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
              @click="review.rating = star"
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
import { ref } from "vue";

const props = defineProps({
  isOpen: Boolean,
});
const emit = defineEmits(["close"]);

const review = ref({
  name: "",
  rating: 0,
  title: "",
  content: "",
});

const errors = ref({
  name: null,
  rating: null,
  title: null,
  content: null,
});

const closeModal = () => {
  emit("close");
};

const submitReview = () => {
  // Trigger validation when the user clicks "Add Review"
  if (validateForm()) {
    console.log("Review submitted:", review.value);
    closeModal();
  }
};

const validateForm = () => {
  let isValid = true;
  errors.value = { name: null, rating: null, title: null, content: null };

  // Name validation
  if (!review.value.name) {
    errors.value.name = "Name is required.";
    isValid = false;
  }

  // Rating validation
  if (!review.value.rating) {
    errors.value.rating = "Please select a rating.";
    isValid = false;
  }

  // Title validation
  if (!review.value.title) {
    errors.value.title = "Review title is required.";
    isValid = false;
  }

  // Content validation
  if (!review.value.content) {
    errors.value.content = "Please write your review.";
    isValid = false;
  }

  return isValid;
};
</script>
