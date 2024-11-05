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

      <!-- Toggle Menu for guests and users only -->
      <div
        v-if="!isAdmin && !isDashboard"
        class="navbar__toggle"
        @click="toggleMenu"
      >
        <div :class="{ 'navbar__toggle-bar': true, active: isMenuOpen }"></div>
        <div :class="{ 'navbar__toggle-bar': true, active: isMenuOpen }"></div>
        <div :class="{ 'navbar__toggle-bar': true, active: isMenuOpen }"></div>
      </div>

      <!-- Navbar Links for guests and users only -->
      <ul
        class="navbar__links"
        :class="{ 'navbar__links--open': isMenuOpen }"
        v-if="!isAdmin && !isDashboard"
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
          <template v-if="isAdmin">
            <!-- Admin User Icon and Logout Button -->
            <router-link to="/admin/dashboard" @click="closeMenu">
              <img
                src="https://res.cloudinary.com/kwishi/image/upload/v1729077994/user_1_jcoth0.svg"
                alt="User Icon"
                class="navbar__user-icon"
              />
            </router-link>
            <button @click="logout" class="navbar__logout-btn">Logout</button>
          </template>
          <template v-else>
            <router-link to="/dashboard" @click="closeMenu">
              <img
                src="https://res.cloudinary.com/kwishi/image/upload/v1729077994/user_1_jcoth0.svg"
                alt="User Icon"
                class="navbar__user-icon"
              />
            </router-link>
          </template>
        </template>
        <template v-else>
          <router-link to="/login" @click="closeMenu">
            <button>Login</button>
          </router-link>
        </template>

        <!-- Cart link only for non-admins -->
        <router-link
          v-if="!isAdmin"
          to="/cart"
          @click="closeMenu"
          class="navbar__cart"
        >
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
const isAuthenticated = computed(() => store.getters.isAuthenticated);
const isAdmin = computed(() => store.getters.userRole === "admin"); // Check if user is admin
const cartItemCount = computed(() => store.getters.cartItemCount);

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};

const closeMenu = () => {
  isMenuOpen.value = false;
};

// Admin logout function
const logout = () => {
  store.dispatch("logout");
};
</script>
