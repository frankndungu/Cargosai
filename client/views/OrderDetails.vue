<template>
  <div class="dashboard">
    <Sidebar />
    <main class="main-content">
      <div class="order-details">
        <h2>Order ID: #{{ orderId }}</h2>
        <div v-if="order" class="order-info">
          <p class="date">
            <strong>Date:</strong>
            {{ new Date(order.created_at).toLocaleDateString() }}
          </p>
          <p>
            <strong>Status: </strong>
            <span :class="getStatusClass(order.status)">
              {{ order.status }}
            </span>
          </p>
          <p class="total">
            <strong>Total:</strong> ${{ order.total_price.toFixed(2) }}
          </p>

          <h3>Items:</h3>
          <ul class="items-list">
            <li v-for="item in order.items" :key="item.id" class="item">
              <img
                :src="item.image_url"
                alt="Product image"
                class="product-image"
              />
              <div class="item-details">
                <p class="item-name">{{ item.name }}</p>
                <p class="item-price">
                  ${{ validatePrice(item.price) }} x {{ item.quantity }} = ${{
                    validatePrice(item.price * item.quantity)
                  }}
                </p>
                <p class="item-description">{{ item.description }}</p>
              </div>
            </li>
          </ul>
        </div>
        <p v-else>Loading your order details...</p>
        <button @click="goBack" class="back-button">Back to Orders</button>
      </div>
    </main>
  </div>
</template>

<script setup>
import Sidebar from "@/components/ui/Sidebar.vue";
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";

const order = ref(null);
const route = useRoute();
const router = useRouter();
const orderId = route.params.id;

// Fetch order details when the component is mounted
onMounted(async () => {
  const API_URL = import.meta.env.VITE_API_URL; // Use the environment variable
  try {
    const response = await axios.get(`${API_URL}/orders/${orderId}/details`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token")}`, // Replace with your auth mechanism if different
      },
    });
    order.value = response.data; // Adjust based on the structure of your response
    // console.log("Fetched order details:", order.value); // Log the order details
  } catch (error) {
    console.error("Failed to fetch order details:", error);
  }
});

// Method to go back to the orders page
const goBack = () => {
  router.push({ path: "/dashboard/orders" });
};

// Helper method to determine the status class for styling
const getStatusClass = (status) => {
  switch (status.toLowerCase()) {
    case "pending":
      return "status-pending";
    case "shipped":
      return "status-shipped";
    case "delivered":
      return "status-delivered";
    case "canceled":
      return "status-canceled";
    default:
      return "";
  }
};

// Validate price before formatting
const validatePrice = (price) => {
  if (typeof price === "number") {
    return price.toFixed(2);
  } else if (typeof price === "string" && !isNaN(price)) {
    return parseFloat(price).toFixed(2);
  } else {
    console.warn("Invalid price value:", price);
    return "0.00"; // Default value if price is invalid
  }
};
</script>

<style scoped>
.dashboard {
  height: 100vh;
}

.order-details {
  padding: 20px;
  background: var(--background-color);
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.order-info p {
  margin-bottom: 8px;
  color: var(--dark-color);
  line-height: 1.5;
}

.date {
  font-weight: 650;
}
.total {
  font-weight: 650;
}

.order-details h2 {
  margin-bottom: 20px;
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--dark-color);
}

.status-pending {
  background: var(--pending-color);
  color: var(--background-color);
  padding: 5px 10px;
  border-radius: 5px;
  font-size: 0.9rem;
}

.status-shipped {
  background: var(--shipped-color);
  color: var(--background-color);
  padding: 5px 10px;
  border-radius: 5px;
  font-size: 0.9rem;
}

.status-delivered {
  background: var(--green-color);
  color: var(--background-color);
  padding: 5px 10px;
  border-radius: 5px;
  font-size: 0.9rem;
}

.status-canceled {
  background: var(--canceled-color);
  color: var(--background-color);
  padding: 5px 10px;
  border-radius: 5px;
  font-size: 0.9rem;
}

.items-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.item {
  display: flex;
  align-items: center;
  border-bottom: 1px solid #ddd;
  padding: 15px 0;
}

.product-image {
  width: 70px;
  height: 70px;
  object-fit: cover;
  border-radius: 5px;
  margin-right: 15px;
}

.item-details {
  flex-grow: 1;
}

.item-name {
  font-weight: 700;
  color: var(--dark-color);
}

.item-price {
  color: var(--dark-color);
  font-weight: 650;
}

.item-description {
  color: var(--dark-color);
  font-size: 0.9rem;
  margin-top: 5px;
  font-weight: 600;
  font-style: oblique;
  line-height: 1.5;
}

.back-button {
  margin-top: 20px;
  padding: 12px 20px;
  background: var(--dark-tint);
  color: var(--background-color);
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background 0.3s ease;
}

.back-button:hover {
  background-color: var(--dark-tint);
}

.back-button:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.2);
}

@media (max-width: 768px) {
  .dashboard {
    height: auto;
  }

  .main-content {
    padding: 1rem;
  }

  .item {
    flex-direction: column;
    align-items: flex-start;
  }

  .product-image {
    margin-bottom: 10px;
  }

  .item-details {
    text-align: left;
  }

  .order-details {
    padding: 15px;
  }

  .order-details h2 {
    font-size: 1.2rem;
  }
}
</style>
