<template>
  <div class="admin-card">
    <div class="admin-card-content">
      <h3>Total Revenue</h3>
      <i class="fas fa-dollar-sign admin-card-icon"></i>
    </div>
    <p class="admin-card-value">${{ totalRevenue.toLocaleString() }}</p>
    <p class="admin-card-change positive">+20.1% from last month</p>
  </div>
  <div class="admin-card">
    <div class="admin-card-content">
      <h3>Orders</h3>
      <i class="fa-solid fa-bag-shopping admin-card-icon"></i>
    </div>
    <p class="admin-card-value">{{ totalOrders }}</p>
    <p class="admin-card-change positive">+18.0% from last month</p>
  </div>
  <div class="admin-card">
    <div class="admin-card-content">
      <h3>Sales</h3>
      <i class="fas fa-wallet admin-card-icon"></i>
    </div>
    <p class="admin-card-value">{{ salesCount }}</p>
    <p class="admin-card-change positive">+19% from last month</p>
  </div>
  <div class="admin-card">
    <div class="admin-card-content">
      <h3>Active Now</h3>
      <i class="fa-solid fa-users admin-card-icon"></i>
    </div>
    <p class="admin-card-value">573</p>
    <p class="admin-card-change neutral">+201 since last hour</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

// Define reactive data properties
const totalRevenue = ref(0);
const totalOrders = ref(0);
const salesCount = ref(0);

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

// Call fetch functions when the component is mounted
onMounted(() => {
  fetchRevenue();
  fetchTotalOrders();
  fetchSalesCount();
});
</script>
