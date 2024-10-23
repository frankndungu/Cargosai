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
import { ref, onMounted } from "vue";
import { useStore } from "vuex";
import { useToast } from "vue-toast-notification";

const store = useStore();
const toast = useToast();
const billingAddress = ref({
  address1: "",
  country: "",
  city: "",
  state: "",
  postalCode: "",
});

const fetchBillingAddress = async () => {
  try {
    const userId = store.state.user.id;
    // console.log("Fetching billing address for user ID:", userId);

    const response = await fetch(
      `${import.meta.env.VITE_API_URL}/billing-address/user/${userId}`,
      {
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );
    if (!response.ok) {
      throw new Error("Failed to fetch billing address.");
    }
    const data = await response.json();
    // console.log("Fetched billing address:", data);

    // If data exists, map it to the billingAddress object
    if (Array.isArray(data) && data.length > 0) {
      billingAddress.value = {
        address1: data[0].address1 || "",
        country: data[0].country || "",
        city: data[0].city || "",
        state: data[0].state || "",
        postalCode: data[0].postal_code || "",
      };
    } else {
      toast.info("No billing address found, please enter one.");
    }
  } catch (error) {
    toast.error("Error fetching billing address.");
    console.error("Error fetching billing address:", error);
  }
};

const saveBillingAddress = async () => {
  try {
    const userId = store.state.user.id;
    // console.log("Saving billing address for user ID:", userId);
    // console.log("Billing address to save:", billingAddress.value);

    const response = await fetch(
      `${import.meta.env.VITE_API_URL}/billing-address/create`,
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        body: JSON.stringify({ ...billingAddress.value, user_id: userId }),
      }
    );

    if (!response.ok) {
      throw new Error("Failed to save billing address.");
    }

    toast.success("Billing Address saved successfully!");
  } catch (error) {
    toast.error("Error saving billing address.");
    console.error("Error saving billing address:", error);
  }
};

// Fetch the billing address on component mount
onMounted(fetchBillingAddress);
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
