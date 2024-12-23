<template>
  <div class="recovery-container">
    <!-- Account Recovery Form -->
    <form @submit.prevent="handleSubmit" class="recovery-form">
      <!-- Title -->
      <h1 class="recovery-title">Forgot your password?</h1>

      <!-- Description -->
      <p class="recovery-description">
        Don't fret! Just type in your email and we will send you a link to reset
        your password!
      </p>

      <!-- Email Input -->
      <div class="recovery-input-group">
        <label for="email" class="recovery-label">Your email</label>
        <input type="email" id="email" v-model="email" class="recovery-input" />
        <!-- Display email error -->
        <span v-if="errorMessage" class="error-message">{{
          errorMessage
        }}</span>
      </div>

      <!-- Reset Password Button -->
      <button type="submit" class="recovery-submit-btn">Send link</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";

// Form data
const email = ref("");
const errorMessage = ref("");
const successMessage = ref("");

// Handle form submission
const handleSubmit = async () => {
  // Reset error message
  errorMessage.value = "";
  successMessage.value = "";

  // Validate email
  if (!email.value) {
    errorMessage.value = "Email is required";
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
    errorMessage.value = "Please enter a valid email";
  }

  // Proceed if no errors
  if (!errorMessage.value) {
    try {
      // Send the password reset request to the API
      const response = await axios.post(
        `${import.meta.env.VITE_API_URL}/password/email`,
        { email: email.value }
      );

      // Handle success response
      successMessage.value = response.data.message;

      // Optionally, reset the email input after successful submission
      email.value = "";
    } catch (error) {
      // Handle error response
      if (error.response && error.response.data) {
        errorMessage.value =
          error.response.data.error ||
          "Failed to send reset link. Please try again.";
      } else {
        errorMessage.value = "An error occurred. Please try again later.";
      }
    }
  } else {
    console.log("Validation failed:", errorMessage.value);
  }
};
</script>

<style scoped>
.recovery-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background: var(--background-color);
  padding: 20px;
}

.recovery-form {
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
  display: flex;
  flex-direction: column;
}

/* Title */
.recovery-title {
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 10px;
  text-align: center;
}

/* Description */
.recovery-description {
  font-size: 1rem;
  margin-bottom: 20px;
  text-align: center;
}

/* Input Group */
.recovery-input-group {
  margin-bottom: 15px;
  display: flex;
  flex-direction: column;
}

.recovery-label {
  margin-bottom: 5px;
  font-weight: bold;
}

.recovery-input {
  padding: 10px;
  border: 1px solid var(--dark-color);
  border-radius: 5px;
  width: 100%;
  box-sizing: border-box;
}

/* Error message */
.error-message {
  color: var(--error-message);
  font-size: 0.9rem;
  margin-top: 5px;
}

/* Submit Button */
.recovery-submit-btn {
  background: var(--dark-tint);
  color: var(--background-color);
  padding: 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  width: 100%;
}

.recovery-submit-btn:hover {
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0);
}

.recovery-submit-btn:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}

/* Responsiveness */
@media (max-width: 600px) {
  .recovery-container {
    padding: 10px;
  }

  .recovery-form {
    padding: 15px;
  }

  .recovery-input {
    font-size: 14px;
  }

  .recovery-submit-btn {
    font-size: 14px;
  }
}
</style>
