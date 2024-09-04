<script setup>
import Navbar from "./components/common/Navbar.vue";
import Footer from "./components/common/Footer.vue";
</script>

<template>
  <section class="page-wrapper">
    <div class="sticky">
      <Navbar />
    </div>
    <RouterView />
    <Footer />
  </section>
</template>
