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
        <select id="billingCountry" v-model="billingAddress.country">
          <option value="" disabled>Select your country</option>
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
        <label for="billingState">State/Province:</label>
        <input id="billingState" v-model="billingAddress.state" type="text" />
      </div>
      <div class="form-group">
        <label for="billingCity">City:</label>
        <input id="billingCity" v-model="billingAddress.city" type="text" />
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
import { ref, onMounted } from "vue";
import axios from "axios";
import { useToast } from "vue-toast-notification";
import { useStore } from "vuex";

const toast = useToast();
const API_URL = import.meta.env.VITE_API_URL;

const store = useStore();
const userId = store.state.user.id; // Get the logged-in user's ID

const billingAddress = ref({
  id: null,
  address1: "",
  country: "",
  state: "",
  city: "",
  postalCode: "",
});

const countries = ref([]); // Array to hold countries

onMounted(async () => {
  try {
    // Fetch existing billing address for the user
    const response = await axios.get(
      `${API_URL}/billing-address/user/${userId}`,
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );

    if (response.data.length > 0) {
      const address = response.data[0];
      billingAddress.value = {
        id: address.id,
        address1: address.address1,
        country: address.country,
        state: address.state,
        city: address.city,
        postalCode: address.postal_code,
      };
    } else {
      toast.info("No billing address found, please enter one.");
    }

    // Fetch countries from the REST Countries API
    const countriesResponse = await axios.get(
      "https://restcountries.com/v3.1/all"
    );
    countries.value = countriesResponse.data
      .map((country) => ({
        name: country.name.common,
        code: country.cca2,
      }))
      .sort((a, b) => a.name.localeCompare(b.name));
  } catch (error) {
    console.error("Error fetching billing address or countries:", error);
    toast.error("Failed to load billing address or countries.");
  }
});

const saveBillingAddress = async () => {
  try {
    const headers = {
      Authorization: `Bearer ${localStorage.getItem("token")}`,
    };

    if (billingAddress.value.id) {
      // Update existing billing address
      await axios.put(
        `${API_URL}/billing-address/update/${billingAddress.value.id}`,
        {
          address1: billingAddress.value.address1,
          country: billingAddress.value.country,
          state: billingAddress.value.state,
          city: billingAddress.value.city,
          postal_code: billingAddress.value.postalCode,
        },
        { headers }
      );
      toast.success("Billing address updated successfully!");
    } else {
      // Create new billing address
      await axios.post(
        `${API_URL}/billing-address/create`,
        {
          user_id: userId,
          address1: billingAddress.value.address1,
          country: billingAddress.value.country,
          state: billingAddress.value.state,
          city: billingAddress.value.city,
          postal_code: billingAddress.value.postalCode,
        },
        { headers }
      );
      toast.success("Billing address saved successfully!");
    }
  } catch (error) {
    console.error("Error saving billing address:", error);
    toast.error("Failed to save billing address.");
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

.form-group input,
.form-group select {
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
