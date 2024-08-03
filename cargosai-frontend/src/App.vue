<script setup>
import Navbar from "./components/common/Navbar.vue";
import Footer from "./components/common/Footer.vue";
</script>

<template>
  <div>
    <Navbar />
    <RouterView />
    <Footer />
  </div>
</template>
