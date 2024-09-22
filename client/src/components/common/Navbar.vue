<template>
  <header class="header">
    <nav class="navbar">
      <div class="navbar__title">
        <router-link to="/" class="navbar__link-title" @click="closeMenu">
          <div class="navbar__logo-text">
            <span class="logo-main">maasai market</span>
            <span class="logo-sub">online</span>
          </div>
        </router-link>
      </div>
      <div class="navbar__toggle" @click="toggleMenu">
        <div :class="{ 'navbar__toggle-bar': true, active: isMenuOpen }"></div>
        <div :class="{ 'navbar__toggle-bar': true, active: isMenuOpen }"></div>
        <div :class="{ 'navbar__toggle-bar': true, active: isMenuOpen }"></div>
      </div>
      <ul class="navbar__links" :class="{ 'navbar__links--open': isMenuOpen }">
        <li><router-link to="/about" @click="closeMenu">About</router-link></li>
        <li><router-link to="/shop" @click="closeMenu">Shop</router-link></li>
        <li>
          <router-link to="/contact" @click="closeMenu">Contact</router-link>
        </li>
      </ul>
      <div class="navbar__actions">
        <router-link to="/login" @click="closeMenu">
          <button>Login</button>
        </router-link>
        <router-link to="/cart" @click="closeMenu" class="navbar__cart">
          <img
            src="https://res.cloudinary.com/kwishi/image/upload/v1725263913/shopping-bag-icon_h04zp7.webp"
            alt="Cart"
          />
          <!-- Display cart item count as a badge -->
          <span v-if="cartItemCount > 0" class="cart-badge">{{
            cartItemCount
          }}</span>
        </router-link>
      </div>
    </nav>
  </header>
</template>

<script setup>
import { ref, computed } from "vue";
import { useStore } from "vuex";

const isMenuOpen = ref(false);
function toggleMenu() {
  isMenuOpen.value = !isMenuOpen.value;
}

function closeMenu() {
  isMenuOpen.value = false;
}

const store = useStore();

// Access the cart item count from the Vuex store
const cartItemCount = computed(() => store.getters.cartItemCount);
</script>
