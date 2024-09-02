<script setup>
import { ref } from "vue";

const isMenuOpen = ref(false);
function toggleMenu() {
  isMenuOpen.value = !isMenuOpen.value;
}

function closeMenu() {
  isMenuOpen.value = false;
}
</script>

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
        <li><router-link to="/blog" @click="closeMenu">Blog</router-link></li>
        <li><router-link to="/shop" @click="closeMenu">Shop</router-link></li>
      </ul>
      <div class="navbar__actions">
        <router-link to="/contact" @click="closeMenu">
          <button>Contact</button>
        </router-link>
        <router-link to="/cart" @click="closeMenu" class="navbar__cart">
          <img
            src="https://res.cloudinary.com/kwishi/image/upload/v1725263913/shopping-bag-icon_h04zp7.webp"
            alt="Cart"
          />
        </router-link>
      </div>
    </nav>
  </header>
</template>
