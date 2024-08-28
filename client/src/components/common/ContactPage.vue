<template>
  <div class="contact-container">
    <div class="contact-info">
      <h2 class="contact-title">Get in Touch with Us</h2>
      <p class="contact-description">
        We're here to help with any questions or inquiries you have.
      </p>
      <div class="contact-details">
        <div class="contact-more">
          <strong>Address</strong><br />
          <p class="contact-link">CPA Center - Survey Nairobi, Kenya</p>
        </div>
        <div class="contact-more">
          <strong>Phone Number</strong><br />
          <p class="contact-link">Customer Support: +254 746 884 254</p>
        </div>
        <div class="contact-more">
          <strong>Email Address</strong><br />
          <p class="contact-link">support@maasaimarket.online</p>
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

const handleSubmit = () => {
  // Reset errors
  errors.name = null;
  errors.email = null;
  errors.subject = null;
  errors.message = null;

  // Validate name
  if (!formState.name) {
    errors.name = "Name is required";
  }
  // Validate email
  if (!formState.email) {
    errors.email = "Email is required";
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formState.email)) {
    errors.email = "Please enter a valid email";
  }

  // Validate subject
  if (!formState.subject) {
    errors.subject = "Please select a subject";
  }

  // Validate message
  if (!formState.message) {
    errors.message = "Message is required";
  }

  // If there are no errors, proceed with form submission
  if (!errors.name && !errors.email && !errors.subject && !errors.message) {
    alert("Form submitted successfully!");
    // Perform form submission logic here, such as sending data to a server
  }
};
</script>
