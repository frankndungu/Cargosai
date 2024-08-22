<template>
  <div class="blog-page">
    <!-- Left Section -->
    <div class="left-section">
      <div class="card blog-posts-card">
        <h2>Blog Posts</h2>
        <p class="blog-intro">
          Discover the stories behind our handcrafted Maasai products and the
          culture that inspires them.
        </p>
      </div>

      <div class="card popular-products-card">
        <div class="popular-products-grid">
          <button
            v-for="product in products"
            :key="product.name"
            class="popular-product-button"
          >
            {{ product.name }}
          </button>
        </div>
        <router-link to="/shop" class="view-all-link"
          >View All Products</router-link
        >
      </div>
    </div>

    <!-- Right Section - BlogList -->
    <div class="right-section">
      <div
        v-for="post in posts"
        :key="post.id"
        class="post-card"
        @click="goToPost(post.id)"
      >
        <div class="post-content">
          <div class="post-tag-container">
            <span class="post-tag">Article</span>
          </div>
          <h3 class="post-title">{{ post.title }}</h3>
          <p class="post-excerpt">{{ post.excerpt }}</p>
          <div class="author-info">
            <img :src="post.authorImage" alt="Author" class="author-image" />
            <div>
              <p class="author">{{ post.author }}</p>
              <p class="post-date">
                {{ post.date }} • {{ post.readTime }} read
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

const posts = ref([
  {
    id: 1,
    title: "Our first office",
    excerpt:
      "Over the past year, Volosoft has undergone many changes! After months of preparation and some hard work, we moved to our new office.",
    author: "Jese Leos",
    authorImage:
      "https://res.cloudinary.com/kwishi/image/upload/v1724314534/Frank_c7t15k.jpg",
    date: "Aug 15, 2021",
    readTime: "16 min",
  },
  {
    id: 2,
    title: "We partnered up with Google",
    excerpt:
      "Over the past year, Volosoft has undergone many changes! After months of preparation and some hard work, we moved to our new office.",
    author: "Roberta Casas",
    authorImage:
      "https://res.cloudinary.com/kwishi/image/upload/v1724314534/Frank_c7t15k.jpg",
    date: "Aug 15, 2021",
    readTime: "16 min",
  },
  {
    id: 3,
    title: "Our first project with React",
    excerpt:
      "Over the past year, Volosoft has undergone many changes! After months of preparation and some hard work, we moved to our new office.",
    author: "Sofia McGuire",
    authorImage:
      "https://res.cloudinary.com/kwishi/image/upload/v1724314534/Frank_c7t15k.jpg",
    date: "Aug 15, 2021",
    readTime: "16 min",
  },
]);

const products = ref([
  { name: "Beaded Earrings" },
  { name: "Maasai Sandals" },
  { name: "Hand-carved Chess Board" },
  { name: "Decorative Mirrors" },
  { name: "Traditional Dresses" },
]);

const goToPost = (id) => {
  console.log(`Navigating to post with id: ${id}`);
  // e.g., this.$router.push(`/blog/${id}`)
};
</script>
