<template>
  <div class="admin-card">
    <div class="admin-card-content">
      <h3>Total Revenue</h3>
      <i class="fas fa-dollar-sign admin-card-icon"></i>
    </div>
    <p class="admin-card-value">${{ totalRevenue.toLocaleString() }}</p>
    <p
      class="admin-card-change"
      :class="revenueChange > 0 ? 'positive' : 'negative'"
    >
      {{
        revenueChange !== null
          ? `${revenueChange.toFixed(1)}% from last month`
          : "Data unavailable"
      }}
    </p>
  </div>
  <div class="admin-card">
    <div class="admin-card-content">
      <h3>Orders</h3>
      <i class="fa-solid fa-bag-shopping admin-card-icon"></i>
    </div>
    <p class="admin-card-value">{{ totalOrders }}</p>
    <p
      class="admin-card-change"
      :class="orderChange > 0 ? 'positive' : 'negative'"
    >
      {{
        orderChange !== null
          ? `${orderChange.toFixed(1)}% from last month`
          : "Data unavailable"
      }}
    </p>
  </div>
  <div class="admin-card">
    <div class="admin-card-content">
      <h3>Sales</h3>
      <i class="fas fa-wallet admin-card-icon"></i>
    </div>
    <p class="admin-card-value">{{ salesCount }}</p>
    <p
      class="admin-card-change"
      :class="salesChange > 0 ? 'positive' : 'negative'"
    >
      {{
        salesChange !== null
          ? `${salesChange.toFixed(1)}% from last month`
          : "Data unavailable"
      }}
    </p>
  </div>
  <div class="admin-card">
    <div class="admin-card-content">
      <h3>New Users</h3>
      <i class="fa-solid fa-users admin-card-icon"></i>
    </div>
    <p class="admin-card-value">{{ newUsers }}</p>
    <p
      class="admin-card-change"
      :class="newUsersChange > 0 ? 'positive' : 'negative'"
    >
      {{
        newUsersChange !== null
          ? `${newUsersChange.toFixed(1)}% from last month`
          : "Data unavailable"
      }}
    </p>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

// Define reactive data properties
const orderChange = ref(null);
const revenueChange = ref(null);
const salesChange = ref(null);
const totalRevenue = ref(0);
const totalOrders = ref(0);
const salesCount = ref(0);
const newUsers = ref(0);
const newUsersChange = ref(0);

// Fetch new users count and percentage change
const fetchNewUsersChange = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_API_URL}/admin/users/change`,
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );
    newUsers.value = response.data.current_month_new_users;
    newUsersChange.value = response.data.percentage_change;
  } catch (error) {
    console.error("Error fetching new users change data:", error);
  }
};

// Fetch percentage change in completed orders (sales)
const fetchCompletedOrdersChange = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_API_URL}/orders/completed-change`,
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );

    salesCount.value = response.data.current_month_completed_orders;
    salesChange.value = response.data.percentage_change;
  } catch (error) {
    console.error("Error fetching completed orders change:", error);
  }
};

// Fetch percentage change in orders
const fetchOrderChange = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_API_URL}/orders/change`,
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );
    totalOrders.value = response.data.current_month_orders;
    orderChange.value = response.data.percentage_change;
  } catch (error) {
    console.error("Error fetching order change data:", error);
  }
};

// Fetch revenue data and percentage change
const fetchRevenueChange = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_API_URL}/payments/revenue-change`
    );
    totalRevenue.value = response.data.current_month_revenue;
    revenueChange.value = response.data.percentage_change;
  } catch (error) {
    console.error("Error fetching revenue change data:", error);
  }
};

// Fetch revenue data from the backend
const fetchRevenue = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_API_URL}/payments/revenue`
    );
    totalRevenue.value = response.data.total_revenue;
  } catch (error) {
    console.error("Error fetching revenue data:", error);
  }
};

// Fetch total orders from the backend
const fetchTotalOrders = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_API_URL}/orders/total`,
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );

    totalOrders.value = response.data.total_orders;
  } catch (error) {
    console.error("Error fetching total orders:", error);
  }
};

const fetchSalesCount = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_API_URL}/orders/completed-count`,
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );
    salesCount.value = response.data.completed_orders_count;
  } catch (error) {
    console.error("Error fetching completed orders count:", error);
  }
};

// Fetch new users count
const fetchNewUsers = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_API_URL}/admin/users/new`,
      {
        params: { days: 30 }, // Last 30 days
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );
    newUsers.value = response.data.new_users_count;
  } catch (error) {
    console.error("Error fetching new users data:", error);
  }
};

// Call fetch functions when the component is mounted
onMounted(() => {
  fetchNewUsersChange();
  fetchCompletedOrdersChange();
  fetchOrderChange();
  fetchRevenueChange();
  fetchRevenue();
  fetchTotalOrders();
  fetchSalesCount();
  fetchNewUsers();
});
</script>
