<template>
  <div class="paypal-callback-container">
    <div class="callback-card">
      <!-- Processing State -->
      <div v-if="status === 'processing'" class="status-container">
        <div class="spinner"></div>
        <h2 class="status-title">Processing Payment</h2>
        <p class="status-message">
          Please wait while we confirm your payment...
        </p>
      </div>

      <!-- Success State -->
      <div v-if="status === 'success'" class="status-container">
        <div class="icon-container success-icon-container">
          <svg
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M5 13l4 4L19 7"
            ></path>
          </svg>
        </div>
        <h2 class="status-title">Payment Successful!</h2>
        <p class="status-message">Redirecting to order confirmation...</p>
      </div>

      <!-- Error State -->
      <div v-if="status === 'error'" class="status-container">
        <div class="icon-container error-icon-container">
          <svg
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            ></path>
          </svg>
        </div>
        <h2 class="status-title">Payment Failed</h2>
        <p class="error-message">{{ error }}</p>
        <p class="status-message">Redirecting to error page...</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import { useStore } from "vuex";

const router = useRouter();
const store = useStore();
const status = ref("processing");
const error = ref("");

onMounted(async () => {
  try {
    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const token = urlParams.get("token");
    const PayerID = urlParams.get("PayerID");
    const orderId = localStorage.getItem("pending_order_id");

    if (!token || !PayerID || !orderId) {
      throw new Error("Missing required parameters");
    }

    // Call success endpoint
    const response = await axios.get(
      `${import.meta.env.VITE_API_URL}/paypal/success`,
      {
        params: { token, PayerID, order_id: orderId },
      }
    );

    if (response.data.success) {
      status.value = "success";
      // Clear pending order ID
      localStorage.removeItem("pending_order_id");
      // Clear cart
      store.dispatch("clearCart");

      // Redirect to success page after 2 seconds
      setTimeout(() => {
        router.push({
          name: "OrderSuccess",
          params: {
            orderId: orderId,
          },
        });
      }, 2000);
    } else {
      throw new Error(response.data.message || "Payment failed");
    }
  } catch (err) {
    console.error("PayPal callback error:", err);
    status.value = "error";
    error.value = err.response?.data?.message || "Payment processing failed";

    // Redirect to error page after 2 seconds
    setTimeout(() => {
      router.push({
        name: "OrderFailed",
        params: {
          error: error.value,
        },
      });
    }, 2000);
  }
});
</script>

<style scoped>
.paypal-callback-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background-color: #f5f5f5;
  font-family: system-ui, -apple-system, sans-serif;
}

.callback-card {
  padding: 2rem;
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  max-width: 400px;
  width: 90%;
}

.status-container {
  text-align: center;
}

.spinner {
  width: 48px;
  height: 48px;
  border: 3px solid #e0e0e0;
  border-bottom-color: #0070ba;
  border-radius: 50%;
  margin: 0 auto;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.icon-container {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: center;
}

.success-icon-container {
  background-color: #e6f4ea;
  color: #34a853;
}

.error-icon-container {
  background-color: #fce8e8;
  color: #ea4335;
}

.status-title {
  margin-top: 1rem;
  font-size: 1.25rem;
  font-weight: 600;
  color: #333;
}

.status-message {
  margin-top: 0.5rem;
  color: #666;
}

.error-message {
  margin-top: 0.5rem;
  color: #ea4335;
}
</style>
