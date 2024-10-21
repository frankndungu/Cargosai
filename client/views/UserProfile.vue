<template>
  <div class="dashboard">
    <Sidebar />
    <main class="user-content">
      <Profile />
      <Shipping />
      <Billing />
    </main>
  </div>
</template>

<script setup>
import Sidebar from "@/components/ui/Sidebar.vue";
import Profile from "@/components/ui/Profile.vue";
import Shipping from "@/components/ui/Shipping.vue";
import Billing from "@/components/ui/Billing.vue";
</script>

<style scoped>
.dashboard {
  display: flex;
  height: 240vh;
  background: var(--background-color);
}

.user-content {
  flex: 1;
  padding: 25px 50px;
}

.user-content h1 {
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

  .user-content {
    padding: 1rem;
  }
}
</style>
