<template>
  <div class="admin-avatar" @click="toggleDropdown">
    <img :src="avatarUrl" alt="Admin Avatar" class="avatar-image" />
    <span class="admin-name">{{ adminName }}</span>
    <div v-if="showDropdown" class="dropdown-menu">
      <a href="#" @click.prevent="logout">Logout</a>
      <a href="#">Profile</a>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
  avatarUrl: {
    type: String,
    default: "https://via.placeholder.com/50", // Default avatar URL
  },
  adminName: {
    type: String,
    default: "Admin",
  },
});

const showDropdown = ref(false);

function toggleDropdown() {
  showDropdown.value = !showDropdown.value;
}

function logout() {
  // Handle logout logic here
  console.log("Logging out...");
}
</script>

<style scoped>
.admin-avatar {
  display: flex;
  align-items: center;
  cursor: pointer;
}

.avatar-image {
  border-radius: 50%;
  width: 50px;
  height: 50px;
  margin-right: 10px;
}

.admin-name {
  font-weight: bold;
}

.dropdown-menu {
  position: absolute;
  background: white;
  border: 1px solid #ddd;
  border-radius: 4px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  margin-top: 10px;
  padding: 10px;
  z-index: 10;
}

.dropdown-menu a {
  display: block;
  padding: 5px 10px;
  color: #333;
  text-decoration: none;
}

.dropdown-menu a:hover {
  background: #f0f0f0;
}
</style>
