<template>
  <div class="admin-dashboard-container">
    <!-- Main Dashboard Content -->
    <div class="admin-dashboard-main">
      <AdminTabs @updateActiveTab="handleTabChange" />

      <!-- Cards Section, visible only on the "Overview" tab -->
      <div class="admin-cards" v-if="activeTab === 'Overview'">
        <AdminCard />
      </div>

      <!-- Main Content Section, display content based on the active tab -->
      <div class="admin-content">
        <!-- Display content for "Overview" tab -->
        <template v-if="activeTab === 'Overview'">
          <AdminSalesChart />
          <AdminRecentSales />
        </template>

        <!-- Display other content based on the active tab -->
        <template v-else-if="activeTab === 'Sales'">
          <p>Sales Data Content</p>
        </template>
        <template v-else-if="activeTab === 'Reports'">
          <p>Reports Content</p>
        </template>
        <template v-else-if="activeTab === 'Notifications'">
          <p>Notifications Content</p>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import AdminTabs from "@/components/admin/AdminTabs.vue";
import AdminCard from "@/components/admin/AdminCard.vue";
import AdminSalesChart from "@/components/admin/AdminSalesChart.vue";
import AdminRecentSales from "@/components/admin/AdminRecentSales.vue";

const activeTab = ref("Overview"); // Default active tab

function handleTabChange(tab) {
  activeTab.value = tab;
}
</script>
