<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRoute } from "vue-router";

// Local state
const route = useRoute();
const orderId = route.params.id; // Get orderId from URL parameter

const order = ref(null);
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
onMounted(fetchOrderDetails);
</script>

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
              <span class="label">Total Price:</span>
              <span class="value">${{ order.total_price }}</span>
            </div>
            <div class="info-item">
              <span class="label">Created:</span>
              <span class="value">{{
                new Date(order.created_at).toLocaleDateString()
              }}</span>
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

      <div v-if="order.items" class="order-items">
        <h3>Order Items</h3>
        <div class="items-list">
          <div v-for="item in order.items" :key="item.id" class="item-card">
            <div class="item-details">
              <span class="item-name">Product #{{ item.product_id }}</span>
              <span class="item-quantity">Qty: {{ item.quantity }}</span>
              <span class="item-price">${{ item.price }}</span>
              <span class="item-total">Total: ${{ item.total }}</span>
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

<style>
.order-details {
  padding: 2rem;
  max-width: 1200px;
  margin: 0 auto;
}

.order-container {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  padding: 2rem;
}

.order-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  padding-bottom: 1rem;
  border-bottom: 2px solid #f0f0f0;
}

.order-header h2 {
  margin: 0;
  color: #2c3e50;
  font-size: 1.8rem;
}

.status-badge {
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-weight: 600;
  font-size: 0.9rem;
}

.status-badge.completed {
  background: #e6f4ea;
  color: #1e8e3e;
}

.status-badge.pending {
  background: #fef7e6;
  color: #b76e00;
}

.status-badge.failed {
  background: #fce8e8;
  color: #d93025;
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
  background: #f8f9fa;
  padding: 1.5rem;
  border-radius: 8px;
}

.order-section h3 {
  margin-top: 0;
  color: #2c3e50;
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
  color: #666;
  font-weight: 500;
}

.value {
  font-weight: 600;
  color: #2c3e50;
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
  color: #666;
}

select {
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 6px;
  background: white;
  font-size: 1rem;
  color: #2c3e50;
}

.update-button {
  margin-top: 1rem;
  padding: 0.75rem 1.5rem;
  background: #4caf50;
  color: white;
  border: none;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s;
}

.update-button:hover {
  background: #43a047;
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
  background: #f8f9fa;
  padding: 1rem;
  border-radius: 8px;
  border: 1px solid #eee;
}

.item-details {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr;
  gap: 1rem;
  align-items: center;
}

.item-name {
  font-weight: 600;
  color: #2c3e50;
}

.item-quantity,
.item-price,
.item-total {
  color: #666;
}

.success-message {
  margin-top: 1rem;
  padding: 1rem;
  background: #e6f4ea;
  color: #1e8e3e;
  border-radius: 6px;
  font-weight: 600;
}

.error {
  margin-top: 1rem;
  padding: 1rem;
  background: #fce8e8;
  color: #d93025;
  border-radius: 6px;
  font-weight: 600;
}

.loading {
  color: #888;
  font-size: 1.2rem;
}
</style>
