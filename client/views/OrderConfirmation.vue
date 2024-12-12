<template>
  <div class="success-container">
    <div class="success-card">
      <div class="success-icon">✓</div>
      <h1>Thank You for Your Order!</h1>
      <p>Your purchase has been successfully completed.</p>
      <div class="order-details">
        <div class="detail-shimmer">
          <p>
            Order Number:
            <strong class="shimmer-text">{{ orderNumber }}</strong>
          </p>
          <p>
            Confirmation sent to:
            <strong class="shimmer-text">{{ customerEmail }}</strong>
          </p>
        </div>
      </div>
      <div class="action-buttons">
        <button @click="goToHomePage" class="btn-continue">
          Continue Shopping
        </button>
        <button @click="viewOrderDetails" class="btn-view-order">
          View Order Details
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";

// Generate a random order number for demonstration
const orderNumber = ref(`ORDER-${Math.floor(Math.random() * 1000000)}`);
const customerEmail = ref("customer@example.com");

const router = useRouter();

const goToHomePage = () => {
  router.push("/shop");
};

const viewOrderDetails = () => {
  router.push(`/order/${orderNumber.value}`);
};
</script>

<style scoped>
@keyframes shimmer {
  0% {
    background-position: -1000px 0;
  }
  100% {
    background-position: 1000px 0;
  }
}

.success-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: var(--background-color);
}

.success-card {
  background-color: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.125);
  padding: 40px;
  text-align: center;
  max-width: 500px;
  width: 100%;
  animation: fadeIn 1.2s ease-out;
}

.order-details {
  background-color: #f9f9f9;
  padding: 20px;
  border-radius: 8px;
  margin: 20px 0;
  text-align: left;
  position: relative;
  overflow: hidden;
}

.detail-shimmer {
  position: relative;
}

.shimmer-text {
  position: relative;
  color: var(--dark-color);
  display: inline-block;
}

.shimmer-text::after {
  content: "";
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  transform: skewX(-20deg);
  background: linear-gradient(
    to right,
    transparent 0%,
    rgba(255, 255, 255, 0.4) 50%,
    transparent 100%
  );
  animation: shimmer 7s infinite linear;
}

.success-icon {
  font-size: 80px;
  color: var(--green-color);
  margin-bottom: 20px;
  animation: popIn 1s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.9);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

@keyframes popIn {
  from {
    transform: scale(0);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}

h1 {
  color: var(--dark-color);
  margin-bottom: 15px;
  animation: slideIn 1s ease-out;
}

@keyframes slideIn {
  from {
    transform: translateY(-20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.action-buttons {
  display: flex;
  justify-content: space-between;
  gap: 15px;
}

.btn-continue,
.btn-view-order {
  flex: 1;
  padding: 12px 20px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
  transition: all 0.3s ease;
}

.btn-continue {
  background-color: var(--dark-tint);
  color: white;
}

.btn-view-order {
  background-color: var(--green-color);
  color: white;
}
</style>
