<script setup>
import Navbar from "./components/common/Navbar.vue";
import Hero from "./components/common/Hero.vue";
</script>

<template>
  <header>
    <Navbar />
  </header>
  <main>
    <Hero />
  </main>
  <footer>
    <!-- Footer content goes here -->
  </footer>
  <RouterView />
</template>
