<template>
  <div class="contact-container">
    <div class="contact-info">
      <div class="info-header">
        <h2 class="contact-title">Let's Talk</h2>
        <p class="contact-description">
          Got questions or proposals? Drop us a message. Our team will get back
          to you within 24 hours.
        </p>
      </div>

      <div class="contact-cards">
        <div class="info-card">
          <div class="card-icon">📞</div>
          <div class="card-content">
            <h3>Call</h3>
            <p>+254 746 884 254</p>
          </div>
        </div>

        <div class="info-card">
          <div class="card-icon">✉️</div>
          <div class="card-content">
            <h3>Email</h3>
            <p>support@maasaimarketonline.com</p>
          </div>
        </div>

        <div class="info-card">
          <div class="card-icon">🕒</div>
          <div class="card-content">
            <h3>Working Hours</h3>
            <p>Mon - Fri, 8AM - 5PM EAT</p>
          </div>
        </div>
      </div>
    </div>

    <form @submit.prevent="handleSubmit" class="contact-form">
      <div class="form-header">
        <h3>Send a Message</h3>
        <p>Fill out the form below and we'll be in touch soon.</p>
      </div>

      <div class="form-grid">
        <div class="form-group">
          <label for="name">Name</label>
          <div class="input-wrapper">
            <input
              type="text"
              id="name"
              v-model="formState.name"
              placeholder="John Doe"
              :class="{ error: errors.name }"
            />
          </div>
          <span v-if="errors.name" class="error-message">{{
            errors.name
          }}</span>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <div class="input-wrapper">
            <input
              type="email"
              id="email"
              v-model="formState.email"
              placeholder="john@example.com"
              :class="{ error: errors.email }"
            />
          </div>
          <span v-if="errors.email" class="error-message">{{
            errors.email
          }}</span>
        </div>
      </div>

      <div class="form-group">
        <label for="subject">Topic</label>
        <div class="input-wrapper">
          <select
            id="subject"
            v-model="formState.subject"
            :class="{ error: errors.subject }"
          >
            <option value="">Select a topic</option>
            <option value="inquiry">General Inquiry</option>
            <option value="support">Technical Support</option>
            <option value="feedback">Product Feedback</option>
            <option value="partnership">Partnership Opportunities</option>
          </select>
        </div>
        <span v-if="errors.subject" class="error-message">{{
          errors.subject
        }}</span>
      </div>

      <div class="form-group">
        <label for="message">Message</label>
        <div class="input-wrapper">
          <textarea
            id="message"
            rows="5"
            v-model="formState.message"
            placeholder="Tell more about what you want..."
            :class="{ error: errors.message }"
          ></textarea>
        </div>
        <span v-if="errors.message" class="error-message">{{
          errors.message
        }}</span>
      </div>

      <button type="submit" class="submit-button" :disabled="isSubmitting">
        <span v-if="!isSubmitting">Send Message</span>
        <span v-else>Sending...</span>
      </button>
    </form>
  </div>
</template>

<script setup>
// Script remains the same as previous version
import { reactive, ref } from "vue";
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

const isSubmitting = ref(false);
const toast = useToast();

const handleSubmit = async () => {
  if (isSubmitting.value) return;

  Object.keys(errors).forEach((key) => (errors[key] = null));

  let hasErrors = false;

  if (!formState.name.trim()) {
    errors.name = "Please enter your name";
    hasErrors = true;
  }

  if (!formState.email.trim()) {
    errors.email = "Please enter your email";
    hasErrors = true;
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formState.email)) {
    errors.email = "Please enter a valid email address";
    hasErrors = true;
  }

  if (!formState.subject) {
    errors.subject = "Please select a topic";
    hasErrors = true;
  }

  if (!formState.message.trim()) {
    errors.message = "Please enter your message";
    hasErrors = true;
  }

  if (hasErrors) return;

  isSubmitting.value = true;

  try {
    const response = await axios.post(
      `${import.meta.env.VITE_API_URL}/contact`,
      formState
    );
    toast.success("Message sent successfully! We'll get back to you soon.");
    Object.keys(formState).forEach((key) => (formState[key] = ""));
  } catch (error) {
    console.error(error);
    toast.error("Something went wrong. Please try again later.");
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<style scoped>
.contact-container {
  display: flex;
  flex-wrap: wrap;
  gap: 40px;
  padding: 40px;
  background-color: #ffffff;
  border-radius: 24px;
  max-width: 1200px;
  margin: 40px auto;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.03);
}

