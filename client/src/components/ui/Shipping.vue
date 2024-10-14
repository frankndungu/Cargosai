<template>
  <div class="shipping-address-view">
    <div class="card">
      <h2>Shipping Address</h2>
      <div class="form-group">
        <label for="address">Address:</label>
        <input id="address" v-model="shippingAddress.address1" type="text" />
      </div>
      <div class="form-group">
        <label for="country">Country:</label>
        <input id="country" v-model="shippingAddress.country" type="text" />
      </div>
      <div class="form-group">
        <label for="city">City:</label>
        <input id="city" v-model="shippingAddress.city" type="text" />
      </div>
      <div class="form-group">
        <label for="state">State/Province:</label>
        <input id="state" v-model="shippingAddress.state" type="text" />
      </div>
      <div class="form-group">
        <label for="postalCode">Postal Code:</label>
        <input
          id="postalCode"
          v-model="shippingAddress.postalCode"
          type="text"
        />
      </div>
      <button class="action-button" @click="saveShippingAddress">
        Save Changes
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

const shippingAddress = ref({
  address: "",
  country: "",
  city: "",
  state: "",
  postalCode: "",
});

const saveShippingAddress = () => {
  console.log("Shipping Address saved:", shippingAddress.value);
  // Implement actual save logic here, such as making an API call
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

.shipping-address-view h2 {
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
