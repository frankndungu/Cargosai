<template>
  <div class="reset-container">
    <!-- Password Reset Form -->
    <form @submit.prevent="handleSubmit" class="reset-form">
      <!-- Title -->
      <h1 class="reset-title">Reset your password</h1>

      <!-- Description -->
      <p class="reset-description">Please enter your new password below.</p>

      <!-- Password Input -->
      <div class="reset-input-group">
        <label for="password" class="reset-label">New Password</label>
        <input
          type="password"
          id="password"
          v-model="password"
          class="reset-input"
        />
        <span v-if="errorMessage" class="error-message">{{
          errorMessage
        }}</span>
      </div>

      <!-- Confirm Password Input -->
      <div class="reset-input-group">
        <label for="confirmPassword" class="reset-label"
          >Confirm Password</label
        >
        <input
          type="password"
          id="confirmPassword"
          v-model="confirmPassword"
          class="reset-input"
        />
        <span v-if="confirmErrorMessage" class="error-message">{{
          confirmErrorMessage
        }}</span>
      </div>

      <!-- Reset Password Button -->
      <button type="submit" class="reset-submit-btn">Reset Password</button>

      <!-- Success Message -->
      <div v-if="successMessage" class="success-message">
        {{ successMessage }}
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import axios from "axios";

// Form data
const password = ref("");
const confirmPassword = ref("");
const errorMessage = ref("");
const confirmErrorMessage = ref("");
const successMessage = ref("");
const route = useRoute(); // Access the route

// Get token and email from URL query parameters
const token = route.query.token;
const email = route.query.email;

// Handle form submission
const handleSubmit = async () => {
  // Reset error messages
  errorMessage.value = "";
  confirmErrorMessage.value = "";
  successMessage.value = "";

  // Validate the password fields
  if (password.value !== confirmPassword.value) {
    confirmErrorMessage.value = "Passwords do not match!";
    return;
  }

  try {
    // Make API call to reset the password
    const response = await axios.post(
      "http://localhost:8000/api/password/reset",
      {
        email: email,
        token: token,
        password: password.value,
        password_confirmation: confirmPassword.value,
      }
    );

    // Handle success
    successMessage.value = response.data.message;
  } catch (error) {
    errorMessage.value =
      error.response?.data?.error || "Failed to reset password.";
  }
};

// On mounted, you can log the parameters for debugging (optional)
onMounted(() => {
  console.log("Token:", token);
  console.log("Email:", email);
});
</script>

<style scoped>
.reset-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background: var(--background-color);
  padding: 20px;
}

.reset-form {
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
  display: flex;
  flex-direction: column;
}

/* Title */
.reset-title {
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 10px;
  text-align: center;
}

/* Description */
.reset-description {
  font-size: 1rem;
  margin-bottom: 20px;
  text-align: center;
}

/* Input Group */
.reset-input-group {
  margin-bottom: 15px;
  display: flex;
  flex-direction: column;
}

.reset-label {
  margin-bottom: 5px;
  font-weight: bold;
}

.reset-input {
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
.reset-submit-btn {
  background: var(--dark-tint);
  color: var(--background-color);
  padding: 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  width: 100%;
}

.reset-submit-btn:hover {
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0);
}

.reset-submit-btn:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}

/* Responsiveness */
@media (max-width: 600px) {
  .reset-container {
    padding: 10px;
  }

  .reset-form {
    padding: 15px;
  }

  .reset-input {
    font-size: 14px;
  }

  .reset-submit-btn {
    font-size: 14px;
  }
}
</style>
