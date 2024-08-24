<template>
  <!-- Right Section - BlogList -->
  <div class="right-section">
    <div
      v-for="post in posts"
      :key="post._id"
      @click="goToPost(post._id)"
      class="post-card"
    >
      <div class="post-content">
        <div class="post-tag-container">
          <span class="post-tag">Article</span>
        </div>
        <h3 class="post-title">{{ post.title }}</h3>
        <p class="post-excerpt">{{ post.summary }}</p>
        <div class="author-info">
          <img
            :src="post.authorImage?.asset?.url"
            alt="Author"
            class="author-image"
          />
          <div>
            <p class="author">{{ post.author }}</p>
            <p class="post-date">
              {{ formatDate(post.publishedAt) }} • {{ post.readTime }} min read
            </p>
          </div>
        </div>
        <div class="blog-route">
          <router-link class="read-more-link">Read More</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { createClient } from "@sanity/client";

const sanityClient = createClient({
  projectId: import.meta.env.VITE_SANITY_PROJECT_ID,
  dataset: import.meta.env.VITE_SANITY_DATASET,
  useCdn: true,
  apiVersion: "2023-08-22",
});

const posts = ref([]);

const fetchPosts = async () => {
  const query = `*[_type == "post"]{
      _id,
      title,
      summary,
      publishedAt,
      readTime,
      "author": author->name,
      "authorImage": author->image{
        asset->{
          _id,
          url
        }
      }
    }`;
  posts.value = await sanityClient.fetch(query);
};

function formatDate(dateStr) {
  const date = new Date(dateStr);
  return date.toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
}

onMounted(() => {
  fetchPosts();
});

const goToPost = (_id) => {};
</script>
