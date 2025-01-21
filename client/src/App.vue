<script setup>
import { useMeta } from "vue-meta";
import Navbar from "./components/common/Navbar.vue";
import Footer from "./components/common/Footer.vue";

useMeta({
  title: "Maasai Market Online",
  meta: [
    {
      name: "description",
      content: "Explore authentic Maasai and African products.",
    },
    { name: "viewport", content: "width=device-width, initial-scale=1" },
  ],
});
</script>

<template>
  <section class="page-wrapper">
    <Navbar />
    <RouterView />
    <Footer />
  </section>
</template>
