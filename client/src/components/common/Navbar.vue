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

      <div class="navbar__toggle" v-if="!isDashboard" @click="toggleMenu">
        <div :class="{ 'navbar__toggle-bar': true, active: isMenuOpen }"></div>
        <div :class="{ 'navbar__toggle-bar': true, active: isMenuOpen }"></div>
        <div :class="{ 'navbar__toggle-bar': true, active: isMenuOpen }"></div>
      </div>

      <ul
        class="navbar__links"
        :class="{ 'navbar__links--open': isMenuOpen }"
        v-if="!isDashboard"
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
        <template v-if="isAuthenticated">
          <template v-if="isDashboard">
            <span class="navbar__user">Hi, {{ userFirstName }}</span>
            <button @click="logout">Logout</button>
          </template>
          <template v-else>
            <router-link to="/dashboard" @click="closeMenu">
              <button>Go to Dashboard</button>
            </router-link>
          </template>
        </template>
        <template v-else>
          <router-link to="/login" @click="closeMenu">
            <button>Login</button>
          </router-link>
        </template>

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
import { ref, computed } from "vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";

const store = useStore();
const route = useRoute();
const isMenuOpen = ref(false);
const isDashboard = computed(() => route.path.startsWith("/dashboard"));
const cartItemCount = computed(() => store.getters.cartItemCount);
const isAuthenticated = computed(() => store.getters.isAuthenticated);
const userFirstName = computed(() => store.getters.userFirstName);

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};

const closeMenu = () => {
  isMenuOpen.value = false;
};

const logout = () => {
  store.dispatch("logout");
};
</script>
