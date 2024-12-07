<template>
  <div class="login-container">
    <form @submit.prevent="handleSubmit" class="login-form">
      <h1 class="login-title">Sign in to your account</h1>

      <div class="login-input-group">
        <label for="email" class="login-label">Your email</label>
        <input type="email" id="email" v-model="email" class="login-input" />
        <span v-if="errors.email" class="error-message">{{
          errors.email
        }}</span>
      </div>

      <div class="login-input-group">
        <label for="password" class="login-label">Password</label>
        <input
          type="password"
          id="password"
          v-model="password"
          class="login-input"
        />
        <span v-if="errors.password" class="error-message">{{
          errors.password
        }}</span>
      </div>

      <div class="login-auxiliary">
        <div class="login-checkbox">
          <input type="checkbox" id="remember-me" v-model="rememberMe" />
          <label for="remember-me" class="login-label">Remember me</label>
        </div>
        <router-link to="/recovery" class="login-forgot-link"
          >Forgot password?</router-link
        >
      </div>

      <button type="submit" class="login-submit-btn" :disabled="isSubmitting">
        Sign in
      </button>

      <p class="login-footer">
        Don’t have an account yet?
        <router-link to="/register" class="login-signup-link"
          >Sign up</router-link
        >
      </p>
    </form>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";

const store = useStore();
const router = useRouter();
const email = ref("");
const password = ref("");
const errors = ref({ email: "", password: "" });
const rememberMe = ref(false);
const isSubmitting = ref(false);

const handleSubmit = async () => {
  errors.value.email = "";
  errors.value.password = "";

  if (!email.value) {
    errors.value.email = "Email is required";
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
    errors.value.email = "Please enter a valid email";
  }

  if (!password.value) {
    errors.value.password = "Password is required";
  } else if (password.value.length < 6) {
    errors.value.password = "Password must be at least 6 characters long";
  }

  if (!errors.value.email && !errors.value.password) {
    try {
      isSubmitting.value = true;
      const response = await fetch(`${import.meta.env.VITE_API_URL}/login`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          email: email.value,
          password: password.value,
          rememberMe: rememberMe.value,
        }),
      });

      const data = await response.json();

      if (response.ok) {
        localStorage.setItem("token", data.token);
        await store.dispatch("fetchUser"); // Fetch user data from API
        email.value = "";
        password.value = "";
        router.push("/dashboard");
      } else {
        if (data.error) {
          alert(data.error);
        } else if (data.errors) {
          for (const [key, value] of Object.entries(data.errors)) {
            errors.value[key] = value[0];
          }
        }
      }
    } catch (error) {
      console.error("Error:", error);
      alert("An error occurred during login. Please try again.");
    } finally {
      isSubmitting.value = false;
    }
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
  background: var(--background-color);
  padding: 20px;
}

.login-form {
  border: 1px solid;
  background-color: var(--secondary-color);
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
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
  border: 1px solid var(--dark-color);
  border-radius: 5px;
  width: 100%;
  box-sizing: border-box;
}

/* Error message */
.error-message {
  color: var(--error-message);
  font-size: 0.9rem;
  margin-top: 5px;
}

/* Remember me and Forgot Password section */
.login-auxiliary {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.login-checkbox {
  display: flex;
  align-items: center;
}

.login-checkbox input {
  margin-right: 5px;
}

.login-forgot-link {
  color: var(--dark-color);
  text-decoration: none;
  font-weight: bold;
}

.login-forgot-link:hover {
  text-decoration: underline;
}

/* Sign In Button */
.login-submit-btn {
  background-color: var(--dark-tint);
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

/* Sign Up Link */
.login-footer {
  margin-top: 20px;
  text-align: center;
}

.login-signup-link {
  color: var(--dark-color);
  text-decoration: none;
  font-weight: bold;
}

.login-signup-link:hover {
  text-decoration: underline;
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
