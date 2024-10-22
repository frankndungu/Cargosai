<template>
  <div class="profile-view">
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
import { ref, onMounted } from "vue";
import axios from "axios";
import { useToast } from "vue-toast-notification";

const toast = useToast();

const user = ref({
  id: null, // Add id property to the user object
  name: "",
  email: "",
  phone: "",
});

const API_URL = import.meta.env.VITE_API_URL;

onMounted(async () => {
  try {
    // Fetch user info
    const userResponse = await axios.get(`${API_URL}/user`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      },
    });
    user.value.id = userResponse.data.id; // Get the user ID
    user.value.name = userResponse.data.name;
    user.value.email = userResponse.data.email;
    user.value.phone = userResponse.data.phonenumber || ""; // Set phone if available
  } catch (error) {
    console.error("Error fetching user data:", error);
    toast.error("Failed to load user data.");
  }
});

const saveProfile = async () => {
  try {
    // Ensure user.id is present before sending the request
    if (!user.value.id) {
      throw new Error("User ID is missing.");
    }

    // Update user profile
    await axios.put(`${API_URL}/users/${user.value.id}`, user.value, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      },
    });
    toast.success("Profile updated successfully!");
  } catch (error) {
    console.error("Error updating profile:", error);
    toast.error("Failed to update profile.");
  }
};
</script>

<style scoped>
.card {
  background-color: var(--background-color);
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
  padding: 1.5rem;
  margin-bottom: 1.5rem;
}

.card h2 {
  font-size: 1.25rem;
  font-weight: 600;
  color: var(--dark-color);
  margin-bottom: 1rem;
}

.profile-view h2 {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--dark-color);
  margin-bottom: 1rem;
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
  border: 1px solid;
  background: var(--background-color);
  border-radius: 0.375rem;
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
