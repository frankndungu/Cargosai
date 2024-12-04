<template>
  <div class="cart-wrapper">
    <div class="cart-container">
      <h1 class="cart-title">Your Cart</h1>

      <div v-if="cartItems.length === 0" class="empty-cart">
        <h3 class="empty-cart-title">Something is missing</h3>
        <p class="empty-cart-description">Your cart is empty</p>
        <button @click="continueShopping" class="continue-shopping-btn">
          Continue Shopping
        </button>
      </div>

      <div v-else class="cart-layout">
        <div class="cart-items-section">
          <div v-for="item in cartItems" :key="item.id" class="cart-item-card">
            <div class="item-image-container">
              <img
                :src="getImageUrl(item.main_image)"
                :alt="item.name"
                class="item-image"
                loading="lazy"
                @click="goToProductPage(item.slug)"
              />
            </div>
            <div class="item-details">
              <div class="item-header">
                <h3
                  class="item-name"
                  @click="goToProductPage(item.slug)"
                  @mouseover="hoverEffect"
                  @mouseleave="removeHoverEffect"
                >
                  {{ item.name }}
                </h3>
                <span class="item-price">${{ item.price.toFixed(2) }}</span>
              </div>
              <p class="item-description">{{ item.description }}</p>
              <div class="item-actions">
                <div class="quantity-control">
                  <button @click="decreaseQuantity(item.id)" class="qty-btn">
                    -
                  </button>
                  <span class="qty-display">{{ item.quantity }}</span>
                  <button @click="increaseQuantity(item.id)" class="qty-btn">
                    +
                  </button>
                </div>
                <button @click="removeItem(item.id)" class="remove-item-btn">
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
              <span>Shipping</span>
              <span>Calculated at Checkout</span>
            </div>
            <div class="summary-row total">
              <span>Total</span>
              <span>${{ total.toFixed(2) }}</span>
            </div>
          </div>

          <button @click="proceedToCheckout" class="checkout-btn">
            Proceed to Checkout
          </button>

          <div class="promo-banner">
            <p>🌿 Shopping small, impacting big—thank you!</p>
          </div>

          <!-- Payment Logos -->
          <div class="payment-logos">
            <img
              src="https://res.cloudinary.com/kwishi/image/upload/v1733219157/visa_wlume7.svg"
              alt="Visa"
              class="payment-logo"
            />
            <img
              src="https://res.cloudinary.com/kwishi/image/upload/v1733219157/mastercard_hd0shs.svg"
              alt="Mastercard"
              class="payment-logo"
            />
            <img
              src="https://res.cloudinary.com/kwishi/image/upload/v1733219157/apple_pay_by7btv.svg"
              alt="Apple Pay"
              class="payment-logo"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";

const storageBaseUrl = import.meta.env.VITE_STORAGE_BASE_URL;

const store = useStore();
const router = useRouter();

// Reactive property for current user ID
const currentUserId = computed(() => store.state.cart.currentUserId);

// Fetch the current user on component mount
onMounted(async () => {
  try {
    await store.dispatch("fetchCurrentUser"); // Use namespace if applicable
    console.log("User ID in Cart.vue after fetch:", currentUserId.value); // Log after fetch
  } catch (error) {
    console.error("Failed to fetch current user in Cart.vue:", error);
  }
});

const cartItems = computed(() => store.getters.cartItems);

// Compute subtotal and total
const subtotal = computed(() =>
  cartItems.value.reduce((total, item) => total + item.price * item.quantity, 0)
);
const total = computed(() => subtotal.value);

// Action to increase item quantity
const increaseQuantity = (id) => {
  store.dispatch("updateCartItem", { productId: id, quantity: 1 });
};

// Action to decrease item quantity with minimum validation
const decreaseQuantity = (id) => {
  const item = cartItems.value.find((item) => item.id === id);
  if (item && item.quantity > 1) {
    store.dispatch("updateCartItem", { productId: id, quantity: -1 });
  }
};

// Remove an item from the cart
const removeItem = (id) => {
  if (confirm("Are you sure you want to remove this item?")) {
    store.dispatch("removeFromCart", id);
  }
};

// Proceed to checkout or redirect to login
const proceedToCheckout = () => {
  console.log("Is Logged In:", store.getters.isLoggedIn);

  if (store.getters.isLoggedIn) {
    router.push("/checkout");
  } else {
    router.push("/login");
  }
};

