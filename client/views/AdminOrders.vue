<template>
  <div class="orders-container">
    <h1>Admin Orders</h1>
    <table v-if="orders.length > 0" class="orders-table">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>User ID</th>
          <th>Total Price</th>
          <th>Payment Status</th>
          <th>Status</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="order in orders" :key="order.id">
          <td>{{ order.formatted_id }}</td>
          <td>{{ order.user_id }}</td>
          <td>${{ order.total_price }}</td>
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
    <p v-else>No orders found.</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router"; // We can use Vue Router to navigate to a detail page

const orders = ref([]);
const router = useRouter(); // Initialize the router

// Fetch orders from API
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
  }
};

// Fetch orders when component is mounted
onMounted(() => {
  fetchOrders();
});

// View order details function
const viewOrderDetails = (orderId) => {
  router.push(`/admin/orders/${orderId}`);
};

// Function to get class for payment status
const getPaymentStatusClass = (paymentStatus) => {
  switch (paymentStatus) {
    case "Pending":
      return "payment-pending";
    case "Paid":
      return "payment-paid";
    case "Failed":
      return "payment-failed";
    default:
      return "";
  }
};

// Function to get class for order status
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

.payment-paid {
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
