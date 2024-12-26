<template>
  <div class="admin-recent-sales">
    <h3>Recent Sales</h3>
    <p>You made {{ completedSalesCount }} sales this month.</p>
    <ul>
      <li v-for="sale in recent_sales" :key="sale.email">
        <div class="admin-sales-item">
          <span class="admin-sales-name">{{ sale.name }}</span>
          <span class="admin-sales-amount">+${{ sale.total_price }}</span>
        </div>
        <p class="admin-sales-email">{{ sale.email }}</p>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const recent_sales = ref([]);
const completedSalesCount = ref(0);

const fetchRecentSales = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_API_URL}/orders/recent`,
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );
    // Log the response structure
    console.log("Raw response:", response);

    // Check if data exists and assign appropriately
    recent_sales.value = Array.isArray(response.data)
      ? response.data
      : response.data.recent_sales || [];
  } catch (error) {
    console.error("Error fetching recent sales:", error);
    recent_sales.value = [];
  }
};

const fetchCompletedSalesCount = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_API_URL}/orders/completed-count`,
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );
    completedSalesCount.value = response.data.completed_orders_count;
  } catch (error) {
    console.error("Error fetching completed sales count:", error);
    completedSalesCount.value = 0;
  }
};

onMounted(() => {
  fetchRecentSales();
  fetchCompletedSalesCount();
});
</script>