// Continue shopping by navigating to the shop page
const continueShopping = () => {
  router.push("/shop");
};

// Navigate to a product's details page
const goToProductPage = (slug) => {
  router.push(`/shop/product/${slug}`);
};

// Handle hover effects for better UI feedback
const hoverEffect = (event) => {
  event.target.style.textDecoration = "underline";
  event.target.style.cursor = "pointer";
};
const removeHoverEffect = (event) => {
  event.target.style.textDecoration = "";
};

// Get the full image URL
const getImageUrl = (imagePath) => {
  return imagePath
    ? `${storageBaseUrl}/${imagePath}`
    : "default-placeholder.png";
};
</script>

<style scoped>
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

.cart-wrapper {
  padding: 40px 50px;
}

.cart-container {
  background: var(--background-color);
}

.cart-title {
  text-align: left;
  margin-bottom: 30px;
  color: var(--dark-color);
  font-weight: bold;
  font-size: 1.35rem;
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
  /* background-color: white; */
  /* box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05); */
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
  border-radius: 10px;
  cursor: pointer;
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
  margin: 5px 0 5px;
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

.item-description {
  font-size: 0.9rem;
  color: var(--dark-color);
  margin-bottom: 20px;
  line-height: 1.4;
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
  font-size: small;
  position: relative;
  overflow: hidden;
  animation: shimmer 3s infinite linear;
  background: linear-gradient(to right, #f1c40f 0%, #f0db4f 50%, #f1c40f 100%);
  background-size: 200% 100%;
}

.promo-banner::before {
  content: "";
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(255, 255, 255, 0.3),
    transparent
  );
  animation: shine 2s infinite;
}

@keyframes shimmer {
  0% {
    background-position: -100% 0;
  }
  100% {
    background-position: 100% 0;
  }
}

@keyframes shine {
  0% {
    left: -100%;
  }
  100% {
    left: 100%;
  }
}

.empty-cart {
  text-align: center;
  padding: 50px;
  background-color: white;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
}

.empty-cart-title {
  font-size: 1.98rem;
}

.empty-cart-description {
  font-size: 1.45rem;
  margin-top: 20px;
}

.continue-shopping-btn {
  background-color: var(--dark-tint);
  color: var(--background-color);
  padding: 12px 30px;
  border-radius: 8px;
  font-weight: bold;
  cursor: pointer;
}

/* Payment logos */
.payment-logos {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-top: 20px;
}

.payment-logo {
  width: 50px;
  height: auto;
  object-fit: contain;
}

/* Responsive Styles */
@media screen and (max-width: 1024px) {
  .cart-wrapper {
    padding: 20px;
  }

  .cart-layout {
    flex-direction: column;
    gap: 20px;
  }

  .cart-title {
    margin-top: 20px;
    font-size: 1.5rem;
    text-align: center;
  }
}

@media screen and (max-width: 768px) {
  .cart-item-card {
    flex-direction: column;
    position: relative;
  }

  .item-image-container {
    width: 100%;
    height: 300px; /* Fixed height for consistency */
    overflow: hidden; /* Prevent image from expanding */
  }

  .item-image {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Ensures image covers entire container */
    object-position: center; /* Center the image */
    transition: transform 0.3s ease; /* Smooth zoom effect */
  }

  .item-image:hover {
    transform: scale(1.05); /* Slight zoom on hover for visual interest */
  }

  .item-details {
    padding: 15px;
    position: relative;
    z-index: 1;
  }

  .item-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 15px;
  }

  .item-actions {
    flex-direction: column;
    gap: 10px;
    align-items: flex-start;
  }
}

@media screen and (max-width: 480px) {
  .cart-wrapper {
    padding: 20px 10px;
  }

  .cart-container {
    padding: 10px;
  }

  .item-image-container {
    height: 250px; /* Slightly smaller on very small screens */
  }

  .item-details {
    padding: 10px;
  }

  .order-summary-card {
    padding: 20px;
  }

  .summary-title {
    font-size: 1.2rem;
    text-align: center;
  }

  .summary-row {
    font-size: 0.9rem;
  }

  .promo-banner {
    font-size: x-small;
    padding: 8px;
  }
}
</style>
