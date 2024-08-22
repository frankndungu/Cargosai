<template>
  <main class="main">
    <div class="blog-page">
      <BlogPost />
      <BlogList />
    </div>
  </main>
</template>
<script setup>
import BlogPost from "@/components/layout/BlogPost.vue";
import BlogList from "@/components/layout/BlogList.vue";
</script>
