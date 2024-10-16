<template>
  <div class="dashboard">
    <Sidebar />
    <main class="main-content">
      <div class="order-details">
        <h2>Order Details for Order ID: {{ orderId }}</h2>
        <div v-if="order">
          <p><strong>Date:</strong> {{ order.date }}</p>
          <p>
            <strong>Status:</strong>
            <span :class="getStatusClass(order.status)">{{
              order.status
            }}</span>
          </p>
          <p><strong>Total:</strong> ${{ order.total | currency }}</p>
          <h3>Items:</h3>
          <ul>
            <li v-for="item in order.items" :key="item.id">
              {{ item.name }} - ${{ item.price }} x {{ item.quantity }}
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

const route = useRoute();
const router = useRouter();

// Get the order ID from the route parameters
const orderId = route.params.id;

// Mock data for demonstration purposes
const orders = [
  {
    id: "001",
    date: "2024-10-01",
    status: "Pending",
    total: 1500,
    items: [
      { id: "item1", name: "Product A", price: 500, quantity: 2 },
      { id: "item2", name: "Product B", price: 500, quantity: 1 },
    ],
  },
  {
    id: "002",
    date: "2024-10-05",
    status: "Shipped",
    total: 3000,
    items: [{ id: "item3", name: "Product C", price: 1000, quantity: 3 }],
  },
  {
    id: "003",
    date: "2024-10-10",
    status: "Delivered",
    total: 2000,
    items: [{ id: "item4", name: "Product D", price: 1000, quantity: 2 }],
  },
];

// Reactive variable to hold the order details
const order = ref(null);

// Fetch order details based on orderId
const fetchOrderDetails = () => {
  const foundOrder = orders.find((o) => o.id === orderId);
  order.value = foundOrder || null; // Set the order or null if not found
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
