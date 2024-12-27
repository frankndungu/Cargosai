<template>
  <header class="header" v-if="!isResetPassword">
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

// Route-specific checks
const isDashboard = computed(() => route.path.startsWith("/dashboard"));
const isCheckout = computed(() => route.path === "/checkout");
const isCart = computed(() => route.path === "/cart");
const isOrderSuccess = computed(() => route.path === "/order/success");
const isResetPassword = computed(() => route.path === "/reset-password");

// User and authentication
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

// Navbar menu controls
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

<style scoped>
/* General Navbar Styles */
.navbar {
  background: var(--secondary-color);
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 50px;
  border-radius: 5px;
  border-bottom: 1px solid var(--dark-color-border);
}

.navbar__title {
  font-size: var(--font-size-large);
  font-weight: var(--font-bold);
}

.navbar__link-title {
  font-size: var(--font-size-large);
  display: flex;
  align-items: center;
}

.navbar__logo-text {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.logo-main {
  font-family: "League Spartan", sans-serif;
  font-size: 24px;
  font-weight: 700;
  color: var(--dark-tint);
}

.logo-sub {
  font-family: "Quicksand", sans-serif;
  font-size: 15px;
  font-weight: 500;
  letter-spacing: 5px;
  color: var(--dark-color);
  margin-top: -4px;
}

/* Navbar Toggle Menu (for Mobile) */
.navbar__toggle {
  display: none;
  flex-direction: column;
  justify-content: space-around;
  height: 24px;
  width: 30px;
  cursor: pointer;
}

.navbar__toggle-bar {
  width: 100%;
  height: 2px;
  background-color: var(--dark-tint);
  transition: all 0.3s;
}

.navbar__toggle-bar.active:nth-child(1) {
  transform: translateY(10px) rotate(45deg);
}

.navbar__toggle-bar.active:nth-child(2) {
  opacity: 0;
}

.navbar__toggle-bar.active:nth-child(3) {
  transform: translateY(-10px) rotate(-45deg);
}

/* Navbar Links */
.navbar__links {
  display: flex;
  list-style-type: none;
  margin: 0;
  padding: 0;
}

.navbar__links li {
  margin: 0 15px;
}

.navbar__links a {
  text-decoration: none;
  color: var(--dark-color);
  font-size: var(--h3-font-size);
  padding: 10px 0;
}

/* Toggle Menu for Mobile */
.navbar__links--open {
  display: flex;
  flex-direction: column;
  align-items: center;
  position: absolute;
  top: 60px;
  left: 0;
  width: 100%;
  background: var(--secondary-color);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  transition: max-height 0.3s ease-in;
  z-index: 1000;
  padding: 20px 0;
}

.navbar__links--open li {
  width: 100%;
  text-align: center;
  margin: 10px 0;
}

/* Actions (Login, Logout, Cart) */
.navbar__actions {
  display: flex;
  gap: 20px;
}

.navbar__actions button {
  padding: 10px 20px;
  background-color: var(--dark-tint);
  color: var(--background-color);
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: var(--background-color) 0.2s ease, transform 0.2s ease,
    box-shadow 0.2s ease;
}

.navbar__logout-btn {
  padding: 10px 20px;
  background-color: var(--dark-tint);
  color: var(--background-color);
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: var(--background-color) 0.2s ease, transform 0.2s ease,
    box-shadow 0.2s ease;
}

.navbar__cart-btn:hover {
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.navbar__cart-btn:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}

.navbar__actions button:hover {
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.navbar__actions button:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}
.navbar__user {
  margin-top: 5px;
  font-size: 20px;
  font-weight: 700;
}

.navbar__cart img {
  width: 24px;
  height: 24px;
  margin-top: 5px;
}

.cart-badge {
  position: absolute;
  top: 12px;
  right: 40px;
  background: var(--error-message);
  color: var(--background-color);
  border-radius: 50%;
  padding: 4px 8px;
  font-size: 12px;
  font-weight: var(--font-bold);
}

/* User Icon */
.navbar__user-icon {
  width: 35px;
  height: 35px;
  cursor: pointer;
}

/* Media Queries */
@media (max-width: 768px) {
  .navbar {
    padding: 10px 20px;
  }
  .navbar__link-title {
    font-size: var(--font-size-large);
  }
  .navbar__toggle {
    display: flex;
  }

  .navbar__links {
    display: none;
    transition: max-height 0.3s ease-in;
  }

  .navbar__links.navbar__links--open {
    display: flex;
    transition: max-height 0.3s ease-out;
    overflow: hidden;
  }

  .navbar__actions button {
    display: none;
  }
  .navbar__user {
    font-weight: 700;
    font-size: 17px;
  }
  .cart-badge {
    top: 12px;
    right: 10px;
  }
}
</style>
