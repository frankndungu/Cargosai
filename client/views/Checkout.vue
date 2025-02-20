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
                complete your transaction.
              </p>
              <p>
                <strong>Note:</strong> Prices are displayed in USD. The final
                payment amount will be converted to Kenyan Shillings (KES) at
                the current exchange rate during checkout.
              </p>
            </div>
          </div>

          <!-- Order Summary -->
          <div class="form-column order-summary-section">
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
                <div class="payment-buttons">
                  <button
                    type="button"
                    class="submit-button paypal-button"
                    :disabled="isLoading"
                    @click="checkoutWithPaypal"
                  >
                    <span class="button-icon">
                      <svg
                        class="paypal-icon"
                        viewBox="0 0 24 24"
                        width="18"
                        height="18"
                      >
                        <path
                          fill="currentColor"
                          d="M20.067 8.478c.492.876.661 1.924.662 3.084 0 3.634-3.076 6.938-7.73 6.938h-2.437a.766.766 0 0 0-.756.655L8.82 23.425c-.048.264-.282.575-.557.575H5.947a.434.434 0 0 1-.429-.506l2.474-15.642a.764.764 0 0 1 .756-.655h4.648c1.096 0 2.26.145 3.131.531.857.383 1.54.977 2.04 1.75zM9.421 0c4.654 0 7.73 3.304 7.73 6.938 0 1.16-.169 2.208-.662 3.084-.5.773-1.183 1.367-2.04 1.75-.87.386-2.035.531-3.131.531H6.67a.766.766 0 0 0-.756.655L3.441 28.3a.434.434 0 0 1-.43.506H.695a.434.434 0 0 1-.429-.506L2.74 12.658a.764.764 0 0 1 .756-.655h2.437c4.654 0 7.73-3.304 7.73-6.938 0-1.16-.169-2.208-.662-3.084-.5-.773-1.183-1.367-2.04-1.75C10.091.145 8.926 0 7.83 0h-4.648a.766.766 0 0 0-.756.655L0 15.642a.434.434 0 0 0 .429.506h2.316c.275 0 .509-.311.557-.575l.986-4.27a.766.766 0 0 1 .756-.655h2.437c4.654 0 7.73-3.304 7.73-6.938 0-1.16-.169-2.208-.662-3.084-.5-.773-1.183-1.367-2.04-1.75C12.639.145 11.474 0 10.378 0H5.73z"
                        />
                      </svg>
                    </span>
                    Checkout With PayPal
                  </button>

                  <div class="button-divider">
                    <span>or</span>
                  </div>
                  <button
                    type="submit"
                    class="submit-button card-button"
                    :disabled="isLoading"
                  >
                    <span class="button-icon"></span>
                    Checkout With Paystack
                  </button>
                </div>
              </div>
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
            <select v-model="formData.shippingCountry" required>
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
              v-model="formData.shippingState"
              type="text"
              placeholder="New York"
              required
            />
          </div>
          <div class="input-wrapper">
            <label>City</label>
            <input
              v-model="formData.shippingCity"
              type="text"
              placeholder="New York"
              required
            />
          </div>
          <div class="input-wrapper">
            <label>Address</label>
            <input
              v-model="formData.shippingAddress"
              type="text"
              placeholder="123 Main St"
              required
            />
          </div>
          <div class="input-wrapper">
            <label>Postal Code</label>
            <input
              v-model="formData.shippingPostalCode"
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

      <!-- Billing Address Section -->
      <div class="billing-address-section">
        <div class="section-header">
          <h2>Billing Address</h2>
          <span>Ensure your billing information is correct</span>
        </div>

        <div class="input-group">
          <div class="input-wrapper">
            <label>Country</label>
            <select v-model="formData.billingCountry" required>
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
              v-model="formData.billingState"
              type="text"
              placeholder="New York"
              required
            />
          </div>
          <div class="input-wrapper">
            <label>City</label>
            <input
              v-model="formData.billingCity"
              type="text"
              placeholder="New York"
              required
            />
          </div>
          <div class="input-wrapper">
            <label>Address</label>
            <input
              v-model="formData.billingAddress"
              type="text"
              placeholder="123 Main St"
              required
            />
          </div>
          <div class="input-wrapper">
            <label>Postal Code</label>
            <input
              v-model="formData.billingPostalCode"
              type="text"
              placeholder="10001"
              required
            />
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

// Form data for personal details, shipping, and billing addresses
const formData = ref({
  name: "",
  email: "",
  phone: "",
  shippingAddress: "",
  shippingCity: "",
  shippingState: "",
  shippingPostalCode: "",
  shippingCountry: "",
  billingAddress: "",
  billingCity: "",
  billingState: "",
  billingPostalCode: "",
  billingCountry: "",
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
    console.error("Error fetching countries:", error);
    errorMessage.value = "Failed to load countries. Please try again.";
  }
};

