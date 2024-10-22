<template>
  <div class="dashboard">
    <Sidebar />
    <main class="main-content">
      <div class="order-details">
        <h2>Order Details for Order ID: {{ orderId }}</h2>
        <div v-if="order">
          <p>
            <strong>Date:</strong>
            {{ new Date(order.created_at).toLocaleDateString() }}
          </p>
          <p>
            <strong>Status:</strong>
            <span :class="getStatusClass(order.status)">{{
              order.status
            }}</span>
          </p>
          <p><strong>Total:</strong> ${{ order.total_price | currency }}</p>

          <h3>Items:</h3>
          <ul class="items-list">
            <li v-for="item in order.items" :key="item.id" class="item">
              <img
                :src="item.image_url"
                alt="Product image"
                class="product-image"
              />
              <div class="item-details">
                <p class="item-name">{{ item.name }}</p>
                <p class="item-price">
                  ${{ item.price | currency }} x {{ item.quantity }} = ${{
                    (item.price * item.quantity).toFixed(2)
                  }}
                </p>
              </div>
            </li>
          </ul>
        </div>
        <p v-else>Loading order details...</p>
        <button @click="goBack">Back to Orders</button>
      </div>
    </main>
  </div>
</template>

<script setup>
import Sidebar from "@/components/ui/Sidebar.vue";
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";

// Get the order ID from the route parameters
const route = useRoute();
const router = useRouter();
const orderId = route.params.id;
const API_URL = import.meta.env.VITE_API_URL;

// Reactive variable to hold the order details
const order = ref(null);

// Fetch order details from the backend based on orderId
const fetchOrderDetails = async () => {
  try {
    const response = await axios.get(`${API_URL}/orders/${orderId}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      },
    });
    order.value = response.data;
  } catch (error) {
    console.error("Failed to fetch order details:", error);
  }
};

// Lifecycle hook to fetch order details on component mount
onMounted(() => {
  fetchOrderDetails();
});

// Method to go back to the orders page
const goBack = () => {
  router.push({ path: "/dashboard/orders" });
};

// Helper method to determine the status class for styling
const getStatusClass = (status) => {
  switch (status.toLowerCase()) {
    case "pending":
      return "status-pending";
    case "shipped":
      return "status-shipped";
    case "delivered":
      return "status-delivered";
    case "canceled":
      return "status-canceled";
    default:
      return "";
  }
};

// Currency filter for formatting total amounts
const currency = (value) => {
  return parseFloat(value).toFixed(2);
};
</script>

<style scoped>
.order-details {
  padding: 20px;
}

.status-pending {
  color: orange;
}

.status-shipped {
  color: blue;
}

.status-delivered {
  color: green;
}

.status-canceled {
  color: red;
}

.items-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.item {
  display: flex;
  align-items: center;
  border-bottom: 1px solid #ddd;
  padding: 10px 0;
}

.product-image {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 5px;
  margin-right: 10px;
}

.item-details {
  flex-grow: 1;
}

.item-name {
  font-weight: 600;
}

.item-price {
  color: var(--secondary-color);
}

button {
  margin-top: 20px;
  padding: 10px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}
</style>
