<template>
  <div class="card">
    <h2>Order Summary</h2>
    <div class="stat-grid">
      <div v-for="stat in stats" :key="stat.name" class="stat-item">
        <p class="stat-name">{{ stat.name }}</p>
        <p class="stat-value">{{ stat.value }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
  stats: {
    type: Array,
    required: true,
  },
});
</script>

<style scoped>
.card {
  background-color: var(--modal-color);
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  padding: 1.5rem;
  margin-bottom: 1.5rem;
}

.card h2 {
  font-size: 1.25rem;
  font-weight: 600;
  color: var(--dark-color);
  margin-bottom: 1rem;
}

.stat-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.stat-item {
  background-color: var(--stat-color);
  padding: 1rem;
  border-radius: 0.375rem;
}

.stat-name {
  font-size: 0.875rem;
  color: var(--stat-name);
}

.stat-value {
  font-size: 1.5rem;
  font-weight: 600;
  color: var(--dark-color);
}
</style>