// Fetch user details
const fetchUserDetails = async () => {
  // Check if user is authenticated
  const token = localStorage.getItem("token");

  // If no token, skip fetching user details
  if (!token) {
    return;
  }

  try {
    const response = await axios.get(`${import.meta.env.VITE_API_URL}/user`, {
      headers: {
        Authorization: `Bearer ${token}`,
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
  // Check if user is authenticated
  const token = localStorage.getItem("token");

  // If no token, skip fetching shipping address
  if (!token) {
    return;
  }

  try {
    const response = await axios.get(`${import.meta.env.VITE_API_URL}/user`, {
      headers: {
        Authorization: `Bearer ${token}`,
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
          Authorization: `Bearer ${token}`,
        },
      }
    );

    const shippingAddresses = addressResponse.data;
    if (shippingAddresses.length > 0) {
      const shippingAddress = shippingAddresses[0];
      formData.value.shippingAddress = shippingAddress.address1;
      formData.value.shippingCity = shippingAddress.city;
      formData.value.shippingState = shippingAddress.state;
      formData.value.shippingPostalCode = shippingAddress.postal_code;
      formData.value.shippingCountry = shippingAddress.country;
    } else {
      errorMessage.value = "No shipping address found.";
    }
  } catch (error) {
    errorMessage.value = "Failed to fetch shipping address. Please try again.";
  }
};

// Fetch billing address
const fetchBillingAddress = async () => {
  // Check if user is authenticated
  const token = localStorage.getItem("token");

  // If no token, skip fetching billing address
  if (!token) {
    return;
  }

  try {
    const response = await axios.get(`${import.meta.env.VITE_API_URL}/user`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });

    const userId = response.data?.id;
    if (!userId) {
      errorMessage.value = "User ID not found. Cannot fetch billing address.";
      return;
    }

    const addressResponse = await axios.get(
      `${import.meta.env.VITE_API_URL}/billing-address/user/${userId}`,
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
    );

    const billingAddresses = addressResponse.data;
    if (billingAddresses.length > 0) {
      const billingAddress = billingAddresses[0];
      formData.value.billingAddress = billingAddress.address1;
      formData.value.billingCity = billingAddress.city;
      formData.value.billingState = billingAddress.state;
      formData.value.billingPostalCode = billingAddress.postal_code;
      formData.value.billingCountry = billingAddress.country;
    } else {
      errorMessage.value = "No billing address found.";
    }
  } catch (error) {
    errorMessage.value = "Failed to fetch billing address. Please try again.";
  }
};

// Calculate shipping
const calculateShipping = async () => {
  try {
    const response = await axios.post(
      `${import.meta.env.VITE_API_URL}/shipping-rates`,
      {
        country: formData.value.shippingCountry,
        postal_code: formData.value.shippingPostalCode,
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

// Submit order using paypal
const checkoutWithPaypal = async () => {
  // Validate shipping option selection
  if (!selectedShippingOption.value) {
    errorMessage.value = "Please select a shipping option first.";
    return;
  }

  isLoading.value = true;
  errorMessage.value = "";

  try {
    // Calculate shipping fee
    const shippingFee = selectedShippingOption.value
      ? parseFloat(selectedShippingOption.value.price)
      : 0;

    // Prepare order data
    const orderData = {
      items: cartItems.value.map((item) => ({
        product_id: item.id,
        price: item.price,
        quantity: item.quantity,
      })),
      total_price: total.value,
      shipping_fee: shippingFee,
      shipping_address: {
        address1: formData.value.shippingAddress,
        country: formData.value.shippingCountry,
        state: formData.value.shippingState,
        city: formData.value.shippingCity,
        postal_code: formData.value.shippingPostalCode,
      },
      billing_address: {
        address1: formData.value.billingAddress,
        country: formData.value.billingCountry,
        state: formData.value.billingState,
        city: formData.value.billingCity,
        postal_code: formData.value.billingPostalCode,
      },
    };

    // Add guest details if user is not authenticated
    const isAuthenticated = localStorage.getItem("token");
    if (!isAuthenticated) {
      orderData.guest_name = formData.value.name;
      orderData.guest_email = formData.value.email;
      orderData.guest_phone = formData.value.phone;
    }

    // Create PayPal order
    const response = await axios.post(
      `${import.meta.env.VITE_API_URL}/paypal/create-order`,
      orderData,
      {
        headers: {
          Authorization: isAuthenticated
            ? `Bearer ${localStorage.getItem("token")}`
            : "",
        },
      }
    );

    if (response.data.approval_url) {
      // Store order details in localStorage for reference after return
      localStorage.setItem("pending_order_id", response.data.order_id);
      store.dispatch("clearCart");

      // Redirect to PayPal
      window.location.href = response.data.approval_url;
    } else {
      throw new Error("PayPal approval URL not received");
    }
  } catch (error) {
    console.error("PayPal checkout error:", error);
    errorMessage.value =
      error.response?.data?.message || "Failed to initialize PayPal checkout";
  } finally {
    isLoading.value = false;
  }
};

// Submit order using paystack
const orderId = ref(null);

const submitOrder = async () => {
  // Validate shipping option selection
  if (!selectedShippingOption.value) {
    errorMessage.value = "Please select a shipping option first.";
    return;
  }

  // Calculate shipping fee
  const shippingFee = selectedShippingOption.value
    ? parseFloat(selectedShippingOption.value.price)
    : 0;

  isLoading.value = true;
  errorMessage.value = "";

  try {
    // Determine if the user is authenticated
    const isAuthenticated = localStorage.getItem("token");

    // Prepare order data
    const orderData = {
      items: cartItems.value.map((item) => ({
        product_id: item.id,
        price: item.price,
        quantity: item.quantity,
      })),
      total_price: total.value,
      shipping_fee: shippingFee,
      status: "Pending",
      payment_status: "Completed",
      // Add shipping address details
      shipping_address: {
        address1: formData.value.shippingAddress,
        country: formData.value.shippingCountry,
        state: formData.value.shippingState,
        city: formData.value.shippingCity,
        postal_code: formData.value.shippingPostalCode,
      },
      // Add billing address details
      billing_address: {
        address1: formData.value.billingAddress,
        country: formData.value.billingCountry,
        state: formData.value.billingState,
        city: formData.value.billingCity,
        postal_code: formData.value.billingPostalCode,
      },
    };

    // Add guest details if user is not authenticated
    if (!isAuthenticated) {
      orderData.guest_name = formData.value.name;
      orderData.guest_email = formData.value.email;
      orderData.guest_phone = formData.value.phone;
    }

    // Step 1: Submit the order to the backend
    const orderResponse = await axios.post(
      `${import.meta.env.VITE_API_URL}/orders`,
      orderData,
      {
        headers: {
          Authorization: isAuthenticated
            ? `Bearer ${localStorage.getItem("token")}`
            : "",
        },
      }
    );

    if (orderResponse.status === 201) {
      // Store orderId in a reactive state
      orderId.value = orderResponse.data.order_id;

      // Step 2: Initialize payment with Paystack
      const paymentResponse = await axios.post(
        `${import.meta.env.VITE_API_URL}/paystack/initialize`,
        {
          email: formData.value.email,
          amount: total.value,
          order_id: orderId.value,
        }
      );

      if (paymentResponse.data.status) {
        // Clear the cart after successful checkout
        store.dispatch("clearCart");

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
    console.error("Order submission error:", error);
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

  // Only attempt to fetch user details if there's a token
  const token = localStorage.getItem("token");
  if (token) {
    await fetchUserDetails();
    await fetchShippingAddress();
    await fetchBillingAddress(); // Fetch billing address for authenticated users
  }
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
  grid-template-columns: repeat(3, 1fr);
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

.shipping-address-section,
.billing-address-section {
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

.summary-container {
  max-width: 400px;
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
  width: 100%;
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

.payment-buttons {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-top: 1.5rem;
  width: 100%;
}

.button-divider {
  position: relative;
  text-align: center;
  margin: 0.5rem 0;
}

.button-divider::before,
.button-divider::after {
  content: "";
  position: absolute;
  top: 50%;
  width: calc(50% - 1.5rem);
  height: 1px;
  background-color: var(--border-color);
}

.button-divider::before {
  left: 0;
}

.button-divider::after {
  right: 0;
}

.button-divider span {
  background-color: white;
  padding: 0 1rem;
  color: #6b7280;
  font-size: 0.875rem;
}

.submit-button {
  margin-top: 0;
}

.card-button {
  background-color: var(--dark-tint);
}

.card-button:hover {
  background-color: var(--dark-color);
}

.paypal-button {
  background-color: #0070ba;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
}

.paypal-button:hover {
  background-color: #003087;
}

.paypal-icon {
  display: inline-block;
  vertical-align: middle;
}

.submit-button {
  font-size: 1rem;
  padding: 0.875rem 1.5rem;
  font-weight: 600;
}

.button-icon {
  display: flex;
  align-items: center;
  justify-content: center;
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

  .payment-buttons {
    /* Keep column layout for larger screens too for consistency */
    flex-direction: column;
    max-width: 100%;
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
