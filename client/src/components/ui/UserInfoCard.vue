<template>
  <div class="card">
    <h2>Account Information</h2>
    <p><strong>Email:</strong> {{ user.email }}</p>
    <p><strong>Joined:</strong> {{ user.joinedDate }}</p>
  </div>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
  user: {
    type: Object,
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
</style>
