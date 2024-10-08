<template>
  <div class="register-container">
    <form @submit.prevent="handleSubmit" class="register-form">
      <h1 class="register-title">Create an account</h1>

      <div class="register-input-group">
        <label for="name" class="register-label">Your Name</label>
        <input type="text" id="name" v-model="name" class="register-input" />
        <span v-if="errors.name" class="error-message">{{ errors.name }}</span>
      </div>

      <div class="register-input-group">
        <label for="email" class="register-label">Your email</label>
        <input type="email" id="email" v-model="email" class="register-input" />
        <span v-if="errors.email" class="error-message">{{
          errors.email
        }}</span>
      </div>

      <div class="register-input-group">
        <label for="password" class="register-label">Password</label>
        <input
          type="password"
          id="password"
          v-model="password"
          class="register-input"
        />
        <span v-if="errors.password" class="error-message">{{
          errors.password
        }}</span>
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
        />
        <span v-if="errors.confirmPassword" class="error-message">{{
          errors.confirmPassword
        }}</span>
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
import { useRouter } from "vue-router";

// Define form fields
const name = ref(""); // Holds the user's name
const email = ref(""); // Holds the user's email
const password = ref(""); // Holds the user's password
const confirmPassword = ref(""); // Holds the confirmation of the user's password

// Error messages object
const errors = ref({
  name: "", // Holds error messages for the name field
  email: "", // Holds error messages for the email field
  password: "", // Holds error messages for the password field
  confirmPassword: "", // Holds error messages for the confirmation password field
});

// router instance
const router = useRouter();

// Form submission handler
const handleSubmit = async () => {
  // Clear previous errors
  errors.value.name = "";
  errors.value.email = "";
  errors.value.password = "";
  errors.value.confirmPassword = "";

  // Validate input fields
  if (!name.value) {
    errors.value.name = "Name is required"; // Validate name
  }
  if (!email.value) {
    errors.value.email = "Email is required"; // Validate email
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
    errors.value.email = "Please enter a valid email"; // Validate email format
  }
  if (!password.value) {
    errors.value.password = "Password is required"; // Validate password
  } else if (password.value.length < 8) {
    errors.value.password = "Password must be at least 8 characters long"; // Minimum length for password
  }
  if (!confirmPassword.value) {
    errors.value.confirmPassword = "Please confirm your password"; // Validate confirmation
  } else if (confirmPassword.value !== password.value) {
    errors.value.confirmPassword = "Passwords do not match"; // Check if passwords match
  }

  // If no errors, proceed with API call
  if (
    !errors.value.name &&
    !errors.value.email &&
    !errors.value.password &&
    !errors.value.confirmPassword
  ) {
    try {
      const response = await fetch(`${import.meta.env.VITE_API_URL}/register`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          name: name.value, // Send user's name
          email: email.value, // Send user's email
          password: password.value, // Send user's password
          password_confirmation: confirmPassword.value, // Send confirmation password
        }),
      });

      const data = await response.json(); // Parse the response

      if (response.ok) {
        // Handle successful registration
        alert("Registration successful!");
        router.push("/dashboard"); // Redirect to the home page after successful registration
      } else {
        // Handle errors returned from the backend
        if (data.error) {
          alert(data.error);
        } else if (data.errors) {
          // Display validation errors from backend
          for (const [key, value] of Object.entries(data.errors)) {
            errors.value[key] = value[0]; // Get the first error message for each field
          }
        }
      }
    } catch (error) {
      console.error("Error:", error);
      alert("An error occurred during registration. Please try again."); // Handle network errors
    }
  }
};
</script>

<style scoped>
.error-message {
  color: var(--error-message); /* Ensure you define an error color */
  margin-top: 5px;
  font-weight: bold;
}

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
