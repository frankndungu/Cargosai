<template>
  <div class="cart-page">
    <h1>Your Cart</h1>

    <!-- Display this message when the cart is empty -->
    <div v-if="cartItemCount === 0" class="empty-cart">
      <h2>Something's missing.</h2>
      <p>There's nothing in your cart.</p>
      <button @click="goToShop">Back to Shop</button>
    </div>

    <!-- Display the cart items when the cart is not empty -->
    <div v-else class="cart-items">
      <ul>
        <li v-for="item in cartItems" :key="item._id">
          <div>{{ item.name }} - {{ item.price }} (x{{ item.quantity }})</div>
          <button @click="removeItem(item._id)">Remove</button>
        </li>
      </ul>
      <div class="cart-summary">
        <p>Total items: {{ cartItemCount }}</p>
        <p>Total price: ${{ totalPrice }}</p>
        <button @click="proceedToCheckout">Proceed to Checkout</button>
      </div>
    </div>
  </div>
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
