<template>
  <div class="billing-address-view">
    <div class="card">
      <h2>Billing Address</h2>
      <div class="form-group">
        <label for="billingAddress">Address:</label>
        <input
          id="billingAddress"
          v-model="billingAddress.address1"
          type="text"
        />
      </div>
      <div class="form-group">
        <label for="billingCountry">Country:</label>
        <input
          id="billingCountry"
          v-model="billingAddress.country"
          type="text"
        />
      </div>
      <div class="form-group">
        <label for="billingCity">City:</label>
        <input id="billingCity" v-model="billingAddress.city" type="text" />
      </div>
      <div class="form-group">
        <label for="billingState">State/Province:</label>
        <input id="billingState" v-model="billingAddress.state" type="text" />
      </div>
      <div class="form-group">
        <label for="billingPostalCode">Postal Code:</label>
        <input
          id="billingPostalCode"
          v-model="billingAddress.postalCode"
          type="text"
        />
      </div>
      <button class="action-button" @click="saveBillingAddress">
        Save Changes
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

const billingAddress = ref({
  address1: "",
  country: "",
  city: "",
  state: "",
  postalCode: "",
});

const saveBilling = ref(false);

const saveBillingAddress = () => {
  console.log("Billing Address saved:", billingAddress.value);
  // Implement actual save logic here, such as making an API call
  if (saveBilling.value) {
    // Logic to save billing address to user's profile if checked
    console.log("Billing Address saved to profile:", billingAddress.value);
  }
};
</script>

<style scoped>
.card {
  background-color: var(--background-color);
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
  padding: 1.5rem;
  margin-bottom: 1.5rem;
}

.card h2 {
  font-size: 1.25rem;
  font-weight: 600;
  color: var(--dark-color);
  margin-bottom: 1rem;
}

.billing-address-view h2 {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--dark-color);
  margin-bottom: 1rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
}

.form-group input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid;
  background: var(--background-color);
  border-radius: 0.375rem;
}

.action-button {
  padding: 0.5rem 1rem;
  background: var(--dark-tint);
  color: var(--background-color);
  border: none;
  border-radius: 0.375rem;
  cursor: pointer;
  transition: background-color 0.2s;
}

.action-button:hover {
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.action-button:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}
</style>
