<template>
  <div class="orders-container">
    <h1>Admin Orders</h1>

    <!-- Loading Spinner -->
    <div v-if="loading" class="loading-spinner"></div>

    <!-- Orders Table -->
    <table v-if="!loading && orders.length > 0" class="orders-table">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>User ID</th>
          <th>Amount Paid</th>
          <th>Payment Status</th>
          <th>Delivery Status</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="order in orders" :key="order.id">
          <td>{{ order.formatted_id }}</td>
          <td>{{ order.user_id }}</td>
          <td>${{ order.total_price.toFixed(2) }}</td>
          <td :class="getPaymentStatusClass(order.payment_status)">
            {{ order.payment_status }}
          </td>
          <td :class="getOrderStatusClass(order.status)">
            {{ order.status }}
          </td>
          <td>{{ new Date(order.created_at).toLocaleString() }}</td>
          <td>
            <button @click="viewOrderDetails(order.id)" class="view-btn">
              View Details
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- No Orders Message -->
    <p v-else-if="!loading && orders.length === 0">No orders found.</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";

const orders = ref([]);
const loading = ref(true); // Add loading state
const router = useRouter();

const fetchOrders = async () => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/orders`, {
      method: "GET",
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      },
    });

    if (response.ok) {
      const data = await response.json();
      orders.value = data; // Save the orders to the ref
    } else {
      console.error("Error fetching orders");
    }
  } catch (error) {
    console.error("Error:", error);
  } finally {
    loading.value = false; // Set loading to false after data is fetched
  }
};

// Fetch orders when component is mounted
onMounted(() => {
  fetchOrders();
});

const viewOrderDetails = (orderId) => {
  router.push(`/admin/orders/${orderId}`);
};

const getPaymentStatusClass = (paymentStatus) => {
  switch (paymentStatus) {
    case "Pending":
      return "payment-pending";
    case "Paid":
      return "payment-confirmed";
    case "Failed":
      return "payment-failed";
    default:
      return "";
  }
};

const getOrderStatusClass = (status) => {
  switch (status) {
    case "Pending":
      return "status-pending";
    case "Shipped":
      return "status-shipped";
    case "Delivered":
      return "status-delivered";
    case "Canceled":
      return "status-canceled";
    default:
      return "";
  }
};
</script>

<style scoped>
.orders-container {
  padding: 0 50px;
}

.loading-spinner {
  font-size: 1rem;
  color: var(--primary-color);
  text-align: center;
  margin: 20px 0;
}

.orders-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  margin-bottom: 20px;
}

.orders-table th,
.orders-table td {
  padding: 12px 15px;
  border: 1px solid #ddd;
  text-align: left;
}

.orders-table th {
  background-color: #f4f4f4;
}

.orders-table td {
  background: var(--background-color);
}

.orders-table tr:nth-child(even) {
  background-color: #f9f9f9;
}

.orders-table tr:hover {
  background-color: #f1f1f1;
}

.view-btn {
  background: #1a1a1a;
  color: var(--background-color);
  border: none;
  padding: 8px 16px;
  cursor: pointer;
  font-size: 14px;
  border-radius: 5px;
}

.view-btn:hover {
  background: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.view-btn:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}

/* Payment status color classes */
.payment-pending {
  color: orange;
}

.payment-completed {
  color: green;
}

.payment-failed {
  color: red;
}

/* Order status color classes */
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

@media (max-width: 768px) {
  .orders-table {
    font-size: 12px;
  }

  .orders-table th,
  .orders-table td {
    padding: 8px 10px;
  }
}
</style>
