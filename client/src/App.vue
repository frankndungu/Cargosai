<script setup>
import Navbar from "./components/common/Navbar.vue";
import Footer from "./components/common/Footer.vue";
</script>

<template>
  <div class="sticky">
    <Navbar />
  </div>
  <RouterView />
  <Footer />
</template>
