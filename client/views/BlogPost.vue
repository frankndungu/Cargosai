<template>
  <main class="main">
    <div v-if="post" class="blog-post">
      <h1 class="blog-post-title">{{ post.title }}</h1>
      <div class="image-container">
        <img
          v-if="post.mainImage"
          :src="imageUrl(post.mainImage).url()"
          alt="Post Image"
          class="blog-post-image"
        />
      </div>
      <PortableText :value="post.body" />
      <p class="blog-author-info">Written by: {{ post.author }}</p>
      <p class="blog-publish-date">{{ formatDate(post.publishedAt) }}</p>
    </div>
    <div v-else class="loading-dots">
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="dot"></div>
    </div>
  </main>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import { createClient } from "@sanity/client";
import { PortableText } from "@portabletext/vue";
import imageUrlBuilder from "@sanity/image-url";

const sanityClient = createClient({
  projectId: import.meta.env.VITE_SANITY_PROJECT_ID,
  dataset: import.meta.env.VITE_SANITY_DATASET,
  useCdn: true,
  apiVersion: "2023-08-22",
});

const builder = imageUrlBuilder(sanityClient);
const imageUrl = (source) => builder.image(source);

const route = useRoute();
const post = ref(null);

const fetchPost = async (slug) => {
  const query = `*[_type == "post" && slug.current == $slug][0]{
      _id,
      title,
      body,
      publishedAt,
      "author": author->name,
      "mainImage": mainImage{
        asset->{
          _id,
          url
        }
      }
    }`;
  post.value = await sanityClient.fetch(query, { slug });
};

const formatDate = (dateStr) => {
  const date = new Date(dateStr);
  return date.toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};

onMounted(() => {
  const postSlug = route.params.slug;
  fetchPost(postSlug);
});
</script>
