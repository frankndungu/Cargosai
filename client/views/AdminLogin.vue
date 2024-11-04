<template>
  <div class="login-container">
    <!-- Login Form -->
    <form @submit.prevent="handleSubmit" class="login-form">
      <!-- Title -->
      <h1 class="login-title">Welcome back Admin ⚡</h1>

      <!-- Email Input -->
      <div class="login-input-group">
        <label for="email" class="login-label">Your email</label>
        <input
          type="email"
          id="email"
          v-model="email"
          class="login-input"
          required
        />
      </div>

      <!-- Password Input -->
      <div class="login-input-group">
        <label for="password" class="login-label">Password</label>
        <input
          type="password"
          id="password"
          v-model="password"
          class="login-input"
          required
        />
      </div>

      <!-- Sign In Button -->
      <button type="submit" class="login-submit-btn">Sign in</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from "vue";

const email = ref("");
const password = ref("");

const handleSubmit = () => {
  // Handle login logic
  console.log("Logging in with:", {
    email: email.value,
    password: password.value,
  });
};
</script>

<style scoped>
.login-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background-color: var(--background-color);
  padding: 20px;
}

.login-form {
  background: var(--secondary-color);
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.25);
  width: 100%;
  max-width: 400px;
  display: flex;
  flex-direction: column;
}

/* Title */
.login-title {
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 20px;
  text-align: center;
}

.login-input-group {
  margin-bottom: 15px;
  display: flex;
  flex-direction: column;
}

.login-label {
  margin-bottom: 5px;
  font-weight: bold;
}

.login-input {
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  width: 100%;
  box-sizing: border-box;
}

.login-input:focus {
  outline: none;
  border-color: #333;
}

/* Sign In Button */
.login-submit-btn {
  background: var(--dark-tint);
  color: var(--background-color);
  padding: 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  width: 100%;
}

.login-submit-btn:hover {
  background-color: #333;
}

/* Responsiveness */
@media (max-width: 600px) {
  .login-container {
    padding: 10px;
  }

  .login-form {
    padding: 15px;
  }

  .login-input {
    font-size: 14px;
  }

  .login-submit-btn {
    font-size: 14px;
  }
}
</style>
