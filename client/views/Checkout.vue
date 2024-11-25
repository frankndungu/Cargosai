<!-- Checkout.vue -->
<template>
  <div class="checkout-container">
    <!-- Loading Overlay -->
    <div v-if="isLoading" class="loading-overlay">
      <div class="loading-spinner"></div>
      <p>Processing your order...</p>
    </div>

    <!-- Error Modal -->
    <div v-if="errorMessage" class="error-modal">
      <div class="error-content">
        <div class="error-icon">⚠️</div>
        <h3>Checkout Error</h3>
        <p>{{ errorMessage }}</p>
        <button @click="clearError" class="error-close">Got It</button>
      </div>
    </div>

    <!-- Checkout Form -->
    <div class="checkout-wrapper">
      <div class="checkout-header">
        <h1>Complete Your Purchase</h1>
        <p>Secure and fast checkout process</p>
      </div>

      <form @submit.prevent="submitOrder" class="modern-checkout-form">
        <div class="form-grid">
          <!-- Personal Details -->
          <div class="form-column personal-info">
            <div class="section-header">
              <h2>Personal Details</h2>
              <span>Keep your information safe and secure</span>
            </div>
            <div class="input-group">
              <div class="input-wrapper">
                <label>Name</label>
                <input
                  v-model="formData.name"
                  type="text"
                  placeholder="John Doe"
                  required
                />
              </div>
              <div class="input-wrapper">
                <label>Email Address</label>
                <input
                  v-model="formData.email"
                  type="email"
                  placeholder="john.doe@example.com"
                  required
                />
              </div>
              <div class="input-wrapper">
                <label>Phone Number</label>
                <input
                  v-model="formData.phone"
                  type="tel"
                  placeholder="+1 (123) 456-7890"
                />
              </div>
            </div>
          </div>

          <!-- Payment Details -->
          <div class="form-column payment-info">
            <div class="section-header">
              <h2>Payment Details</h2>
              <span>We protect your payment information</span>
            </div>
            <div class="input-group">
              <div class="input-wrapper">
                <label>Card Holder Name</label>
                <input
                  v-model="formData.cardName"
                  type="text"
                  placeholder="John Doe"
                  required
                />
              </div>
              <div class="input-wrapper card-number">
                <label>Card Number</label>
                <input
                  v-model="formData.cardNumber"
                  type="text"
                  placeholder="1234 5678 9012 3456"
                  required
                  pattern="\d{16}"
                />
              </div>
              <div class="card-details-row">
                <div class="input-wrapper">
                  <label>Expiry (MM/YY)</label>
                  <input
                    v-model="formData.expiry"
                    type="text"
                    placeholder="12/25"
                    required
                    pattern="\d{2}/\d{2}"
                  />
                </div>
                <div class="input-wrapper">
                  <label>CVV</label>
                  <input
                    v-model="formData.cvv"
                    type="text"
                    placeholder="123"
                    required
                    pattern="\d{3}"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="order-summary-section">
          <div class="summary-container">
            <h3 class="summary-title">Order Summary</h3>
            <div class="summary-content">
              <div class="summary-breakdown">
                <div class="summary-row">
                  <span>Subtotal</span>
                  <span>${{ cartTotalPrice }}</span>
                </div>
                <div class="summary-row">
                  <span>Shipping</span>
                  <span
                    >${{
                      selectedShippingOption?.price
                        ? selectedShippingOption.price.toFixed(2)
                        : "0.00"
                    }}</span
                  >
                </div>
                <div class="summary-row total">
                  <strong>Total</strong>
                  <strong>${{ total.toFixed(2) }}</strong>
                </div>
              </div>
              <button type="submit" class="submit-button" :disabled="isLoading">
                Pay Now
              </button>
            </div>
          </div>
        </div>
      </form>

      <!-- Shipping Address Section -->
      <div class="shipping-address-section">
        <div class="section-header">
          <h2>Shipping Address</h2>
          <span>Ensure your delivery information is correct</span>
        </div>
        <div class="input-group">
          <div class="input-wrapper">
            <label>Address</label>
            <input
              v-model="formData.address"
              type="text"
              placeholder="123 Main St"
              required
            />
          </div>
          <div class="input-wrapper">
            <label>City</label>
            <input
              v-model="formData.city"
              type="text"
              placeholder="New York"
              required
            />
          </div>
          <div class="input-wrapper">
            <label>Postal Code</label>
            <input
              v-model="formData.postalCode"
              type="text"
              placeholder="10001"
              required
            />
          </div>
          <div class="input-wrapper">
            <label>Country</label>
            <input
              v-model="formData.country"
              type="text"
              placeholder="United States"
              required
            />
          </div>
        </div>

        <!-- Calculate Shipping Button -->
        <div class="calculate-shipping-section">
          <button @click="calculateShipping" class="calculate-shipping-btn">
            Calculate Shipping
          </button>
        </div>

        <!-- Display Shipping Options -->
        <div v-if="shippingOptions.length" class="shipping-options">
          <h3>Shipping Options</h3>
          <ul>
            <li v-for="(option, index) in shippingOptions" :key="index">
              <label>
                <input
                  type="radio"
                  :value="option"
                  v-model="selectedShippingOption"
                />
                {{ option.service }} - ${{ option.price }}
                {{ option.estimatedDelivery }}
              </label>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useStore } from "vuex";
