<template>
  <div class="admin-recent-sales">
    <h3>Recent Sales</h3>
    <p>You made 265 sales this month.</p>
    <ul>
      <li v-for="sale in sales" :key="sale.email">
        <div class="admin-sales-item">
          <span class="admin-sales-name">{{ sale.name }}</span>
          <span class="admin-sales-amount">{{ sale.amount }}</span>
        </div>
        <p class="admin-sales-email">{{ sale.email }}</p>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref } from "vue";

const sales = ref([
  {
    name: "Olivia Martin",
    amount: "+$1,999.00",
    email: "olivia.martin@email.com",
  },
  { name: "Jackson Lee", amount: "+$39.00", email: "jackson.lee@email.com" },
  {
    name: "Isabella Nguyen",
    amount: "+$299.00",
    email: "isabella.nguyen@email.com",
  },
]);
</script>
