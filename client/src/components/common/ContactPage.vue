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
          <address>CPA Center Survey - Nairobi, Kenya</address>
        </div>
        <div class="contact-more">
          <strong>Phone Number</strong><br />
          Customer Support: +254 712 345 678
        </div>
        <div class="contact-more">
          <strong>Email Address</strong><br />
          support@maasaimarket.online
        </div>
      </div>
    </div>
    <form @submit.prevent="handleSubmit" class="contact-form">
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" placeholder="Your name" />
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" placeholder="you@company.com" />
      </div>
      <div class="form-group">
        <label for="subject">Subject</label>
        <select id="subject">
          <option value="">Please select one</option>
          <option value="inquiry">General Inquiry</option>
          <option value="support">Customer Support</option>
          <option value="feedback">Feedback</option>
        </select>
      </div>
      <div class="form-group">
        <label for="message">Send Message</label>
        <textarea
          id="message"
          rows="4"
          placeholder="Tell us more about what you want"
        ></textarea>
      </div>
      <button type="submit" class="submit-button">Send Message</button>
    </form>
  </div>
</template>

<script setup></script>
