<template>
  <main class="main">
    <div class="cart-page">
      <h1 class="cart-title">Your Cart</h1>
      <div v-if="cartItemCount === 0" class="empty-cart">
        <h2>Something's missing.</h2>
        <p>There's nothing in your cart.</p>
        <button @click="goToShop">Back to Shop</button>
      </div>
      <div v-else class="cart-items">
        <p class="cart-items-description">
          ⏰ All products are purchased on preorder and take 3-4 days to
          fulfill. Shipping costs are not included in the price and vary
          depending on your delivery location. 🚢
        </p>
        <table class="cart-table">
          <thead>
            <tr>
              <th>Product</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Total</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in cartItems" :key="item._id">
              <td class="product-details">
                <img
                  :src="item.imageUrl"
                  alt="Product image"
                  class="product-image-cart"
                />
                <span>{{ item.name }}</span>
              </td>
              <td class="cart-price">
                ${{ (parseFloat(item.price) || 0).toFixed(2) }}
              </td>
              <td>
                <div class="quantity-controls">
                  <button @click="decreaseQuantity(item._id)">-</button>
                  <span>{{ item.quantity }}</span>
                  <button @click="increaseQuantity(item._id)">+</button>
                </div>
              </td>
              <td class="cart-price">
                ${{
                  (
                    parseFloat(item.price) || 0 * (parseInt(item.quantity) || 0)
                  ).toFixed(2)
                }}
              </td>
              <td>
                <button
                  @click="removeItem(item._id)"
                  class="transparent-button"
                >
                  Remove
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="cart-summary">
          <p>Total price: ${{ totalPrice }}</p>
          <button @click="proceedToCheckout">Proceed to Checkout</button>
        </div>
      </div>
    </div>
  </main>
</template>

<script setup>
import { computed } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";

const store = useStore();
const router = useRouter();

// Access cart items and count
const cartItems = computed(() => store.getters.cartItems);
const cartItemCount = computed(() => store.getters.cartItemCount);
const totalPrice = computed(() => store.getters.cartTotalPrice);

// Functions to handle cart actions
const decreaseQuantity = (id) => {
  store.dispatch("decreaseItemQuantity", id);
};

const increaseQuantity = (id) => {
  store.dispatch("increaseItemQuantity", id);
};

const removeItem = (id) => {
  store.dispatch("removeFromCart", id);
};

const goToShop = () => {
  router.push("/shop");
};

const proceedToCheckout = () => {
  router.push("/checkout");
};
</script>
