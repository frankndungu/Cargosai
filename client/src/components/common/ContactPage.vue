<template>
  <div class="contact-container">
    <div class="contact-info">
      <h2 class="contact-title">Get in Touch with Us</h2>
      <p class="contact-description">
        We're here to help with any questions or inquiries you have.
      </p>
      <div class="contact-details">
        <div class="contact-more">
          <strong>Phone Number</strong><br />
          <p class="contact-link">Customer Support: +254 746 884 254</p>
        </div>
        <div class="contact-more">
          <strong>Email Address</strong><br />
          <p class="contact-link">support@maasaimarketonline.com</p>
        </div>
      </div>
    </div>
    <form @submit.prevent="handleSubmit" class="contact-form">
      <div class="form-group">
        <label for="name">Name</label>
        <input
          type="text"
          id="name"
          v-model="formState.name"
          placeholder="Your name"
        />
        <span v-if="errors.name" class="error-message">{{ errors.name }}</span>
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input
          type="email"
          id="email"
          v-model="formState.email"
          placeholder="you@company.com"
        />
        <span v-if="errors.email" class="error-message">{{
          errors.email
        }}</span>
      </div>
      <div class="form-group">
        <label for="subject">Subject</label>
        <select id="subject" v-model="formState.subject">
          <option value="">Please select one</option>
          <option value="inquiry">General Inquiry</option>
          <option value="support">Customer Support</option>
          <option value="feedback">Feedback</option>
        </select>
        <span v-if="errors.subject" class="error-message">{{
          errors.subject
        }}</span>
      </div>
      <div class="form-group">
        <label for="message">Send Message</label>
        <textarea
          id="message"
          rows="4"
          v-model="formState.message"
          placeholder="Tell us more about what you want"
        ></textarea>
        <span v-if="errors.message" class="error-message">{{
          errors.message
        }}</span>
      </div>
      <button type="submit" class="submit-button">Send Message</button>
    </form>
  </div>
</template>

<script setup>
import { reactive } from "vue";
import axios from "axios";
import { useToast } from "vue-toast-notification";

const formState = reactive({
  name: "",
  email: "",
  subject: "",
  message: "",
});

const errors = reactive({
  name: null,
  email: null,
  subject: null,
  message: null,
});

const toast = useToast();

const handleSubmit = async () => {
  // Reset errors
  errors.name = null;
  errors.email = null;
  errors.subject = null;
  errors.message = null;

  // Validate form
  if (!formState.name) errors.name = "Name is required";
  if (!formState.email) {
    errors.email = "Email is required";
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formState.email)) {
    errors.email = "Please enter a valid email";
  }
  if (!formState.subject) errors.subject = "Please select a subject";
  if (!formState.message) errors.message = "Message is required";

  // Submit form if no errors
  if (!errors.name && !errors.email && !errors.subject && !errors.message) {
    try {
      const response = await axios.post(
        `${import.meta.env.VITE_API_URL}/contact`,
        formState
      );
      // Show success toast
      toast.success(response.data.message);
      // Clear the form
      Object.keys(formState).forEach((key) => (formState[key] = ""));
    } catch (error) {
      console.error(error);
      // Show error toast
      toast.error("An error occurred. Please try again later.");
    }
  }
};
</script>

<style scoped>
.contact-container {
  display: flex;
  flex-wrap: wrap;
  gap: 50px;
  padding: 30px;
  background-color: var(--background-color);
  border-radius: 8px;
  max-width: 950px; /* Constrain width on larger screens */
  margin: 40px auto; /* Center container horizontally */
  box-shadow: 7px 4px 3px 0px rgba(0, 0, 0, 0.1);
}

.contact-info {
  background-color: var(--dark-tint);
  color: var(--background-color);
  padding: 30px;
  border-radius: 8px;
  flex: 1;
  max-width: 400px;
}

.contact-title {
  margin-top: 0;
}

.contact-description {
  margin-top: 20px;
}

.contact-details {
  margin-top: 20px;
}

.contact-more {
  margin-top: 20px;
}

.contact-link {
  margin-top: 5px;
  font-size: 12px;
}

.contact-form {
  flex: 2;
  display: flex;
  flex-direction: column;
  gap: 15px;
  max-width: 400px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group label {
  margin-bottom: 5px;
  font-weight: bold;
}

.error-message {
  color: var(--error-message);
  margin-top: 5px;
  font-weight: var(--font-bold);
}

.error-message {
  animation: shake 0.2s ease-in-out;
}

@keyframes shake {
  0% {
    transform: translateX(0);
  }
  25% {
    transform: translateX(-5px);
  }
  50% {
    transform: translateX(5px);
  }
  75% {
    transform: translateX(-5px);
  }
  100% {
    transform: translateX(0);
  }
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  max-width: 350px;
  padding: 8px;
  background: var(--background-color);
  border: 1px solid var(--dark-color);
  border-radius: 5px;
  font-size: 14px;
  box-sizing: border-box;
}

.submit-button {
  width: 100%;
  max-width: 350px;
  background-color: var(--dark-tint);
  color: var(--background-color);
  padding: 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
}

.submit-button:hover {
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.submit-button:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}

/* Responsive Styles */
@media (max-width: 768px) {
  .contact-container {
    flex-direction: column;
    gap: 30px;
  }

  .contact-info {
    max-width: 100%;
  }

  .contact-form {
    max-width: 100%;
  }

  .form-group input,
  .form-group select,
  .form-group textarea,
  .submit-button {
    max-width: 100%;
  }
}

@media (max-width: 480px) {
  .contact-info {
    padding: 20px;
  }

  .contact-details div {
    font-size: 14px;
  }

  .form-group input,
  .form-group select,
  .form-group textarea,
  .submit-button {
    font-size: 12px;
    padding: 6px;
  }
}
</style>
