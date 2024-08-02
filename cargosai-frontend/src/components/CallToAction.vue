<template>
  <div class="subscribe-section">
    <h2>Stay Updated with Maasai Market Online!</h2>
    <p>
      Subscribe to our newsletter to receive the latest updates and exclusive
      offers.
    </p>
    <form @submit.prevent="handleSubscribe">
      <input
        type="email"
        v-model="email"
        placeholder="Enter your email"
        required
      />
      <button type="submit" class="subscribe-button">Subscribe</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from "vue";

const email = ref("");
function handleSubscribe() {
  // Handle email subscription logic
  alert(`Subscribed with email: ${email.value}`);
}
</script>
