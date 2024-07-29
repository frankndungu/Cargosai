<script setup>
import { ref } from "vue";

const isMenuOpen = ref(false);
function toggleMenu() {
  isMenuOpen.value = !isMenuOpen.value;
}
</script>

<template>
  <nav class="navbar">
    <div class="navbar__title">
      <h1>Cargosai</h1>
    </div>
    <div class="navbar__toggle" @click="toggleMenu">
      <div :class="{ 'navbar__toggle-bar': true, active: isMenuOpen }"></div>
      <div :class="{ 'navbar__toggle-bar': true, active: isMenuOpen }"></div>
      <div :class="{ 'navbar__toggle-bar': true, active: isMenuOpen }"></div>
    </div>
    <ul class="navbar__links" :class="{ 'navbar__links--open': isMenuOpen }">
      <li><a href="#about">About</a></li>
      <li><a href="#services">Services</a></li>
      <li><a href="#blog">Blog</a></li>
    </ul>
    <div class="navbar__contact">
      <button>Contact</button>
    </div>
  </nav>
</template>
