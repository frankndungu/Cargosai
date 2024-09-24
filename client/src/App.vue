<script setup>
import Navbar from "./components/common/Navbar.vue";
import Footer from "./components/common/Footer.vue";
</script>

<template>
  <section class="page-wrapper">
    <Navbar />
    <RouterView />
    <Footer />
  </section>
</template>
