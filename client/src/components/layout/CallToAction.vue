<template>
  <section class="call-to-action">
    <div class="subscribe-section">
      <h2>Become a Member of This Community</h2>
      <p>
        Get access to discounts, new arrivals and receive updates on our latest
        products.
      </p>
      <form @submit.prevent="handleSubscribe">
        <input
          type="email"
          v-model="email"
          placeholder="Enter your email"
          required
        />
        <button type="submit" class="subscribe-button">Join</button>
      </form>
    </div>
  </section>
</template>

<script setup>
import axios from "axios";
import { ref } from "vue";
import { useToast } from "vue-toast-notification";

const email = ref("");
const toast = useToast();

async function handleSubscribe() {
  try {
    const response = await axios.post(
      `${import.meta.env.VITE_API_URL}/subscribe`,
      { email: email.value }
    );
    toast.success(response.data.message);
    email.value = ""; // Clear the input
  } catch (error) {
    if (error.response && error.response.data.errors) {
      const errors = Object.values(error.response.data.errors).flat();
      errors.forEach((err) => toast.error(err));
    } else {
      toast.error("An error occurred. Please try again later.");
    }
  }
}
</script>
