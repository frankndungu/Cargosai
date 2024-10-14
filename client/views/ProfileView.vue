<!-- ProfileView.vue -->
<template>
  <div class="profile-view">
    <h1>My Profile</h1>
    <div class="card">
      <h2>Personal Information</h2>
      <div class="form-group">
        <label for="name">Name:</label>
        <input id="name" v-model="user.name" type="text" />
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input id="email" v-model="user.email" type="email" />
      </div>
      <div class="form-group">
        <label for="phone">Phone:</label>
        <input id="phone" v-model="user.phone" type="tel" />
      </div>
      <button class="action-button" @click="saveProfile">Save Changes</button>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

const user = ref({
  name: "John Doe",
  email: "john@example.com",
  phone: "+1 123-456-7890",
});

const saveProfile = () => {
  console.log("Profile saved:", user.value);
  // Implement actual save logic here
};
</script>

<style scoped>
.profile-view h1 {
  font-size: 1.875rem;
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 1.5rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
}

.form-group input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
}
</style>
