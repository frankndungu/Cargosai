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
        v-if="showNavigation"
        class="navbar__toggle"
        @click="toggleMenu"
        aria-label="Toggle navigation menu"
      >
        <div :class="{ 'navbar__toggle-bar': true, active: isMenuOpen }"></div>
        <div :class="{ 'navbar__toggle-bar': true, active: isMenuOpen }"></div>
        <div :class="{ 'navbar__toggle-bar': true, active: isMenuOpen }"></div>
      </div>

      <!-- Navbar Links for guests and users only -->
      <ul
        class="navbar__links"
        :class="{ 'navbar__links--open': isMenuOpen }"
        v-if="showNavigation"
        role="navigation"
      >
        <li><router-link to="/shop" @click="closeMenu">Shop</router-link></li>
        <li><router-link to="/about" @click="closeMenu">About</router-link></li>
        <li>
          <router-link to="/contact" @click="closeMenu">Contact</router-link>
        </li>
      </ul>

      <div class="navbar__actions">
        <template v-if="isAuthenticated">
          <template v-if="isAdmin">
            <!-- Admin Avatar and Logout Button -->
            <div class="navbar__admin-controls">
              <router-link
                to="/admin/dashboard"
                @click="closeMenu"
                class="navbar__avatar-link"
              >
                <img
                  :src="adminAvatarUrl"
                  alt="Admin Avatar"
                  class="navbar__user-icon"
                />
              </router-link>
              <button
                @click="logout"
                class="navbar__logout-btn"
                aria-label="Logout"
              >
                Logout
              </button>
            </div>
          </template>
          <template v-else>
            <!-- User Avatar -->
            <router-link
              v-if="showUserControls"
              to="/dashboard"
              @click="closeMenu"
              class="navbar__avatar-link"
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
            <button class="navbar__login-btn">Login</button>
          </router-link>
        </template>

        <!-- Cart link only for non-admins -->
        <router-link
          v-if="showCart"
          to="/cart"
          @click="closeMenu"
          class="navbar__cart"
          aria-label="Shopping cart"
        >
          <img
            src="https://res.cloudinary.com/kwishi/image/upload/v1725263913/shopping-bag-icon_h04zp7.webp"
            alt="Cart"
          />
          <span v-if="cartItemCount > 0" class="cart-badge" role="status">{{
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

// Computed properties for conditional rendering
const showNavigation = computed(
  () =>
    !isAdmin.value &&
    !isDashboard.value &&
    !isCheckout.value &&
    !isCart.value &&
    !isOrderSuccess.value
);

const showUserControls = computed(
  () => !isCart.value && !isCheckout.value && !isOrderSuccess.value
);

const showCart = computed(
  () =>
    !isAdmin.value &&
    !isCheckout.value &&
    !isCart.value &&
    !isOrderSuccess.value
);

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
  padding: 1.25rem 3rem;
  border-radius: 8px;
  border-bottom: 1px solid var(--dark-color-border);
  top: 0;
  z-index: 1000;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.navbar__title {
  font-size: var(--font-size-large);
  font-weight: var(--font-bold);
}

.navbar__link-title {
  font-size: var(--font-size-large);
  display: flex;
  align-items: center;
  transition: transform 0.2s ease;
}

.navbar__link-title:hover {
  transform: scale(1.02);
}

.navbar__logo-text {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.logo-main {
  font-family: "League Spartan", sans-serif;
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--dark-tint);
  letter-spacing: 0.5px;
}

.logo-sub {
  font-family: "Quicksand", sans-serif;
  font-size: 0.9rem;
  font-weight: 500;
  letter-spacing: 5px;
  color: var(--dark-color);
  margin-top: -4px;
  opacity: 0.9;
}

/* Navbar Links */
.navbar__links {
  display: flex;
  list-style-type: none;
  margin: 0;
  padding: 0;
  gap: 2rem;
}

.navbar__links li {
  position: relative;
}

.navbar__links a {
  text-decoration: none;
  color: var(--dark-color);
  font-size: var(--h3-font-size);
  padding: 0.5rem 0;
  transition: color 0.2s ease;
  position: relative;
}

.navbar__links a::after {
  content: "";
  position: absolute;
  bottom: -2px;
  left: 0;
  width: 0;
  height: 2px;
  background-color: var(--dark-tint);
  transition: width 0.3s ease;
}

.navbar__links a:hover::after {
  width: 100%;
}

/* Actions (Login, Logout, Cart) */
.navbar__actions {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.navbar__admin-controls {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.navbar__login-btn,
.navbar__logout-btn {
  padding: 0.6rem 1.2rem;
  background-color: var(--dark-tint);
  color: var(--background-color);
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.2s ease;
}

.navbar__login-btn:hover,
.navbar__logout-btn:hover {
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.navbar__avatar-link {
  display: flex;
  align-items: center;
  transition: transform 0.2s ease;
}

.navbar__avatar-link:hover {
  transform: scale(1.05);
}

.navbar__user-icon {
  width: 35px;
  height: 35px;
  border-radius: 50%;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.navbar__cart {
  position: relative;
  display: flex;
  align-items: center;
  transition: transform 0.2s ease;
}

.navbar__cart:hover {
  transform: scale(1.05);
}

.navbar__cart img {
  width: 24px;
  height: 24px;
}

.cart-badge {
  position: absolute;
  top: -8px;
  right: -8px;
  background: var(--error-message);
  color: var(--background-color);
  border-radius: 50%;
  padding: 0.25rem 0.5rem;
  font-size: 0.75rem;
  font-weight: var(--font-bold);
  min-width: 20px;
  text-align: center;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Mobile Menu Styles */
.navbar__toggle {
  display: none;
  flex-direction: column;
  justify-content: space-around;
  height: 24px;
  width: 30px;
  cursor: pointer;
  padding: 0;
  background: transparent;
  border: none;
}

.navbar__toggle-bar {
  width: 100%;
  height: 2px;
  background-color: var(--dark-tint);
  transition: all 0.3s ease;
  border-radius: 2px;
}

/* Media Queries */
@media (max-width: 768px) {
  .navbar {
    padding: 1rem;
  }

  .navbar__toggle {
    display: flex;
  }

  .navbar__links {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    background: var(--secondary-color);
    padding: 1rem 0;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .navbar__links--open {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
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

  .navbar__links li {
    width: 100%;
    margin: 10px 0;
    text-align: center;
  }

  .navbar__toggle-bar.active:nth-child(1) {
    transform: translateY(8px) rotate(45deg);
  }

  .navbar__toggle-bar.active:nth-child(2) {
    opacity: 0;
  }

  .navbar__toggle-bar.active:nth-child(3) {
    transform: translateY(-8px) rotate(-45deg);
  }

  .navbar__actions {
    gap: 1rem;
  }

  .navbar__login-btn,
  .navbar__logout-btn {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
  }

  .logo-main {
    font-size: 1.2rem;
  }

  .logo-sub {
    font-size: 0.8rem;
    letter-spacing: 3px;
  }
}
</style>
