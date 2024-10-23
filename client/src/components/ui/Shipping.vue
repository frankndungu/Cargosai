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
        <select id="country" v-model="shippingAddress.country">
          <option
            v-for="country in countries"
            :key="country.code"
            :value="country.name"
          >
            {{ country.name }}
          </option>
        </select>
      </div>
      <div class="form-group">
        <label for="state">State/Province:</label>
        <input id="state" v-model="shippingAddress.state" type="text" />
      </div>
      <div class="form-group">
        <label for="city">City:</label>
        <input id="city" v-model="shippingAddress.city" type="text" />
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
import { ref, onMounted } from "vue";
import axios from "axios";
import { useToast } from "vue-toast-notification";
import { useStore } from "vuex"; // Import useStore from Vuex

const toast = useToast();
const API_URL = import.meta.env.VITE_API_URL;

const store = useStore(); // Access the Vuex store
const userId = store.state.user.id; // Get the logged-in user's ID

const shippingAddress = ref({
  id: null,
  address1: "",
  country: "",
  state: "",
  city: "",
  postalCode: "",
});

const countries = ref([]);

// Fetch countries from API
onMounted(async () => {
  try {
    const response = await axios.get("https://restcountries.com/v3.1/all");
    countries.value = response.data
      .map((country) => ({
        name: country.name.common,
        code: country.cca2,
      }))
      .sort((a, b) => a.name.localeCompare(b.name));

    // Fetch existing shipping address for the user
    const addressResponse = await axios.get(
      `${API_URL}/shipping-address/user/${userId}`,
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );

    if (addressResponse.data.length > 0) {
      const address = addressResponse.data[0];
      shippingAddress.value = {
        id: address.id,
        address1: address.address1,
        country: address.country,
        state: address.state,
        city: address.city,
        postalCode: address.postal_code,
      };
    }
  } catch (error) {
    console.error("Error fetching countries or address:", error);
    toast.error("Failed to load countries or shipping address.");
  }
});

// Save or update shipping address
const saveShippingAddress = async () => {
  try {
    const headers = {
      Authorization: `Bearer ${localStorage.getItem("token")}`,
    };

    if (shippingAddress.value.id) {
      await axios.put(
        `${API_URL}/shipping-address/update/${shippingAddress.value.id}`,
        {
          address1: shippingAddress.value.address1,
          country: shippingAddress.value.country,
          state: shippingAddress.value.state,
          city: shippingAddress.value.city,
          postal_code: shippingAddress.value.postalCode,
        },
        { headers }
      );
      toast.success("Shipping address updated successfully!");
    } else {
      await axios.post(
        `${API_URL}/shipping-address/create`,
        {
          user_id: userId, // Use the dynamic user ID
          address1: shippingAddress.value.address1,
          country: shippingAddress.value.country,
          state: shippingAddress.value.state,
          city: shippingAddress.value.city,
          postal_code: shippingAddress.value.postalCode,
        },
        { headers }
      );
      toast.success("Shipping address saved successfully!");
    }
  } catch (error) {
    console.error("Error saving shipping address:", error);
    toast.error("Failed to save shipping address.");
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
