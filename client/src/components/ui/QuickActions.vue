<template>
  <div class="quick-actions">
    <button
      v-for="action in actions"
      :key="action.name"
      @click="action.handler"
      class="action-button"
    >
      {{ action.name }}
    </button>
  </div>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
  actions: {
    type: Array,
    required: true,
  },
});
</script>

<style scoped>
.quick-actions {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
}

.action-button {
  padding: 0.5rem 1rem;
  background: var(--dark-tint);
  color: var(--background-color);
  border: none;
  border-radius: 0.375rem;
  cursor: pointer;
  transition: background-color 0.2s;
}

.action-button:hover {
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.action-button:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}
</style>