.contact-info {
  flex: 1;
  min-width: 300px;
  display: flex;
  flex-direction: column;
  gap: 40px;
  background: #ffffff;
}

.info-header {
  padding: 20px;
}

.contact-title {
  font-size: 2.5rem;
  margin: 0;
  background: linear-gradient(135deg, #2d3436 0%, #000000 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin-bottom: 16px;
}

.contact-description {
  color: #0f0f0f;
  line-height: 1.6;
  font-size: 1.1rem;
}

.contact-cards {
  display: flex;
  flex-direction: column;
  gap: 16px;
  padding: 0 20px;
}

.info-card {
  display: flex;
  align-items: center;
  gap: 20px;
  padding: 24px;
  border-radius: 16px;
  transition: all 0.3s ease;
}

.card-icon {
  font-size: 20px;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  color: #2d3436;
}

.card-content {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.card-content h3 {
  margin: 0;
  font-size: 0.9rem;
  color: #4a5568;
  font-weight: 500;
}

.card-content p {
  margin: 0;
  color: #2d3436;
  font-size: 0.95rem;
  font-weight: 500;
}

.contact-form {
  flex: 2;
  min-width: 300px;
  width: 100%;
  max-width: 600px;
  display: flex;
  flex-direction: column;
  gap: 24px;
  padding: 40px;
  border-radius: 24px;
}

.form-header {
  margin-bottom: 20px;
  width: 100%;
}

.form-header h3 {
  margin: 0;
  font-size: 1.5rem;
  color: #2d3436;
  margin-bottom: 8px;
}

.form-header p {
  margin: 0;
  color: #0f0f0f;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
  width: 100%;
}

/* Subject (Topic) and Message should span full width */
.form-group:has(#subject),
.form-group:has(#message) {
  grid-column: 1 / -1;
  width: 100%;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
  width: 100%;
}

.form-group label {
  font-size: 0.9rem;
  font-weight: 500;
  color: #0f0f0f;
  width: 100%;
}

.input-wrapper {
  position: relative;
  width: 100%;
  display: block;
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  display: block;
  padding: 16px;
  background: white;
  border: 1px solid #dfe6e9;
  border-radius: 12px;
  font-size: 1rem;
  transition: all 0.3s ease;
  box-sizing: border-box;
  min-width: 100%;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #2d3436;
  box-shadow: 0 0 0 4px rgba(45, 52, 54, 0.1);
}

.form-group input.error,
.form-group select.error,
.form-group textarea.error {
  border-color: #ff7675;
}

.error-message {
  color: #ff7675;
  font-size: 0.8rem;
}

.submit-button {
  background: var(--dark-tint);
  color: var(--background-color);
  padding: 16px 32px;
  border: none;
  border-radius: 12px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-top: 16px;
  width: 100%;
}

.submit-button:hover:not(:disabled) {
  background: var(--dark-color);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.submit-button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

@media (max-width: 768px) {
  .contact-container {
    padding: 24px;
    margin: 20px;
    flex-direction: column;
  }

  .contact-info {
    padding: 0;
  }

  .contact-cards {
    padding: 0;
    margin-top: -20px; /* Reduce gap between title and cards */
  }

  .info-card {
    margin: 0;
    border: 1px solid #f1f1f1;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
  }

  .card-content h3 {
    font-size: 0.85rem;
  }

  .card-content p {
    font-size: 0.9rem;
  }

  .form-grid {
    grid-template-columns: 1fr;
    width: 100%;
  }

  .form-group,
  .input-wrapper,
  .form-group input,
  .form-group select,
  .form-group textarea {
    width: 100%;
    min-width: 100%;
  }

  .contact-title {
    font-size: 2rem;
  }
}

@media (max-width: 480px) {
  .contact-container {
    padding: 16px;
    margin: 16px;
  }

  .contact-form {
    padding: 16px;
  }

  .contact-cards {
    gap: 12px;
  }

  .info-card {
    padding: 0;
    margin-bottom: 24px;
  }

  .info-card {
    padding: 12px 16px;
  }

  .card-icon {
    width: 36px;
    height: 36px;
    font-size: 18px;
  }

  .form-group input,
  .form-group select,
  .form-group textarea {
    padding: 12px;
    width: 100%;
  }

  .submit-button {
    padding: 12px 24px;
    width: 100%;
  }
}
</style>
