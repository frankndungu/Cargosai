<template>
  <div class="dashboard">
    <Sidebar />

    <main class="main-content">
      <h1>Welcome back, {{ user.name }}!</h1>
      <UserInfoCard :user="user" />
      <OrderSummary :stats="orderStats" />
      <QuickActions :actions="quickActions" />
    </main>
  </div>
</template>

<script setup>
import { ref } from "vue";
import Sidebar from "@/components/ui/Sidebar.vue";
import UserInfoCard from "@/components/ui/UserInfoCard.vue";
import OrderSummary from "@/components/ui/OrderSummary.vue";
import QuickActions from "@/components/ui/QuickActions.vue";

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

const quickActions = [
  { name: "View Orders", handler: () => console.log("View Orders clicked") },
  { name: "Edit Profile", handler: () => console.log("Edit Profile clicked") },
];
</script>

<style scoped>
.dashboard {
  display: flex;
  height: 100vh;
  background: var(--background-color);
}

.main-content {
  flex: 1;
  padding: 25px 50px;
}

.main-content h1 {
  font-size: 1.875rem;
  font-weight: 600;
  color: var(--dark-color);
  margin-bottom: 1.5rem;
}

@media (max-width: 768px) {
  .dashboard {
    flex-direction: column;
  }

  .sidebar {
    width: 100%;
    height: auto;
  }

  .main-content {
    padding: 1rem;
  }
}
</style>
