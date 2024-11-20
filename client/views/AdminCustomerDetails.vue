<template>
  <div class="admin-details-wrapper">
    <!-- Loading state -->
    <div v-if="loading" class="loading-spinner">
      Loading customer details...
    </div>

    <!-- Error state -->
    <div v-if="error" class="error-message">{{ error }}</div>

    <div class="admin-details-container" v-if="customerData && !loading">
      <div class="admin-details-header">
        <h1>Customer Profile</h1>
        <div class="customer-id">ID: {{ customerData?.id }}</div>
      </div>

      <div class="admin-details-content">
        <!-- Customer Info -->
        <div class="admin-details-section">
          <div class="section-header">
            <h2>Personal Information</h2>
            <span class="customer-since"
              >Member since {{ formatDate(customerData?.created_at) }}</span
            >
          </div>
          <div class="info-grid">
            <div class="info-item">
              <label>Name</label>
              <span>{{ customerData?.name }}</span>
            </div>
            <div class="info-item">
              <label>Email</label>
              <span>{{ customerData?.email }}</span>
            </div>
            <div class="info-item">
              <label>Phone</label>
              <span>{{ customerData?.phonenumber }}</span>
            </div>
            <div class="info-item">
              <label>Account Type</label>
              <span class="status-badge">{{ customerData?.role }}</span>
            </div>
          </div>
        </div>

        <!-- Shipping Address -->
        <div class="admin-details-section" v-if="shippingData?.length">
          <div class="section-header">
            <h2>Shipping Address</h2>
          </div>
          <div class="info-grid">
            <div class="info-item">
              <label>Street</label>
              <span>{{ shippingData[0]?.address1 }}</span>
            </div>
            <div class="info-item">
              <label>City</label>
              <span>{{ shippingData[0]?.city }}</span>
            </div>
            <div class="info-item">
              <label>State</label>
              <span>{{ shippingData[0]?.state }}</span>
            </div>
            <div class="info-item">
              <label>Country</label>
              <span>{{ shippingData[0]?.country }}</span>
            </div>
            <div class="info-item">
              <label>Postal Code</label>
              <span>{{ shippingData[0]?.postal_code }}</span>
            </div>
          </div>
        </div>

        <!-- Billing Address -->
        <div class="admin-details-section" v-if="billingData?.length">
          <div class="section-header">
            <h2>Billing Address</h2>
          </div>
          <div class="info-grid">
            <div class="info-item">
              <label>Street</label>
              <span>{{ billingData[0]?.address1 }}</span>
            </div>
            <div class="info-item">
              <label>City</label>
              <span>{{ billingData[0]?.city }}</span>
            </div>
            <div class="info-item">
              <label>State</label>
              <span>{{ billingData[0]?.state }}</span>
            </div>
            <div class="info-item">
              <label>Country</label>
              <span>{{ billingData[0]?.country }}</span>
            </div>
            <div class="info-item">
              <label>Postal Code</label>
              <span>{{ billingData[0]?.postal_code }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { useRoute } from "vue-router"; // Import useRoute to access route params
import axios from "axios";

// Use the environment variable for the API URL
const API_URL = import.meta.env.VITE_API_URL;

const customerData = ref(null);
const shippingData = ref(null);
const billingData = ref(null);
const loading = ref(false); // Track loading state
const error = ref(null); // Track error message

// Get the userId from route params
const route = useRoute();
const userId = ref(route.params.id); // Assuming the route has an `id` parameter

const formatDate = (dateString) => {
  if (!dateString) return "";
  return new Date(dateString).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};

const fetchCustomerDetails = async () => {
  loading.value = true; // Set loading to true before making requests
  error.value = null; // Reset error before fetching data

  try {
    const token = localStorage.getItem("token");

    const [customerRes, shippingRes, billingRes] = await Promise.all([
      axios.get(`${API_URL}/admin/users/${userId.value}`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }),
      axios.get(`${API_URL}/shipping-address/user/${userId.value}`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }),
      axios.get(`${API_URL}/billing-address/user/${userId.value}`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }),
    ]);

    customerData.value = customerRes.data;
    shippingData.value = shippingRes.data;
    billingData.value = billingRes.data;
  } catch (err) {
    error.value = "Error fetching customer details. Please try again later."; // Set error message
    console.error("Error fetching customer details:", err);
  } finally {
    loading.value = false; // Set loading to false once the requests are complete
  }
};

// Watch for changes in the route to refetch data when userId changes
watch(
  () => route.params.id,
  (newUserId) => {
    userId.value = newUserId;
    fetchCustomerDetails(); // Fetch details for the new userId
  },
  { immediate: true } // Immediately fetch data when the component mounts
);

onMounted(() => {
  fetchCustomerDetails(); // Fetch details on initial mount
});
</script>

<style scoped>
.admin-details-wrapper {
  width: 100%;
  min-height: 100vh;
  background: var(--background-color);
  padding: 0 50px;
}

.admin-details-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.admin-details-header h1 {
  font-size: 1.75rem;
  color: var(--dark-color);
  margin: 0;
}

.customer-id {
  font-size: 1rem;
  color: var(--background-color);
  background: var(--dark-tint);
  padding: 0.5rem 1rem;
  border-radius: 6px;
}

.admin-details-content {
  display: grid;
  gap: 2rem;
}

.admin-details-section {
  background-color: var(--background-color);
  border-radius: 8px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
  padding: 1.5rem;
  border: 1px solid #ddd;
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #ddd;
}

.section-header h2 {
  font-size: 1.25rem;
  color: var(--dark-color);
  margin: 0;
}

.customer-since {
  font-size: 0.875rem;
  color: var(--primary-color);
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
}

.info-item {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.info-item label {
  font-size: 0.875rem;
  color: var(--dark-color);
}

.info-item span {
  font-size: 1rem;
  color: var(--dark-color);
  font-weight: 500;
}

.status-badge {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  background: #0f91ee;
  border-radius: 4px;
  font-size: 0.875rem;
  font-weight: 500;
  width: 30%;
}

.loading-spinner {
  font-size: 1rem;
  color: var(--primary-color);
  text-align: center;
}

.error-message {
  color: var(--error-message);
  font-size: 1rem;
  text-align: center;
}

@media (max-width: 768px) {
  .admin-details-wrapper {
    padding: 1rem;
  }

  .info-grid {
    grid-template-columns: 1fr;
  }

  .section-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }
}
</style>
