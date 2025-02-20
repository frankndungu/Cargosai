<template>
  <div class="payment-failed-container">
    <div class="error-card">
      <h1 class="error-title">Payment Failed</h1>
      <p class="error-message">
        Oops! Something went wrong with your payment. Please try again or
        contact support if the issue persists.
      </p>
      <div class="actions">
        <button @click="retryPayment" class="retry-button">
          Retry Payment
        </button>
        <button @click="goToHome" class="home-button">Go to Homepage</button>
      </div>
      <p class="support-text">
        Need help? Contact us at
        <a href="mailto:support@maasaimarketonline.com"
          >support@maasaimarketonline.com</a
        >.
      </p>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from "vue-router";

const router = useRouter();

// Function to retry payment
const retryPayment = () => {
  // Redirect to the payment page or retry logic
  router.push("/checkout"); // Replace 'Checkout' with your actual route name
};

// Function to go back to the homepage
const goToHome = () => {
  router.push("/"); // Replace 'Home' with your actual route name
};
</script>

<style scoped>
/* Container for the error page */
.payment-failed-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-color: #f8f9fa;
  padding: 20px;
}

/* Error card styling */
.error-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  padding: 40px;
  max-width: 500px;
  width: 100%;
  text-align: center;
}

/* Error title styling */
.error-title {
  font-size: 2rem;
  color: #ea4335;
  margin-bottom: 20px;
}

/* Error message styling */
.error-message {
  font-size: 1.1rem;
  color: #333;
  margin-bottom: 30px;
}

/* Action buttons container */
.actions {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-bottom: 30px;
}

/* Button styling */
.retry-button,
.home-button {
  padding: 10px 20px;
  border: none;
  border-radius: 6px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.retry-button {
  background-color: var(--green-color); /* Blue color for retry */
  color: white;
}

.retry-button:hover {
  background-color: #004526; /* Darker blue on hover */
}

.home-button {
  background-color: #000; /* Gray color for home */
  color: white;
}

.home-button:hover {
  background-color: #0f0f0f; /* Darker gray on hover */
}

/* Support text styling */
.support-text {
  font-size: 0.9rem;
  color: #333;
}

.support-text a {
  color: #0f0f0f;
  text-decoration: none;
}

.support-text a:hover {
  text-decoration: underline;
}
</style>
