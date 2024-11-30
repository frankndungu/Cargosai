<!-- Cart.vue -->
<template>
  <div class="cart-wrapper">
    <div class="cart-container">
      <h1 class="cart-title">Your Shopping Cart</h1>

      <div v-if="cartItems.length === 0" class="empty-cart">
        <h3 class="empty-cart-title">Something is missing</h3>
        <p class="empty-cart-description">Your cart is empty</p>
        <button class="continue-shopping-btn">Continue Shopping</button>
      </div>

      <div v-else class="cart-layout">
        <div class="cart-items-section">
          <div v-for="item in cartItems" :key="item.id" class="cart-item-card">
            <div class="item-image-container">
              <img :src="item.image" :alt="item.name" class="item-image" />
              <div class="item-badge">{{ item.category }}</div>
            </div>
            <div class="item-details">
              <div class="item-header">
                <h3 class="item-name">{{ item.name }}</h3>
                <span class="item-price">${{ item.price.toFixed(2) }}</span>
              </div>

              <div class="item-actions">
                <div class="quantity-control">
                  <button @click="decreaseQuantity(item)" class="qty-btn">
                    -
                  </button>
                  <span class="qty-display">{{ item.quantity }}</span>
                  <button @click="increaseQuantity(item)" class="qty-btn">
                    +
                  </button>
                </div>
                <button @click="removeItem(item)" class="remove-item-btn">
                  🗑️ Remove
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="order-summary-card">
          <h2 class="summary-title">📦 Order Summary</h2>

          <div class="summary-details">
            <div class="summary-row">
              <span>Subtotal</span>
              <span>${{ subtotal.toFixed(2) }}</span>
            </div>
            <div class="summary-row">
              <span>Shipping 🚚</span>
              <span>Calculated at Checkout</span>
            </div>
            <div class="summary-row total">
              <span>Total</span>
              <span>${{ total.toFixed(2) }}</span>
            </div>
          </div>

          <button class="checkout-btn">Proceed to Checkout</button>

          <!-- <div class="promo-banner">
            <p>🎉 Free shipping on orders over $50!</p>
          </div> -->
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";

const cartItems = ref([
  {
    id: 1,
    name: "Classic Soft T-Shirt",
    price: 29.99,
    quantity: 1,
    image:
      "https://royallooks.co.ke/wp-content/uploads/2020/09/royal-looks-kenya-2.jpg",
    category: "Clothing",
  },
  {
    id: 2,
    name: "Vintage Denim Jacket",
    price: 89.99,
    quantity: 1,
    image:
      "https://royallooks.co.ke/wp-content/uploads/2020/09/royal-looks-kenya-2.jpg",
    category: "Outerwear",
  },
]);

const subtotal = computed(() =>
  cartItems.value.reduce((total, item) => total + item.price * item.quantity, 0)
);

const tax = computed(() => subtotal.value * 0.1);
const total = computed(() => subtotal.value + tax.value);

const increaseQuantity = (item) => {
  item.quantity++;
};

const decreaseQuantity = (item) => {
  if (item.quantity > 1) {
    item.quantity--;
  }
};

const removeItem = (itemToRemove) => {
  cartItems.value = cartItems.value.filter(
    (item) => item.id !== itemToRemove.id
  );
};
</script>

<style scoped>
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

.empty-cart-title {
  font-size: 1.98rem;
}

.empty-cart-description {
  font-size: 1.45rem;
  margin-top: 20px;
}
.cart-wrapper {
  padding: 40px 50px;
}

.cart-container {
  padding: 20px;
  background: var(--background-color);
}

.cart-title {
  text-align: center;
  margin-bottom: 30px;
  color: var(--dark-color);
  font-size: 2.5rem;
}

.cart-layout {
  display: flex;
  gap: 40px;
}

.cart-items-section {
  flex: 2;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.cart-item-card {
  display: flex;
  background-color: white;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
  overflow: hidden;
  transition: transform 0.3s ease;
}

.item-image-container {
  position: relative;
  width: 250px;
}

.item-image {
  width: 100%;
  height: 250px;
  object-fit: cover;
}

.item-badge {
  position: absolute;
  top: 10px;
  left: 10px;
  background-color: var(--accent-color);
  color: var(--background-color);
  padding: 5px 10px;
  border-radius: 20px;
  font-size: 0.8rem;
}

.item-details {
  flex-grow: 1;
  padding: 20px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.item-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.item-name {
  font-size: 1.2rem;
  color: var(--dark-color);
}

.item-price {
  font-weight: bold;
  color: var(--accent-color);
}

.item-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.quantity-control {
  display: flex;
  align-items: center;
  border: 1px solid #e0e0e0;
  border-radius: 20px;
  overflow: hidden;
}

.qty-btn {
  background: none;
  border: none;
  padding: 8px 12px;
  cursor: pointer;
  font-size: 1rem;
  background: var(--dark-tint);
}

.qty-display {
  padding: 0 10px;
  font-weight: bold;
}

.remove-item-btn {
  background: var(--dark-tint);
  color: var(--background-color);
  border: none;
  padding: 8px 15px;
  border-radius: 20px;
  cursor: pointer;
}

.order-summary-card {
  flex: 1;
  background-color: white;
  border-radius: 12px;
  padding: 30px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.summary-title {
  text-align: center;
  color: var(--dark-color);
  margin-bottom: 10px;
}

.summary-details {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  padding: 10px 0;
  border-bottom: 1px solid #e0e0e0;
}

.summary-row.total {
  font-weight: bold;
  color: var(--dark-color);
}

.checkout-btn {
  background: var(--dark-tint);
  color: var(--background-color);
  border: none;
  padding: 15px;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  font-weight: bold;
}

.checkout-btn:hover {
  background-color: var(--dark-color);
}

.promo-banner {
  background-color: #f1c40f;
  color: #2c3e50;
  text-align: center;
  padding: 10px;
  border-radius: 8px;
}

.empty-cart {
  text-align: center;
  padding: 50px;
  background-color: white;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
}

.empty-cart-image {
  width: 100px;
  margin-bottom: 20px;
}

.continue-shopping-btn {
  background-color: var(--dark-tint);
  color: var(--background-color);
  padding: 12px 30px;
  border-radius: 8px;
  font-weight: bold;
  cursor: pointer;
}
</style>