import axios from "axios";

const store = useStore();

const formData = ref({
  name: "",
  email: "",
  phone: "",
  cardName: "",
  cardNumber: "",
  expiry: "",
  cvv: "",
  address: "",
  city: "",
  postalCode: "",
  country: "",
});

const isLoading = ref(false);
const errorMessage = ref("");
const shippingOptions = ref([]);
const selectedShippingOption = ref(null);

const cartTotalPrice = computed(() => store.getters.cartTotalPrice);

const total = computed(() => {
  return selectedShippingOption.value
    ? parseFloat(cartTotalPrice.value) +
        parseFloat(selectedShippingOption.value.price)
    : parseFloat(cartTotalPrice.value);
});

const calculateShipping = async () => {
  try {
    const response = await axios.post(
      `${import.meta.env.VITE_API_URL}/shipping-rates`,
      {
        country: formData.value.country,
        postal_code: formData.value.postalCode,
        weight: 1,
      },
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );
    shippingOptions.value = response.data.shipping_rates; // Assuming this response contains a list of options
    selectedShippingOption.value = shippingOptions.value[0]; // Default to the first shipping option
  } catch (error) {
    errorMessage.value = "Failed to calculate shipping. Please try again.";
  }
};

const submitOrder = async () => {
  errorMessage.value = "";
  isLoading.value = true;

  try {
    // Place order logic...
    alert("Order Placed Successfully!");
  } catch (error) {
    errorMessage.value = error.message || "An unexpected error occurred";
  } finally {
    isLoading.value = false;
  }
};

const clearError = () => {
  errorMessage.value = "";
};
</script>

<style>
.checkout-container {
  background-color: var(--background-color);
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 40px 50px;
}

.checkout-wrapper {
  background: white;
  border-radius: 16px;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
  /* width: 100%;
  max-width: 1200px; */
  overflow: hidden;
}

.checkout-header {
  background-color: var(--dark-color);
  color: var(--background-color);
  padding: 2rem;
  text-align: center;
}

.checkout-header h1 {
  font-size: 1.75rem;
  margin-bottom: 0.5rem;
}

.modern-checkout-form {
  padding: 2rem;
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
  margin-bottom: 2rem;
}

.shipping-address-section {
  border-top: 1px solid #ccc;
  margin-top: 5px;
  padding: 2rem;
  width: 100%;
}

.calculate-shipping-section {
  margin-top: 1rem;
}

.calculate-shipping-btn {
  background-color: var(--dark-tint);
  color: var(--background-color);
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  cursor: pointer;
  font-size: 1rem;
  font-weight: bold;
}

.calculate-shipping-btn:hover {
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0);
}

.calculate-shipping-btn:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}

.shipping-options {
  margin-top: 1rem;
  padding: 1rem;
  border-radius: 8px;
  border: 1px solid #ccc;
}

.shipping-options ul {
  list-style-type: none;
  padding: 0;
}

.shipping-options li {
  margin-bottom: 1rem;
}

.shipping-options input[type="radio"] {
  margin-right: 0.5rem;
}

.shipping-options label {
  font-size: 1rem;
}

.summary-row.total strong {
  color: var(--primary-color);
}

.section-header {
  margin-bottom: 1.5rem;
  text-align: left;
}

.section-header h2 {
  color: var(--text-color);
  font-size: 1.25rem;
  margin-bottom: 0.25rem;
}

