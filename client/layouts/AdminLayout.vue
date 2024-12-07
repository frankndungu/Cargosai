<template>
  <div class="admin-dashboard-container">
    <!-- Top Navigation Bar -->
    <header class="admin-dashboard-header">
      <div class="admin-nav-top">
        <div class="admin-nav-links">
          <router-link
            v-for="route in adminRoutes"
            :key="route.path"
            :to="{ name: route.name }"
            :class="{ active: currentRoute === route.name }"
          >
            {{ route.meta.title }}
          </router-link>
        </div>
      </div>
      <div class="admin-dashboard-actions">
        <h1 class="admin-dashboard-title">{{ currentPageTitle }}</h1>
        <div class="admin-actions">
          <VueDatePicker v-model="date" range class="admin-date-picker" />
          <button class="admin-download">Download</button>
        </div>
      </div>
    </header>
  </div>
  <!-- Router view for the child routes -->
  <router-view />
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

const route = useRoute();
const router = useRouter();

// Date picker configuration
const date = ref();
onMounted(() => {
  const startDate = new Date();
  const endDate = new Date(new Date().setDate(startDate.getDate() + 7));
  date.value = [startDate, endDate];
});

// Get all admin routes for navigation
const adminRoutes = computed(() => {
  return router.options.routes
    .find((r) => r.path === "/admin")
    .children.filter((route) => route.meta?.title);
});

// Get current route name for active state
const currentRoute = computed(() => route.name);

// Get current page title
const currentPageTitle = computed(() => {
  return route.meta.title || "Dashboard";
});
</script>
