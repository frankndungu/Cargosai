<template>
  <section class="call-to-action">
    <div class="subscribe-section">
      <h2>Be Part of Our Vibrant Community</h2>
      <p>
        Get early access to new arrivals and receive updates on our latest
        products.
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
  </section>
</template>

<script setup>
import { ref } from "vue";

const email = ref("");
function handleSubscribe() {
  // Handle email subscription logic
  alert(`Subscribed with email: ${email.value}`);
}
</script>