.section-header span {
  color: #6b7280;
  font-size: 0.875rem;
}

.input-group {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.input-wrapper {
  display: flex;
  flex-direction: column;
}

.input-wrapper label {
  margin-bottom: 0.5rem;
  color: var(--text-color);
  font-size: 0.875rem;
  font-weight: 500;
}

.input-wrapper input {
  padding: 0.75rem;
  border: 1px solid rgb(26, 25, 25);
  border-radius: 8px;
  transition: border-color 0.3s ease;
  width: 100%;
}

.input-wrapper input:focus {
  outline: none;
  border-color: var(--dark-color);
  box-shadow: 0 0 0 3px rgba(29, 30, 31, 0.1);
}

.card-details-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.card-input-container {
  position: relative;
}

.card-icons {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
}

.order-summary-section {
  margin-top: 0rem;
  padding-top: 0rem;
  margin-left: 2rem;
}

.summary-container {
  max-width: 400px;
  margin: 0 auto;
}

.summary-title {
  font-size: 1.25rem;
  color: var(--text-color);
  margin-bottom: 1.5rem;
  font-weight: 600;
}

.summary-content {
  border-radius: 12px;
  padding: 1.5rem;
  width: 400px;
}

.summary-breakdown {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  color: var(--dark-color);
  font-size: 0.95rem;
}

.total {
  font-weight: bold;
  color: var(--text-color);
  border-top: 1px solid var(--border-color);
  margin-top: 0.5rem;
  padding-top: 1rem;
}

.submit-button {
  background-color: var(--dark-tint);
  color: var(--background-color);
  border: none;
  padding: 1rem 2rem;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
  width: 100%;
  margin-top: 1.5rem;
  font-weight: 500;
  font-size: 1rem;
}

.submit-button:hover {
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.submit-button:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}

.submit-button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.button-icon {
  font-size: 1.25rem;
}

/* Loading and Error Styles */
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  color: white;
}

.loading-spinner {
  width: 50px;
  height: 50px;
  border: 5px solid #f3f3f3;
  border-top: 5px solid var(--primary-color);
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 1rem;
}

.error-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.error-content {
  background: white;
  padding: 2rem;
  border-radius: 16px;
  text-align: center;
  max-width: 400px;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
}

.error-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.error-close {
  margin-top: 1rem;
  padding: 0.75rem 1.5rem;
  background-color: var(--primary-color);
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.error-close:hover {
  background-color: var(--dark-color);
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

/* Responsive Styles */
@media (max-width: 768px) {
  .checkout-container {
    padding: 1rem;
  }

  .form-grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }

  .checkout-wrapper {
    border-radius: 12px;
  }

  .modern-checkout-form {
    padding: 1.5rem;
  }

  .name-row,
  .card-details-row {
    grid-template-columns: 1fr;
    gap: 1rem;
  }

  .order-summary-section {
    margin: 0;
  }

  .summary-container {
    max-width: 100%;
  }

  .summary-content {
    width: auto;
  }

  .shipping-address-section {
    width: 100%;
  }
}

/* Additional Hover and Focus States */
.input-wrapper input:hover {
  border-color: rgb(24, 26, 27);
}
.error-close {
  background: var(--dark-tint);
}
.error-close:focus,
.submit-button:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(23, 23, 24, 0.3);
}

/* Disabled State Styles */
input:disabled {
  background-color: #f1f5f9;
  cursor: not-allowed;
}

/* Error State Styles */
.input-wrapper.error input {
  border-color: #ef4444;
  background-color: #fef2f2;
}

.input-wrapper.error label {
  color: #ef4444;
}

/* Success State Styles */
.input-wrapper.success input {
  border-color: #22c55e;
  background-color: #f0fdf4;
}

/* Loading Button State */
.submit-button:disabled {
  background-color: #94a3b8;
  cursor: not-allowed;
}

/* Card Icons Container */
.card-icons {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

/* Section Transitions */
.section-header,
.input-wrapper,
.summary-content {
  transition: all 0.3s ease;
}

/* Print Styles */
@media print {
  .checkout-container {
    padding: 0;
    background: white;
  }

  .submit-button,
  .error-modal,
  .loading-overlay {
    display: none;
  }

  .checkout-wrapper {
    box-shadow: none;
  }
}
</style>
