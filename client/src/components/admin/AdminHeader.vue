<template>
  <header class="admin-dashboard-header">
    <div class="admin-nav-top">
      <div class="admin-nav-links">
        <a href="#">Overview</a>
        <a href="#">Products</a>
        <a href="#">Customers</a>
        <a href="#">Orders</a>
      </div>
    </div>
    <div class="admin-dashboard-actions">
      <h1 class="admin-dashboard-title">Dashboard</h1>
      <div class="admin-actions">
        <VueDatePicker v-model="date" range class="admin-date-picker" />
        <button class="admin-download">Download</button>
      </div>
    </div>
  </header>
</template>

<script setup>
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { ref, onMounted } from "vue";

const date = ref();

onMounted(() => {
  const startDate = new Date();
  const endDate = new Date(new Date().setDate(startDate.getDate() + 7));
  date.value = [startDate, endDate];
});
</script>
