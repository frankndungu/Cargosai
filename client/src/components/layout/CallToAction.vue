<template>
  <section class="cta-section">
    <div class="cta-subscribe-container">
      <h2 class="cta-title">Join Our Growing Community</h2>
      <p class="cta-description">
        Get exclusive access to member-only discounts, early product releases,
        and stay updated with our latest offerings.
      </p>
      <form @submit.prevent="handleSubscribe" class="cta-form">
        <div class="input-group">
          <input
            type="email"
            v-model="email"
            placeholder="Enter your email"
            required
            class="cta-input"
            :disabled="isSubmitting"
            @focus="handleInputFocus"
            @blur="handleInputBlur"
          />
          <button type="submit" class="cta-button" :disabled="isSubmitting">
            <span v-if="isSubmitting">
              <span class="loader"></span>
            </span>
            <span v-else> Join Now </span>
          </button>
        </div>
        <p class="privacy-notice">
          We respect your privacy. Unsubscribe at any time.
        </p>
      </form>
    </div>
  </section>
</template>

<script setup>
import axios from "axios";
import { ref } from "vue";
import { useToast } from "vue-toast-notification";

const email = ref("");
const isSubmitting = ref(false);
const isInputFocused = ref(false);
const toast = useToast();

const handleInputFocus = () => {
  isInputFocused.value = true;
};

const handleInputBlur = () => {
  isInputFocused.value = false;
};

async function handleSubscribe() {
  if (isSubmitting.value) return;

  isSubmitting.value = true;
  try {
    const response = await axios.post(
      `${import.meta.env.VITE_API_URL}/subscribe`,
      { email: email.value }
    );
    toast.success("Welcome to our community! 🎉");
    email.value = "";
  } catch (error) {
    if (error.response?.data?.errors) {
      const errors = Object.values(error.response.data.errors).flat();
      errors.forEach((err) => toast.error(err));
    } else {
      toast.error("Oops! Something went wrong. Please try again.");
    }
  } finally {
    isSubmitting.value = false;
  }
}
</script>

<style scoped>
.cta-section {
  text-align: center;
  padding: 3.5rem 2rem;
  background: linear-gradient(
    145deg,
    var(--secondary-color),
    var(--secondary-color-light, #f5f5f5)
  );
  margin: 2.5rem auto;
  border-radius: 16px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  max-width: 800px;
}

.cta-title {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 1rem;
  background: linear-gradient(to right, var(--dark-color), var(--dark-tint));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.cta-description {
  font-size: 1.1rem;
  line-height: 1.6;
  margin-bottom: 2rem;
  color: var(--dark-color);
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
}

.cta-form {
  max-width: 500px;
  margin: 0 auto;
}

.input-group {
  display: flex;
  gap: 0.75rem;
  margin-bottom: 1rem;
}

.cta-input {
  flex: 1;
  background: var(--background-color);
  padding: 0.875rem 1.25rem;
  font-size: 1rem;
  border: 2px solid transparent;
  border-radius: 8px;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.cta-input:focus {
  outline: none;
  border-color: var(--dark-tint);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.cta-button {
  background: var(--dark-tint);
  color: var(--background-color);
  padding: 0.875rem 2rem;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  min-width: 140px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.cta-button:not(:disabled):hover {
  background: var(--dark-color);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.cta-button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.privacy-notice {
  font-size: 0.875rem;
  color: var(--dark-color);
  opacity: 0.8;
}

.loader {
  width: 20px;
  height: 20px;
  border: 3px solid var(--background-color);
  border-bottom-color: transparent;
  border-radius: 50%;
  display: inline-block;
  animation: rotation 1s linear infinite;
}

@keyframes rotation {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

@media (max-width: 600px) {
  .cta-section {
    padding: 2rem 1rem;
    margin: 1.5rem 1rem;
  }

  .cta-title {
    font-size: 1.75rem;
  }

  .cta-description {
    font-size: 1rem;
  }

  .input-group {
    flex-direction: column;
    gap: 1rem;
  }

  .cta-input,
  .cta-button {
    width: 100%;
    min-width: unset;
  }

  .cta-button {
    padding: 0.875rem 1rem;
  }
}
</style>
