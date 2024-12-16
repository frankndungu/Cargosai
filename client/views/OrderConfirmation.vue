<!--Order Confirmation-->
<template>
  <div class="success-container">
    <div v-if="loading" class="loading-message">Loading order details...</div>
    <div v-else class="success-card">
      <div class="success-icon">✓</div>
      <h1>Thank You for Your Order!</h1>
      <p>Your purchase has been successfully completed.</p>
      <div class="order-details">
        <p>
          Order Number:
          <strong>{{ order.reference || "Not Available" }}</strong>
        </p>
        <p>
          Total Price:
          <strong
            >${{
              order.total_price ? order.total_price.toFixed(2) : "0.00"
            }}</strong
          >
        </p>
        <p>
          Payment Status:
          <strong>{{ order.status || "Pending" }}</strong>
        </p>
        <p>
          Confirmation sent to:
          <strong>{{ customerEmail }}</strong>
        </p>
      </div>
      <div class="action-buttons">
        <button @click="goToHomePage" class="btn-continue">
          Continue Shopping
        </button>
        <button @click="viewOrderDetails" class="btn-view-order">
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

const order = ref({});
const customerEmail = ref("");
const loading = ref(true);

const router = useRouter();
const route = useRoute();

const fetchUserDetails = async () => {
  try {
    const response = await axios.get(`${import.meta.env.VITE_API_URL}/user`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      },
    });
    return response.data.email || "customer@example.com";
  } catch (error) {
    console.error("Error fetching user details:", error);
    return "customer@example.com"; // Default email if the user fetch fails
  }
};

const fetchOrderDetails = async () => {
  try {
    const orderId = route.query.order_id; // Fetch the order ID from query params
    if (!orderId) {
      throw new Error("Order ID is missing.");
    }
    const orderResponse = await axios.get(
      `${import.meta.env.VITE_API_URL}/orders/${orderId}`,
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`, // Replace with your auth mechanism if different
        },
      }
    );
    order.value = orderResponse.data;

    // Fetch user details after the order details are loaded
    customerEmail.value = await fetchUserDetails();
  } catch (error) {
    console.error("Error fetching order details:", error);
    alert("Failed to load order details. Please try again later.");
  } finally {
    loading.value = false;
  }
};

const viewOrderDetails = async () => {
  try {
    const orderId = route.query.order_id; // Fetch the order ID from query params
    if (!orderId) {
      throw new Error("Order ID is missing.");
    }
    const response = await axios.get(
      `${import.meta.env.VITE_API_URL}/orders/${orderId}/details`,
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`, // Replace with your auth mechanism if different
        },
      }
    );
    // Use router.push with the `id` from the response to navigate to the order details page
    router.push({ name: "OrderDetails", params: { id: response.data.id } });
  } catch (error) {
    console.error("Error fetching detailed order information:", error);
    alert("Failed to load order details. Please try again later.");
  }
};

const goToHomePage = () => {
  router.push("/shop");
};

// Fetch order details on component mount
onMounted(fetchOrderDetails);
</script>

<style scoped>
.success-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: var(--background-color);
}

.success-card {
  background-color: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.125);
  padding: 40px;
  text-align: center;
  max-width: 500px;
  width: 100%;
  animation: fadeIn 1.2s ease-out;
}

.order-details {
  background-color: #f9f9f9;
  padding: 20px;
  border-radius: 8px;
  margin: 20px 0;
  text-align: left;
  position: relative;
  overflow: hidden;
}

.detail-shimmer {
  position: relative;
}

.shimmer-text {
  position: relative;
  color: var(--dark-color);
  display: inline-block;
}

.shimmer-text::after {
  content: "";
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  transform: skewX(-20deg);
  background: linear-gradient(
    to right,
    transparent 0%,
    rgba(255, 255, 255, 0.4) 50%,
    transparent 100%
  );
  animation: shimmer 7s infinite linear;
}

.success-icon {
  font-size: 80px;
  color: var(--green-color);
  margin-bottom: 20px;
  animation: popIn 1s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.9);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

@keyframes popIn {
  from {
    transform: scale(0);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}

h1 {
  color: var(--dark-color);
  margin-bottom: 15px;
  animation: slideIn 1s ease-out;
}

@keyframes slideIn {
  from {
    transform: translateY(-20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.action-buttons {
  display: flex;
  justify-content: space-between;
  gap: 15px;
}

.btn-continue,
.btn-view-order {
  flex: 1;
  padding: 12px 20px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
  transition: all 0.3s ease;
}

.btn-continue {
  background-color: var(--dark-tint);
  color: white;
}

.btn-view-order {
  background-color: var(--green-color);
  color: white;
}
</style>
