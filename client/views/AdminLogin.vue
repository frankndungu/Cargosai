<template>
  <div class="login-container">
    <form @submit.prevent="handleSubmit" class="login-form">
      <h1 class="login-title">Welcome back Sir! ⚡</h1>

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

      <!-- Error Message -->
      <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>

      <!-- Sign In Button -->
      <button type="submit" class="login-submit-btn" :disabled="isLoading">
        {{ isLoading ? "Signing in..." : "Sign in" }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";

const router = useRouter();
const store = useStore();

const email = ref("");
const password = ref("");
const errorMessage = ref("");
const isLoading = ref(false);

const handleSubmit = async () => {
  try {
    isLoading.value = true;
    errorMessage.value = "";

    const response = await fetch(
      `${import.meta.env.VITE_API_URL}/admin/login`,
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          email: email.value,
          password: password.value,
        }),
      }
    );

    const data = await response.json();

    if (!response.ok) {
      // Display an error message without redirecting
      throw new Error(data.message || "Login failed");
    }

    // Store the token
    localStorage.setItem("token", data.token);

    // Store user role if included in the response
    await store.dispatch("fetchUser"); // Ensure this method updates the user role in the Vuex store

    // Redirect to admin dashboard
    router.push("/admin/dashboard");
  } catch (error) {
    // Set the error message for display
    errorMessage.value = error.message || "An error occurred during login";
  } finally {
    isLoading.value = false;
  }
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
  font-weight: 900;
  margin-bottom: 20px;
  text-align: center;
  letter-spacing: 2px;
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

/* Error Message */
.error-message {
  color: var(--error-message);
  font-size: 0.9rem;
  text-align: center;
  margin-bottom: 10px;
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
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0);
}

.login-submit-btn:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
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
