<template>
  <div class="orders-container">
    <h1>Admin Orders</h1>
    <table v-if="orders.length > 0" class="orders-table">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>User ID</th>
          <th>Total Price</th>
          <th>Status</th>
          <th>Created At</th>
          <th>Actions</th>
          <!-- Added Actions header -->
        </tr>
      </thead>
      <tbody>
        <tr v-for="order in orders" :key="order.id">
          <td>{{ order.formatted_id }}</td>
          <td>{{ order.user_id }}</td>
          <td>{{ order.total_price }}</td>
          <td>{{ order.status }}</td>
          <td>{{ new Date(order.created_at).toLocaleString() }}</td>
          <td>
            <!-- Action button to view order details -->
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
        Authorization: `Bearer ${localStorage.getItem("token")}`, // Assuming you're using token-based auth
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
  // You can route to a detailed order page or show a modal
  // Example: Navigate to a detail page using Vue Router
  router.push(`/admin/orders/${orderId}`); // Assuming you have this route set up
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
  background: var(--dark-tint);
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
