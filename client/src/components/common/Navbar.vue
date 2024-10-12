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

      <!-- Conditionally render the toggle menu (hide when authenticated and on the dashboard) -->
      <div
        class="navbar__toggle"
        v-if="!isAuthenticated || !isDashboard"
        @click="toggleMenu"
      >
        <div :class="{ 'navbar__toggle-bar': true, active: isMenuOpen }"></div>
        <div :class="{ 'navbar__toggle-bar': true, active: isMenuOpen }"></div>
        <div :class="{ 'navbar__toggle-bar': true, active: isMenuOpen }"></div>
      </div>

      <!-- Conditionally hide links on the dashboard -->
      <ul
        class="navbar__links"
        :class="{ 'navbar__links--open': isMenuOpen }"
        v-if="!isAuthenticated || !isDashboard"
      >
        <li><router-link to="/about" @click="closeMenu">About</router-link></li>
        <li>
          <router-link to="/contact" @click="closeMenu">Contact</router-link>
        </li>
        <li>
          <router-link to="/shop" @click="closeMenu">Pre-Order</router-link>
        </li>
      </ul>

      <div class="navbar__actions">
        <!-- Show the user's first name and Logout button if authenticated -->
        <template v-if="isAuthenticated">
          <span class="navbar__user">Hi, {{ userFirstName }}</span>
          <button @click="logout">Logout</button>
        </template>

        <!-- Show Login button if not authenticated -->
        <template v-else>
          <router-link to="/login" @click="closeMenu">
            <button>Login</button>
          </router-link>
        </template>

        <!-- Cart icon (always visible) -->
        <router-link to="/cart" @click="closeMenu" class="navbar__cart">
          <img
            src="https://res.cloudinary.com/kwishi/image/upload/v1725263913/shopping-bag-icon_h04zp7.webp"
            alt="Cart"
          />
          <span v-if="cartItemCount > 0" class="cart-badge">{{
            cartItemCount
          }}</span>
        </router-link>
      </div>
    </nav>
  </header>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";

const isMenuOpen = ref(false);
function toggleMenu() {
  isMenuOpen.value = !isMenuOpen.value;
}

function closeMenu() {
  isMenuOpen.value = false;
}

const store = useStore();
const route = useRoute();

// Access Vuex state and getters
const cartItemCount = computed(() => store.getters.cartItemCount);
const isAuthenticated = computed(() => store.getters.isAuthenticated);
const userFirstName = computed(() => store.getters.userFirstName);

// Check if the user is on the dashboard route
const isDashboard = computed(() => route.path.includes("/dashboard"));

// Fetch the user data after the component mounts
onMounted(() => {
  if (localStorage.getItem("token")) {
    store.dispatch("fetchUser");
  }
});

// Handle logout
function logout() {
  store.dispatch("logout");
  closeMenu(); // Close the menu after logging out
}
</script>
