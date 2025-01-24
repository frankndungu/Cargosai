<template>
  <div class="success-container">
    <div v-if="loading" class="loading-wrapper">
      <p class="loading-text">Loading order confirmation...</p>
    </div>
    <div v-else class="success-card">
      <div class="success-icon">
        <!-- Success Icon -->
      </div>
      <h1 class="success-title">Thank You for Your Order!</h1>
      <p class="success-subtitle">
        Your purchase has been successfully completed.
      </p>
      <div class="order-details">
        <div class="detail-item">
          <span class="detail-label">Order Number</span>
          <strong>{{ order.reference || "Not Available" }}</strong>
        </div>
        <div class="detail-item">
          <span class="detail-label">Confirmation sent to</span>
          <strong>{{ customerEmail }}</strong>
        </div>
        <div class="detail-item">
          <span class="detail-label">Payment Status</span>
          <strong>{{ order.payment_status || "Pending" }}</strong>
        </div>
        <div class="detail-item">
          <span class="detail-label">Shipping Fee</span>
          <strong>
            ${{
              !isNaN(Number(order.shipping_fee))
                ? Number(order.shipping_fee).toFixed(2)
                : "0.00"
            }}</strong
          >
        </div>
        <div class="detail-item">
          <span class="detail-label">Total Price</span>
          <strong
            >${{
              order.total_price ? order.total_price.toFixed(2) : "0.00"
            }}</strong
          >
        </div>
      </div>
      <div class="action-buttons">
        <button @click="goToHomePage" class="btn btn-continue">
          Continue Shopping
        </button>
        <button @click="viewOrderDetails" class="btn btn-view-order">
          View Order Details
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import axios from "axios";
import { useStore } from "vuex";
import { useToast } from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";

const toast = useToast();
const order = ref({});
const customerEmail = ref("");
const loading = ref(true);

const router = useRouter();
const route = useRoute();

const fetchUserDetails = async () => {
  // Check if the user is authenticated
  const token = localStorage.getItem("token");
  if (token) {
    try {
      const response = await axios.get(`${import.meta.env.VITE_API_URL}/user`, {
        headers: { Authorization: `Bearer ${token}` },
      });
      return response.data.email || "customer@example.com";
    } catch (error) {
      console.error("Error fetching user details:", error);
      toast.error("Unable to fetch user details. Using default email.");
      return "customer@example.com";
    }
  }
  return "customer@example.com"; // Default email for guests
};

const fetchOrderDetails = async () => {
  try {
    const orderId = route.query.order_id; // Get order ID from query params
    if (!orderId) throw new Error("Order ID is missing.");

    // For guests, no authorization header is needed
    const token = localStorage.getItem("token");
    const headers = token ? { Authorization: `Bearer ${token}` } : {}; // Skip header for guest

    const orderResponse = await axios.get(
      `${import.meta.env.VITE_API_URL}/orders/${orderId}`,
      { headers }
    );
    order.value = orderResponse.data;

    // Fetch user details for email display
    customerEmail.value = await fetchUserDetails();
  } catch (error) {
    console.error("Error fetching order details:", error);
    toast.error("Failed to load order details. Please try again later.");
  } finally {
    loading.value = false;
  }
};

const viewOrderDetails = async () => {
  try {
    const orderId = route.query.order_id;
    if (!orderId) throw new Error("Order ID is missing.");

    const token = localStorage.getItem("token");
    const headers = token ? { Authorization: `Bearer ${token}` } : {}; // Skip header for guest

    const response = await axios.get(
      `${import.meta.env.VITE_API_URL}/orders/${orderId}/details`,
      { headers }
    );
    router.push({ name: "OrderDetails", params: { id: response.data.id } });
  } catch (error) {
    console.error("Error fetching order details:", error);
    toast.error("Failed to load order details. Please try again later.");
  }
};

const goToHomePage = () => {
  router.push("/shop");
};

const store = useStore();

onMounted(() => {
  fetchOrderDetails();
  store.dispatch("clearCart");
  toast.success("Order confirmed successfully!", {
    position: "top-right",
    duration: 3000,
  });
});
</script>

<style scoped>
.success-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: var(--background-color);
  padding: 20px;
}

.success-card {
  background-color: #ffffff;
  border-radius: 16px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  padding: 40px;
  text-align: center;
  max-width: 600px;
  width: 100%;
  transform: translateY(-20px);
  opacity: 0;
  animation: fadeInUp 0.8s forwards;
  margin: 0 auto;
}

@keyframes fadeInUp {
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.loading-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  color: var(--dark-color);
  font-size: 1.2rem;
}

.loading-text {
  opacity: 0.7;
}

.success-icon {
  width: 100px;
  height: 100px;
  margin: 0 auto 20px;
  color: var(--green-color);
  stroke-width: 3;
  animation: popIn 0.6s ease-out;
}

.success-title {
  font-size: 1.8rem;
  font-weight: 700;
  color: var(--green-color);
  margin-bottom: 10px;
}

.success-subtitle {
  color: var(--dark-color);
  opacity: 0.7;
  margin-bottom: 25px;
}

.order-details {
  border-radius: 12px;
  padding: 25px;
  margin: 25px 0;
  text-align: left;
}

.detail-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 0;
  border-bottom: 1px solid #ccc;
}

.detail-item:last-child {
  border-bottom: none;
}

.detail-label {
  color: var(--dark-color);
  opacity: 0.6;
  font-weight: 600;
}

.action-buttons {
  display: flex;
  gap: 15px;
}

.btn {
  flex: 1;
  padding: 15px 20px;
  border: none;
  border-radius: 8px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  cursor: pointer;
  transition: all var(--transition-speed) ease;
}

.btn-continue {
  background-color: var(--dark-tint);
  color: var(--background-color);
}

.btn-view-order {
  background-color: var(--dark-tint);
  color: var(--background-color);
}

.btn:hover {
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
}

.loading-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.skeleton-loader {
  width: 100%;
  max-width: 400px;
  padding: 20px;
  background-color: var(--color-text-light);
  border-radius: 16px;
}

.skeleton-header,
.skeleton-line {
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  height: 20px;
  margin-bottom: 15px;
  border-radius: 4px;
  animation: skeleton-loading 1.5s infinite;
}

.skeleton-header {
  height: 100px;
  margin-bottom: 30px;
}

@keyframes skeleton-loading {
  0% {
    background-position: 200% 0;
  }
  100% {
    background-position: -200% 0;
  }
}

@media (max-width: 600px) {
  .success-card {
    padding: 20px;
    margin: 0 10px;
  }

  .action-buttons {
    flex-direction: column;
  }

  .btn {
    padding: 12px 15px;
  }
}
</style>
