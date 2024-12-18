<template>
  <div class="order-details">
    <div v-if="loading" class="loading">Loading order details...</div>

    <div v-else-if="error" class="error">
      {{ error }}
    </div>

    <div v-else-if="order" class="order-container">
      <header class="order-header">
        <h2>Order #{{ order.formatted_id || order.id }}</h2>
        <div class="status-badge" :class="order.payment_status.toLowerCase()">
          {{ order.payment_status }}
        </div>
      </header>

      <div class="order-grid">
        <div class="order-section">
          <h3>Order Information</h3>
          <div class="info-grid">
            <div class="info-item">
              <span class="label">Reference:</span>
              <span class="value">{{ order.reference }}</span>
            </div>
            <div class="info-item">
              <span class="label">User ID:</span>
              <span class="value">{{ order.user_id }}</span>
            </div>
            <div class="info-item">
              <span class="label">Created at:</span>
              <span class="value">{{
                new Date(order.created_at).toLocaleDateString()
              }}</span>
            </div>
            <div class="info-item">
              <span class="label">Shipping Fee:</span>
              <span class="value">${{ order.shipping_fee }}</span>
            </div>
            <div class="info-item">
              <span class="label">Total Price:</span>
              <span class="value">${{ order.total_price.toFixed(2) }}</span>
            </div>
          </div>
        </div>

        <div class="order-section">
          <h3>Status Management</h3>
          <div class="status-controls">
            <div class="select-group">
              <label>Order Status:</label>
              <select v-model="order.status" :disabled="updating">
                <option
                  v-for="status in orderStatuses"
                  :key="status"
                  :value="status"
                >
                  {{ status }}
                </option>
              </select>
            </div>

            <button
              @click="updateOrderStatus"
              :disabled="updating"
              class="update-button"
            >
              {{ updating ? "Updating..." : "Update Status" }}
            </button>
          </div>
        </div>
      </div>

      <!-- Order Items Section -->
      <div v-if="orderItems" class="order-items">
        <h3>Order Items</h3>
        <div class="items-list">
          <div v-for="item in orderItems" :key="item.id" class="item-card">
            <div class="item-details">
              <span class="item-name">{{ item.name }}</span>
              <span class="item-quantity">Qty: {{ item.quantity }}</span>
              <span class="item-price">${{ item.price }}</span>
              <span class="item-total"
                >Total: ${{ (item.price * item.quantity).toFixed(2) }}</span
              >
            </div>
          </div>
        </div>
      </div>

      <div v-if="successMessage" class="success-message">
        {{ successMessage }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRoute } from "vue-router";

// Local state
const route = useRoute();
const orderId = route.params.id; // Get orderId from URL parameter

const order = ref(null);
const orderItems = ref(null);
const loading = ref(true);
const error = ref(null);
const successMessage = ref("");
const orderStatuses = ["Pending", "Shipped", "Delivered", "Canceled"];
const updating = ref(false);

// Methods
const fetchOrderDetails = async () => {
  try {
    loading.value = true;
    const response = await axios.get(
      `${import.meta.env.VITE_API_URL}/orders/${orderId}`,
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );
    order.value = response.data;
  } catch (err) {
    error.value =
      err.response?.data?.message || "Failed to fetch order details";
  } finally {
    loading.value = false;
  }
};

const fetchOrderItems = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_API_URL}/orders/${orderId}/details`,
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );
    orderItems.value = response.data.items; // Assuming the response contains items
  } catch (err) {
    error.value = err.response?.data?.message || "Failed to fetch order items";
  }
};

const updateOrderStatus = async () => {
  try {
    updating.value = true;
    await axios.put(
      `${import.meta.env.VITE_API_URL}/orders/${orderId}/status`,
      {
        status: order.value.status,
        payment_status: order.value.payment_status,
      },
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );
    successMessage.value = "Order updated successfully";
    setTimeout(() => {
      successMessage.value = "";
    }, 3000);
  } catch (err) {
    error.value =
      err.response?.data?.message || "Failed to update order status";
    setTimeout(() => {
      error.value = null;
    }, 3000);
  } finally {
    updating.value = false;
  }
};

// Lifecycle
onMounted(() => {
  fetchOrderDetails();
  fetchOrderItems();
});
</script>

<style>
.order-details {
  padding: 0 50px;
}

.order-container {
  background: var(--background-color);
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  padding: 2rem;
  margin-bottom: 30px;
}

.order-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  padding-bottom: 1rem;
  border-bottom: 2px solid #ddd;
}

.order-header h2 {
  margin: 0;
  color: var(--dark-color);
  font-size: 1.8rem;
}

.status-badge {
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-weight: 700;
  font-size: 0.9rem;
  border: 1px solid #ddd;
}

.status-badge.completed {
  background: #e6f4ea;
  color: var(--green-color);
}

.status-badge.pending {
  background: #fef7e6;
  color: var(--pending-color);
}

.status-badge.failed {
  background: #fce8e8;
  color: var(--error-message);
}

.status-badge.refund {
  background: #e8eaed;
  color: #5f6368;
}

.order-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
  margin-bottom: 2rem;
}

.order-section {
  padding: 1.5rem;
  border-radius: 8px;
  border: 1px solid #ddd;
}

.order-section h3 {
  margin-top: 0;
  color: var(--dark-color);
  margin-bottom: 1rem;
}

.info-grid {
  display: grid;
  gap: 1rem;
}

.info-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.label {
  color: var(--dark-color);
  font-weight: 500;
}

.value {
  font-weight: 600;
  color: var(--dark-color);
}

.status-controls {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.select-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.select-group label {
  font-weight: 500;
  color: var(--dark-color);
}

select {
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 6px;
  background: var(--background-color);
  font-size: 1rem;
  color: var(--dark-color);
}

.update-button {
  margin-top: 1rem;
  padding: 0.75rem 1.5rem;
  background: var(--dark-tint);
  color: var(--background-color);
  border: none;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s;
}

.update-button:hover {
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0);
}

.update-button:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}

.update-button:disabled {
  background: #ccc;
  cursor: not-allowed;
}

.order-items {
  margin-top: 2rem;
}

.items-list {
  display: grid;
  gap: 1rem;
  margin-top: 1rem;
}

.item-card {
  padding: 1rem;
  border-radius: 8px;
  border: 1px solid #ddd;
}

.item-details {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr;
  gap: 1rem;
}

.item-name {
  font-weight: 600;
  font-size: 1.1rem;
}

.item-quantity,
.item-price,
.item-total {
  font-weight: 500;
  font-size: 1rem;
}

.success-message,
.error {
  padding: 1rem;
  margin-top: 2rem;
  background-color: #dff0d8;
  color: #3c763d;
  border-radius: 6px;
  text-align: center;
}

.error {
  background-color: #f2dede;
  color: #a94442;
}

@media (max-width: 768px) {
  .order-grid {
    grid-template-columns: 1fr;
  }

  .item-details {
    grid-template-columns: 1fr 1fr;
  }

  .order-header {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>
