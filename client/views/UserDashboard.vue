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
import { ref } from "vue";
import Sidebar from "@/components/ui/Sidebar.vue";
import UserInfoCard from "@/components/ui/UserInfoCard.vue";
import OrderSummary from "@/components/ui/OrderSummary.vue";

const user = ref({
  name: "John Doe",
  email: "john@example.com",
  joinedDate: "January 1, 2023",
});

const orderStats = [
  { name: "Total Orders", value: 25 },
  { name: "Pending Orders", value: 3 },
  { name: "Completed Orders", value: 22 },
];
</script>
