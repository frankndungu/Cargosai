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
        <router-link to="/" class="navbar__link-title" @click="closeMenu"
          >Cargosai</router-link
        >
      </div>
      <div class="navbar__toggle" @click="toggleMenu">
        <div :class="{ 'navbar__toggle-bar': true, active: isMenuOpen }"></div>
        <div :class="{ 'navbar__toggle-bar': true, active: isMenuOpen }"></div>
        <div :class="{ 'navbar__toggle-bar': true, active: isMenuOpen }"></div>
      </div>
      <ul class="navbar__links" :class="{ 'navbar__links--open': isMenuOpen }">
        <li><router-link to="/about" @click="closeMenu">About</router-link></li>
        <li>
          <router-link to="/services" @click="closeMenu">Services</router-link>
        </li>
        <li><router-link to="/blog" @click="closeMenu">Blog</router-link></li>
        <li><router-link to="/shop" @click="closeMenu">Shop</router-link></li>
      </ul>
      <div class="navbar__contact">
        <router-link to="/contact" @click="closeMenu">
          <button>Contact</button>
        </router-link>
      </div>
    </nav>
  </header>
</template>
