<template>
  <div class="admin-dashboard-container">
    <!-- Top Navigation Bar -->
    <AdminHeader />

    <!-- Main Dashboard Content -->
    <div class="admin-dashboard-main">
      <AdminTabs />

      <!-- Cards Section -->
      <div class="admin-cards">
        <AdminCard />
      </div>

      <!-- Main Content Section -->
      <div class="admin-content">
        <AdminSalesChart />
        <AdminRecentSales />
      </div>
    </div>
  </div>
</template>

<script setup>
import AdminHeader from "@/components/admin/AdminHeader.vue";
import AdminTabs from "@/components/admin/AdminTabs.vue";
import AdminCard from "@/components/admin/AdminCard.vue";
import AdminSalesChart from "@/components/admin/AdminSalesChart.vue";
import AdminRecentSales from "@/components/admin/AdminRecentSales.vue";
</script>
