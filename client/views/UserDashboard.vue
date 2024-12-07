<template>
  <div class="dashboard">
    <Sidebar />

    <main class="main-content">
      <h1>Welcome back, {{ user.name }}!</h1>
      <UserInfoCard :user="user" />
      <OrderSummary :stats="orderStats" />
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import Sidebar from "@/components/ui/Sidebar.vue";
import UserInfoCard from "@/components/ui/UserInfoCard.vue";
import OrderSummary from "@/components/ui/OrderSummary.vue";

const user = ref({
  name: "",
  email: "",
});

const orderStats = ref([
  { name: "Total Orders", value: 0 },
  { name: "Pending Orders", value: 0 },
  { name: "Completed Orders", value: 0 },
]);

const API_URL = import.meta.env.VITE_API_URL;

onMounted(async () => {
  try {
    // Fetch user info
    const userResponse = await axios.get(`${API_URL}/user`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      },
    });
    user.value.name = userResponse.data.name;
    user.value.email = userResponse.data.email;

    // Convert the 'created_at' date to a more readable format
    user.value.joinedDate = new Date(
      userResponse.data.created_at
    ).toLocaleDateString();

    // Fetch order data
    const ordersResponse = await axios.get(`${API_URL}/orders`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      },
    });

    const orders = ordersResponse.data;

    // Calculate the statistics
    const totalOrders = orders.length;
    const pendingOrders = orders.filter(
      (order) => order.status === "Pending"
    ).length;
    const completedOrders = orders.filter(
      (order) => order.status === "Delivered"
    ).length;

    orderStats.value = [
      { name: "Total Orders", value: totalOrders },
      { name: "Pending Orders", value: pendingOrders },
      { name: "Completed Orders", value: completedOrders },
    ];
  } catch (error) {
    console.error("Error fetching user or order data:", error);
  }
});
</script>
