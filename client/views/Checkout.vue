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

          <!-- Paystack Payment Details -->
          <div class="form-column payment-info">
            <div class="section-header">
              <h2>Payment Method</h2>
              <span>Secure Payment with Paystack</span>
            </div>
            <div class="payment-description">
              <p>
                You will be redirected to Paystack's secure payment gateway to
                complete your transaction. Paystack supports multiple payment
                methods including:
              </p>
              <ul>
                <li>Credit/Debit Cards</li>
                <li>Bank Transfer</li>
                <li>Mobile Money</li>
                <li>PayPal</li>
              </ul>
              <p>
                Your payment information will be processed securely through
                Paystack's encrypted platform.
              </p>
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
                  <span>${{ cartTotal.toFixed(2) }}</span>
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
                Pay With Card
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
            <label>Country</label>
            <select v-model="formData.country" required>
              <option value="" disabled>Select your country</option>
              <option
                v-for="(country, index) in countries"
                :key="index"
                :value="country"
              >
                {{ country }}
              </option>
            </select>
          </div>
          <div class="input-wrapper">
            <label>State/Province</label>
            <input
              v-model="formData.state"
              type="text"
              placeholder="New York"
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
            <label>Address</label>
            <input
              v-model="formData.address"
              type="text"
              placeholder="123 Main St"
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
        </div>

        <!-- Calculate Shipping Button -->
        <div class="calculate-shipping-section">
          <button @click="calculateShipping" class="calculate-shipping-btn">
            Calculate Shipping
          </button>
        </div>

        <!-- Display Shipping Options -->
        <div v-if="shippingOptions.length" class="shipping-options">
          <h3 class="shipping-title">Shipping Options</h3>
          <div class="shipping-options-grid">
            <div
              v-for="(option, index) in shippingOptions"
              :key="index"
              class="shipping-option-card"
              :class="{ selected: selectedShippingOption === option }"
              @click="selectedShippingOption = option"
            >
              <div class="shipping-option-content">
                <div class="shipping-header">
                  <div class="shipping-icon">
                    <!-- Show different icon based on shipping service type -->
                    <span
                      v-if="option.service.toLowerCase().includes('express')"
                      >🚀</span
                    >
                    <span
                      v-else-if="
                        option.service.toLowerCase().includes('standard')
                      "
                      >🚚</span
                    >
                    <span v-else>📦</span>
                  </div>
                  <div class="shipping-price">
                    ${{ option.price.toFixed(2) }}
                  </div>
                </div>

                <div class="shipping-details">
                  <div class="shipping-service">{{ option.service }}</div>
                  <div class="shipping-delivery">
                    {{ option.estimatedDelivery }}
                  </div>
                </div>

                <div class="shipping-selection">
                  <input
                    type="radio"
                    :id="'shipping-' + index"
                    :value="option"
                    v-model="selectedShippingOption"
                    class="shipping-radio"
                  />
                  <label :for="'shipping-' + index" class="shipping-label">
                    <span class="radio-custom"></span>
                    Select this option
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useStore } from "vuex";
import axios from "axios";

const store = useStore();

const formData = ref({
  name: "",
  email: "",
  phone: "",
  address: "",
  city: "",
  postalCode: "",
  country: "",
  state: "",
});

const countries = ref([]);
const isLoading = ref(false);
const errorMessage = ref("");
const shippingOptions = ref([]);
const selectedShippingOption = ref(null);

// Load cart total from Vuex
const cartTotal = computed(() => store.getters.cartTotal);

// Access cart items from Vuex store
const cartItems = computed(() => store.getters.cartItems);

// Calculate total including shipping
const total = computed(() => {
  return selectedShippingOption.value
    ? parseFloat(cartTotal.value) +
        parseFloat(selectedShippingOption.value.price)
    : parseFloat(cartTotal.value);
});

// Fetch countries
const fetchCountries = async () => {
  try {
    const response = await axios.get("https://restcountries.com/v3.1/all");
    countries.value = response.data
      .map((country) => country.name.common)
      .sort();
  } catch (error) {
    errorMessage.value = "Failed to load countries. Please try again.";
  }
};

// Fetch personal details
const fetchUserDetails = async () => {
  try {
    const response = await axios.get(`${import.meta.env.VITE_API_URL}/user`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      },
    });

    const user = response.data;
    formData.value.name = user.name || "";
    formData.value.email = user.email || "";
    formData.value.phone = user.phonenumber || "";
  } catch (error) {
    errorMessage.value =
      "Failed to load user details. Please check your connection.";
  }
};

