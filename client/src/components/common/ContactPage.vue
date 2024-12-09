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
