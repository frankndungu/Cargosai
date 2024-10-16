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
                <th>Status</th>
                <th>Total</th>
                <th>Cancel Order</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="order in filteredOrders" :key="order.id">
                <td>{{ order.id }}</td>
                <td>{{ order.date }}</td>
                <td :class="getStatusClass(order.status)">
                  {{ order.status }}
                </td>
                <td>${{ order.total | currency }}</td>
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
import { ref, computed } from "vue";
import { useRouter } from "vue-router"; // Import useRouter
import Sidebar from "@/components/ui/Sidebar.vue";

const router = useRouter(); // Initialize router
const searchQuery = ref("");
const orders = ref([
  {
    id: "001",
    date: "2024-10-01",
    status: "Pending",
    total: 1500,
  },
  {
    id: "002",
    date: "2024-10-05",
    status: "Shipped",
    total: 3000,
  },
  {
    id: "003",
    date: "2024-10-10",
    status: "Delivered",
    total: 2000,
  },
  {
    id: "004",
    date: "2024-10-12",
    status: "Canceled",
    total: 1200,
  },
]);

const filteredOrders = computed(() => {
  return orders.value.filter((order) =>
    order.id.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

// Update viewOrder to use router.push with the correct path
const viewOrder = (orderId) => {
  router.push({ name: "OrderDetails", params: { id: orderId } }); // Pass the orderId as a parameter
};

const cancelOrder = (orderId) => {
  const order = orders.value.find((o) => o.id === orderId);
  if (order && order.status === "Pending") {
    order.status = "Canceled";
    console.log("Order canceled:", orderId);
  }
};

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

.cancel-btn:hover:not(:disabled) {
  background: transparent;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.cancel-btn:focus:not(:disabled) {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}

.no-orders {
  text-align: center;
  color: #888;
}
</style>
