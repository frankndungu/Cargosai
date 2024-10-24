<template>
  <div class="dashboard">
    <Sidebar />
    <main class="main-content">
      <div class="order-details">
        <h2>Order ID: {{ orderId }}</h2>
        <div v-if="order" class="order-info">
          <p>
            <strong>Date:</strong>
            {{ new Date(order.created_at).toLocaleDateString() }}
          </p>
          <p>
            <strong>Status: </strong>
            <span :class="getStatusClass(order.status)">
              {{ order.status }}
            </span>
          </p>
          <p><strong>Total:</strong> ${{ order.total_price | currency }}</p>

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
                  ${{ item.price | currency }} x {{ item.quantity }} = ${{
                    (item.price * item.quantity).toFixed(2)
                  }}
                </p>
                <p class="item-description">{{ item.description }}</p>
              </div>
            </li>
          </ul>
        </div>
        <p v-else>Loading order details...</p>
        <button @click="goBack" class="back-button">Back to Orders</button>
      </div>
    </main>
  </div>
</template>

<script setup>
import Sidebar from "@/components/ui/Sidebar.vue";
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";

// Dummy data for testing
const order = ref({
  id: 123,
  created_at: "2024-10-24T10:00:00Z",
  status: "Shipped",
  total_price: 120.5,
  items: [
    {
      id: 1,
      name: "Maasai Beaded Necklace",
      price: 30.0,
      quantity: 2,
      image_url: "https://via.placeholder.com/100",
      description: "A beautiful handmade Maasai beaded necklace.",
    },
    {
      id: 2,
      name: "African Print Dress",
      price: 60.0,
      quantity: 1,
      image_url: "https://via.placeholder.com/100",
      description: "Elegant African print dress made with vibrant fabrics.",
    },
  ],
});

const route = useRoute();
const router = useRouter();
const orderId = route.params.id || order.value.id;

// Mock fetch function since we are using dummy data
onMounted(() => {
  // Normally, you would call your fetchOrderDetails method here.
  console.log("Order details loaded with dummy data.");
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

// Currency filter for formatting total amounts
const currency = (value) => {
  return parseFloat(value).toFixed(2);
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
  background: var(--shipped-color);
  color: var(--background-color);
  padding: 5px 10px;
  border-radius: 5px;
  font-size: 0.9rem;
}

.status-canceled {
  background: var(--cancel-color);
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
  font-weight: 600;
  color: var(--dark-color);
}

.item-price {
  color: var(--dark-color);
}

.item-description {
  color: var(--dark-color);
  font-size: 0.9rem;
  margin-top: 5px;
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
