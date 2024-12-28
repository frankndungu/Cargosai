<template>
  <section class="cta-section">
    <div class="cta-subscribe-container">
      <h2 class="cta-title">Become a Member of This Community</h2>
      <p class="cta-description">
        Get access to discounts, new arrivals and receive updates on our latest
        products.
      </p>
      <form @submit.prevent="handleSubscribe" class="cta-form">
        <input
          type="email"
          v-model="email"
          placeholder="Enter your email"
          required
          class="cta-input"
        />
        <button type="submit" class="cta-button">Join</button>
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

<style scoped>
/* CTA section */
.cta-section {
  text-align: center;
  padding: 40px;
  background-color: var(--secondary-color);
  margin-top: 40px;
  border-radius: 8px;
}

.cta-title {
  font-size: 1.5rem;
  margin-bottom: 20px;
}

.cta-description {
  margin-bottom: 20px;
  color: var(--dark-color);
}

.cta-form {
  display: flex;
  justify-content: center;
  gap: 10px;
}

.cta-input {
  background: var(--background-color);
  padding: 10px;
  font-size: 1rem;
  border: 1px solid var(--dark-color);
  border-radius: 5px;
  width: 300px;
}

.cta-button {
  background-color: var(--dark-tint);
  color: var(--background-color);
  padding: 10px 50px;
  border: none;
  border-radius: 5px;
  font-size: 1rem;
  cursor: pointer;
}

.cta-button:hover {
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0);
}

.cta-button:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}

@media (max-width: 600px) {
  .cta-form {
    flex-direction: column;
  }

  .cta-title {
    font-size: var(--h2-font-size);
    margin-bottom: 20px;
  }

  .cta-description {
    font-size: var(--normal-font-size);
    margin-bottom: 20px;
  }

  .cta-input,
  .cta-button {
    width: 100%;
    max-width: 100%;
    margin: 5px 0;
  }

  .cta-button {
    padding: 10px;
  }
}
</style>
