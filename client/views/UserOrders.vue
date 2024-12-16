<template>
  <div class="dashboard">
    <Sidebar />
    <main class="main-content">
      <div class="orders-container">
        <h2 class="title">My Orders</h2>
        <div class="filters">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search orders..."
            class="search-input"
          />
        </div>
        <div class="orders-table-wrapper">
          <table class="orders-table">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Payment Status</th>
                <th>Total</th>
                <th>Cancel Order</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="order in filteredOrders" :key="order.id">
                <td>{{ order.formatted_id }}</td>
                <td>{{ new Date(order.created_at).toLocaleDateString() }}</td>
                <td :class="getStatusClass(order.status)">
                  {{ order.status }}
                </td>
                <td>${{ order.total_price.toFixed(2) }}</td>
                <td>
                  <button
                    @click="cancelOrder(order.id)"
                    class="cancel-btn"
                    :disabled="order.status !== 'Pending'"
                  >
                    Cancel
                  </button>
                </td>
                <td>
                  <button @click="viewOrder(order.id)" class="view-btn">
                    View
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <p v-if="filteredOrders.length === 0" class="no-orders">
            No orders found.
          </p>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import Sidebar from "@/components/ui/Sidebar.vue";

// Set up router and data
const router = useRouter();
const searchQuery = ref("");
const orders = ref([]);
const API_URL = import.meta.env.VITE_API_URL; // Use the environment variable

// Fetch orders when the component is mounted
onMounted(async () => {
  try {
    const response = await axios.get(`${API_URL}/orders`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token")}`, // Replace with your auth mechanism if different
      },
    });
    // console.log(response.data); // Check the structure of response data
    orders.value = response.data; // Adjust this if the response has a different structure
  } catch (error) {
    // console.error("Failed to fetch orders:", error);
  }
});

// Filter the orders based on the search query
const filteredOrders = computed(() => {
  if (!searchQuery.value) return orders.value; // Return all orders if search query is empty
  return orders.value.filter((order) =>
    order.formatted_id
      .toString()
      .toLowerCase()
      .includes(searchQuery.value.toLowerCase())
  );
});

// Navigate to the order details page
const viewOrder = (orderId) => {
  router.push({ name: "OrderDetails", params: { id: orderId } });
};

// Cancel an order if it is in the 'Pending' state
const cancelOrder = async (orderId) => {
  try {
    await axios.put(
      `${API_URL}/orders/${orderId}/status`,
      { status: "Canceled" },
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );
    const order = orders.value.find((o) => o.id === orderId);
    if (order) {
      order.status = "Canceled";
    }
  } catch (error) {
    // console.error("Failed to cancel order:", error);
  }
};

// Get CSS classes for order status
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
</script>

<style scoped>
.orders-container {
  padding: 16px;
}

.title {
  font-size: 24px;
  margin-bottom: 16px;
}

.filters {
  margin-bottom: 12px;
}

.search-input {
  padding: 8px;
  width: 100%;
  max-width: 300px;
  border-radius: 4px;
  border: 1px solid;
  background: var(--background-color);
}

.orders-table-wrapper {
  overflow-x: auto;
}

.orders-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 16px;
}

.orders-table th,
.orders-table td {
  padding: 12px;
  text-align: left;
  border: 1px solid #ddd;
}

.orders-table th {
  background: var(--table-color);
}

.status-pending {
  color: var(--pending-color);
  font-weight: 700;
}

.status-shipped {
  color: var(--shipped-color);
  font-weight: 700;
}

.status-delivered {
  color: var(--green-color);
  font-weight: 700;
}

.status-canceled {
  color: var(--canceled-color);
  font-weight: 700;
}

.view-btn {
  background: var(--dark-tint);
  color: var(--background-color);
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
}

.view-btn:hover {
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.view-btn:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}

.cancel-btn {
  background: transparent;
  color: var(--cancel-color);
  border: 1px solid;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
}

.cancel-btn:disabled {
  background: var(--background-color);
  color: var(--dark-color);
  cursor: not-allowed;
}

.no-orders {
  text-align: center;
  color: #888;
}
</style>
