<template>
  <div>
    <button @click="handleLogout">Logout</button>
  </div>
</template>

<script setup>
import { useStore } from "vuex";
import { useRouter } from "vue-router";

const store = useStore();
const router = useRouter();

const handleLogout = async () => {
  try {
    // Optional: Call backend logout endpoint if needed
    await fetch(`${import.meta.env.VITE_API_URL}/logout`, {
      method: "POST",
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      },
    });

    // Clear token from local storage
    localStorage.removeItem("token");

    // Dispatch Vuex logout action
    store.dispatch("logout");

    // Redirect to login page
    router.push("/login");
  } catch (error) {
    console.error("Logout failed", error);
  }
};
</script>
