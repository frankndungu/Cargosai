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
        v-if="
          !isAdmin && !isDashboard && !isCheckout && !isCart && !isOrderSuccess
        "
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
        v-if="
          !isAdmin && !isDashboard && !isCheckout && !isCart && !isOrderSuccess
        "
      >
        <li><router-link to="/about" @click="closeMenu">About</router-link></li>
        <li>
          <router-link to="/contact" @click="closeMenu">Contact</router-link>
        </li>
        <li>
          <router-link to="/shop" @click="closeMenu">Shop</router-link>
        </li>
      </ul>

      <div class="navbar__actions">
        <template v-if="isAuthenticated">
          <template v-if="isAdmin">
            <!-- Admin Avatar and Logout Button -->
            <router-link to="/admin/dashboard" @click="closeMenu">
              <img
                :src="adminAvatarUrl"
                alt="Admin Avatar"
                class="navbar__user-icon"
              />
            </router-link>
            <button @click="logout" class="navbar__logout-btn">Logout</button>
          </template>
          <template v-else>
            <!-- User Avatar -->
            <router-link
              v-if="!isCart && !isCheckout && !isOrderSuccess"
              to="/dashboard"
              @click="closeMenu"
            >
              <img
                :src="userAvatarUrl"
                alt="User Avatar"
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
          v-if="!isAdmin && !isCheckout && !isCart && !isOrderSuccess"
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
const isCheckout = computed(() => route.path === "/checkout");
const isCart = computed(() => route.path === "/cart");
const isOrderSuccess = computed(() => route.path === "/order/success"); // Add this check
const isAuthenticated = computed(() => store.getters.isAuthenticated);
const isAdmin = computed(() => store.getters.userRole === "admin");
const cartItemCount = computed(() => store.getters.cartItemCount);

// Dicebear avatar URLs
const adminAvatarUrl =
  "https://api.dicebear.com/9.x/adventurer-neutral/svg?radius=50";
const userAvatarUrl = computed(() => {
  const initials = store.getters.userInitials || "User";
  return `https://api.dicebear.com/9.x/initials/svg?seed=${initials}&radius=50&backgroundColor=000000`;
});

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};

const closeMenu = () => {
  isMenuOpen.value = false;
};

// Logout function for admin
const logout = () => {
  store.dispatch("logout");
};
</script>
