<template>
  <div class="register-container">
    <form @submit.prevent="handleSubmit" class="register-form">
      <!-- Title inside the form -->
      <h1 class="register-title">Create an account</h1>

      <div class="register-input-group">
        <label for="email" class="register-label">Your email</label>
        <input
          type="email"
          id="email"
          v-model="email"
          class="register-input"
          required
        />
      </div>

      <div class="register-input-group">
        <label for="password" class="register-label">Password</label>
        <input
          type="password"
          id="password"
          v-model="password"
          class="register-input"
          required
        />
      </div>

      <div class="register-input-group">
        <label for="confirm-password" class="register-label"
          >Confirm Password</label
        >
        <input
          type="password"
          id="confirm-password"
          v-model="confirmPassword"
          class="register-input"
          required
        />
      </div>

      <button type="submit" class="register-submit-btn">
        Create an account
      </button>
      <p class="register-footer">
        Already have an account?
        <router-link to="/login" class="register-link">Login here</router-link>
      </p>
    </form>
  </div>
</template>

<script setup>
import { ref } from "vue";

const email = ref("");
const password = ref("");
const confirmPassword = ref("");

const handleSubmit = () => {
  // Handle form submission logic
  console.log("Form submitted:", {
    email: email.value,
    password: password.value,
  });
};
</script>

<style scoped>
.register-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background: var(--background-color);
  padding: 20px;
}

.register-form {
  background: var(--secondary-color);
  padding: 20px;
  border: 1px solid;
  border-radius: 10px;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
  display: flex;
  flex-direction: column;
}

/* Title is now inside the form */
.register-title {
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 20px;
  text-align: center;
}

.register-input-group {
  margin-bottom: 15px;
  display: flex;
  flex-direction: column;
}

.register-label {
  margin-bottom: 5px;
  font-weight: bold;
}

.register-input {
  padding: 10px;
  border: 1px solid var(--dark-color);
  border-radius: 5px;
  width: 100%;
  box-sizing: border-box; /* Ensures consistent width across all inputs */
}

.register-submit-btn {
  background: var(--dark-tint);
  color: var(--background-color);
  padding: 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  width: 100%;
}

.register-submit-btn:hover {
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0);
}

.register-submit-btn:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}

.register-footer {
  margin-top: 20px;
  text-align: center;
}

.register-link {
  color: var(--dark-tint);
  text-decoration: none;
  font-weight: bold;
}

.register-link:hover {
  text-decoration: underline;
}

/* Responsiveness */
@media (max-width: 600px) {
  .register-container {
    padding: 10px;
  }

  .register-form {
    padding: 15px;
  }

  .register-input {
    font-size: 14px;
  }

  .register-submit-btn {
    font-size: 14px;
  }
}
</style>