// Fetch shipping address
const fetchShippingAddress = async () => {
  try {
    const response = await axios.get(`${import.meta.env.VITE_API_URL}/user`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      },
    });

    const userId = response.data?.id;
    if (!userId) {
      errorMessage.value = "User ID not found. Cannot fetch shipping address.";
      return;
    }

    const addressResponse = await axios.get(
      `${import.meta.env.VITE_API_URL}/shipping-address/user/${userId}`,
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );

    const shippingAddresses = addressResponse.data;
    if (shippingAddresses.length > 0) {
      const shippingAddress = shippingAddresses[0];
      formData.value.address = shippingAddress.address1;
      formData.value.city = shippingAddress.city;
      formData.value.state = shippingAddress.state;
      formData.value.postalCode = shippingAddress.postal_code;
      formData.value.country = shippingAddress.country;
    } else {
      errorMessage.value = "No shipping address found.";
    }
  } catch (error) {
    errorMessage.value = "Failed to fetch shipping address. Please try again.";
  }
};

// Calculate shipping
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
    shippingOptions.value = response.data.shipping_rates;
    selectedShippingOption.value = shippingOptions.value[0];
  } catch (error) {
    errorMessage.value = "Failed to calculate shipping. Please try again.";
  }
};

// Submit order
const orderId = ref(null);

const submitOrder = async () => {
  isLoading.value = true;
  errorMessage.value = "";

  try {
    // Step 1: Submit the order to the backend
    const orderResponse = await axios.post(
      `${import.meta.env.VITE_API_URL}/orders`,
      {
        items: cartItems.value.map((item) => ({
          product_id: item.id,
          price: item.price,
          quantity: item.quantity,
        })),
        total_price: total.value,
        status: "Pending",
        payment_status: "Pending",
      },
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );

    if (orderResponse.status === 201) {
      // Store orderId in a reactive state
      orderId.value = orderResponse.data.order_id;

      // Step 2: Initialize payment with Paystack, include the order ID
      const paymentResponse = await axios.post(
        `${import.meta.env.VITE_API_URL}/paystack/initialize`,
        {
          email: formData.value.email,
          amount: total.value,
          order_id: orderId.value,
        }
      );

      if (paymentResponse.data.status) {
        // Step 3: Clear the cart after successful checkout
        store.dispatch("clearCart"); // Clear the cart from Vuex and localStorage

        // Redirect to Paystack payment page
        window.location.href = paymentResponse.data.data.authorization_url;
      } else {
        errorMessage.value = "Failed to initialize payment.";
      }
    } else {
      errorMessage.value = "Failed to create order.";
    }
  } catch (error) {
    errorMessage.value =
      error.response?.data?.message || "An unexpected error occurred.";
  } finally {
    isLoading.value = false;
  }
};

// Clear error messages
const clearError = () => {
  errorMessage.value = "";
};

// Load user details on component mount
onMounted(async () => {
  fetchCountries();
  await fetchUserDetails();
  await fetchShippingAddress();
});
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

.payment-description {
  border-radius: 8px;
}

.payment-description p {
  margin-bottom: 0.75rem;
  color: var(--dark-color);
}

.payment-description ul {
  list-style-type: disc;
  padding-left: 1.5rem;
  margin-bottom: 0.75rem;
  color: var(--dark-color);
}

.shipping-address-section {
  border-top: 1px solid #ccc;
  margin-top: 5px;
  padding: 2rem;
  width: 100%;
}

.calculate-shipping-section {
  margin-top: 2rem;
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
  margin-top: 2rem;
  padding: 1.5rem;
  border-radius: 12px;
}

.shipping-title {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
  color: var(--text-color);
}

.shipping-options-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1rem;
}

.shipping-option-card {
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  padding: 1.25rem;
  cursor: pointer;
  transition: all 0.2s ease;
  position: relative;
  overflow: hidden;
}

.shipping-option-card:hover {
  border-color: var(--dark-tint);
  transform: translateY(-2px);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.shipping-option-card.selected {
  border-color: var(--dark-color);
}

.shipping-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.shipping-icon {
  font-size: 1.5rem;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(0, 0, 0, 0.05);
  border-radius: 50%;
}

.shipping-price {
  font-size: 1.25rem;
  font-weight: 600;
  color: var(--dark-color);
}

.shipping-details {
  margin-bottom: 1rem;
}

.shipping-service {
  font-weight: 500;
  margin-bottom: 0.25rem;
  color: var(--text-color);
}

.shipping-delivery {
  font-size: 0.875rem;
  color: #6b7280;
}

.shipping-selection {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.shipping-radio {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

.shipping-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  font-size: 0.875rem;
  color: var(--dark-color);
}

.radio-custom {
  width: 1.25rem;
  height: 1.25rem;
  border: 2px solid #d1d5db;
  border-radius: 50%;
  display: inline-block;
  position: relative;
  transition: all 0.2s ease;
}

.shipping-radio:checked + .shipping-label .radio-custom {
  border-color: var(--dark-color);
  background: var(--dark-color);
}

.shipping-radio:checked + .shipping-label .radio-custom::after {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 0.5rem;
  height: 0.5rem;
  background: white;
  border-radius: 50%;
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

.input-wrapper select {
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
    padding: 40px 10px;
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

  .shipping-options {
    padding: 1rem;
  }

  .shipping-options-grid {
    grid-template-columns: 1fr;
  }

  .shipping-option-card {
    padding: 1rem;
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
