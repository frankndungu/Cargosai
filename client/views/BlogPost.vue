<template>
  <main class="main-container">
    <article v-if="post" class="blog-article">
      <!-- Header Section -->
      <header class="article-header">
        <div class="hero-section">
          <div class="image-wrapper">
            <img
              v-if="post.mainImage"
              :src="imageUrl(post.mainImage).url()"
              :alt="post.title"
              class="main-image"
            />
            <div class="image-overlay"></div>
          </div>
          <h1 class="article-title">{{ post.title }}</h1>
          <time :datetime="post.publishedAt" class="publish-date">
            {{ formatDate(post.publishedAt) }}
          </time>
        </div>
      </header>

      <!-- Content Section -->
      <div class="article-content">
        <PortableText :value="post.body" />
      </div>

      <!-- Author Section -->
      <footer class="article-footer">
        <div class="author-card">
          <div class="author-image-wrapper">
            <img
              v-if="post.authorImage?.asset?.url"
              :src="post.authorImage.asset.url"
              :alt="post.authorName"
              class="author-image"
            />
          </div>
          <div class="author-info">
            <div class="author-bio">
              <PortableText :value="post.authorBio" />
            </div>
          </div>
        </div>
      </footer>
    </article>

    <!-- Loading State -->
    <div
      v-else
      class="loading-container"
      role="status"
      aria-label="Loading post"
    >
      <div class="loading-dots">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
      </div>
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
    "authorName": author->name,
    "authorBio": author->bio,
    "authorImage": author->image{
      asset->{
        _id,
        url
      }
    },
    "mainImage": mainImage{
      asset->{
        _id,
        url
      }
    }
  }`;

  try {
    post.value = await sanityClient.fetch(query, { slug });
  } catch (error) {
    console.error("Error fetching post:", error);
  }
};

const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleDateString("en-US", {
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

<style>
:root {
  --text-primary: #0f0f0f;
  --text-secondary: #28282b;
  --background-primary: #faf9f6;
  --background-secondary: #f9f9f9;
  --accent-color: #9e301b;
  --spacing-unit: 1rem;
  --border-radius: 12px;
  --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  --transition-duration: 0.3s;
}

/* Main Container */
.main-container {
  min-height: 100vh;
  background-color: var(--background-primary);
  padding: 40px 50px;
}

/* Article Styling */
.blog-article {
  background-color: var(--background-primary);
  border-radius: var(--border-radius);
  box-shadow: var(--box-shadow);
  overflow: hidden;
}

/* Hero Section */
.hero-section {
  position: relative;
  height: 60vh;
  max-height: 600px;
  min-height: 300px;
  overflow: hidden;
}

.image-wrapper {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.main-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transform: scale(
    1.02
  ); /* Slight scale to prevent white edges during transition */
  transition: transform var(--transition-duration) ease;
}

.image-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(
    to bottom,
    rgba(0, 0, 0, 0.2),
    rgba(0, 0, 0, 0.7)
  );
}

.article-title {
  position: absolute;
  bottom: calc(var(--spacing-unit) * 3);
  left: var(--spacing-unit);
  right: var(--spacing-unit);
  color: var(--background-primary);
  font-size: 2.5rem;
  margin: 0;
  padding: var(--spacing-unit);
  z-index: 2;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.publish-date {
  position: absolute;
  bottom: var(--spacing-unit);
  left: calc(var(--spacing-unit) * 2);
  color: rgba(255, 255, 255, 1.2);
  font-size: 0.9rem;
  font-weight: bolder;
  z-index: 2;
}

/* Content Section */
.article-content {
  padding: calc(var(--spacing-unit) * 3) calc(var(--spacing-unit) * 2);
  font-size: 1.1rem;
  line-height: 1.6;
  color: var(--text-primary);
}

.article-content img {
  width: 100%;
  height: auto;
  border-radius: var(--border-radius);
  margin: var(--spacing-unit) 0;
  box-shadow: var(--box-shadow);
}

/* Author Section */
.article-footer {
  border-top: 1px solid #ddd;
  padding: calc(var(--spacing-unit) * 2);
  background: var(--background-primary);
}

.author-card {
  display: flex;
  gap: calc(var(--spacing-unit) * 2);
  align-items: flex-start;
  padding: var(--spacing-unit);
  border-radius: var(--border-radius);
}

.author-image-wrapper {
  flex-shrink: 0;
  position: relative;
}

.author-image {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid var(--background-secondary);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
  margin-top: 10px;
}

.author-info {
  flex: 1;
}

.author-bio {
  color: var(--text-primary);
  font-size: 0.95rem;
  line-height: 1.6;
}

/* Responsive Design */
@media (max-width: 768px) {
  .hero-section {
    height: 40vh;
  }

  .main-container {
    padding: 20px 20px;
  }

  .article-title {
    font-size: 1.8rem;
    bottom: calc(var(--spacing-unit) * 2);
  }

  .article-content {
    padding: var(--spacing-unit);
  }

  .author-card {
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: var(--spacing-unit);
  }

  .author-image {
    width: 100px;
    height: 100px;
    margin-bottom: calc(var(--spacing-unit) / 2);
  }
}

/* Additional Typography Styles */
.article-content h2 {
  font-size: 1.8rem;
  color: var(--text-primary);
  margin: 2em 0 1em;
}

.article-content h3 {
  font-size: 1.4rem;
  color: var(--text-primary);
  margin: 1.5em 0 0.8em;
}

.article-content p {
  margin: 1.2em 0;
}

.article-content blockquote {
  margin: 2em 0;
  padding: 1em 2em;
  border-left: 4px solid var(--accent-color);
  background-color: var(--background-secondary);
  border-radius: 0 var(--border-radius) var(--border-radius) 0;
  font-style: italic;
  color: var(--text-secondary);
}

/* Loading Animation */
.loading-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
}

.loading-dots {
  display: flex;
  gap: 0.5rem;
  margin: 0 6px;
}

.dot {
  width: 12px;
  height: 12px;
  background-color: var(--accent-color);
  border-radius: 50%;
  animation: bounce 0.5s infinite alternate;
}

.dot:nth-child(2) {
  animation-delay: 0.15s;
}

.dot:nth-child(3) {
  animation-delay: 0.3s;
}

@keyframes bounce {
  from {
    transform: translateY(0);
  }
  to {
    transform: translateY(-10px);
  }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
  .main-image,
  .dot {
    animation: none;
    transition: none;
  }
}
</style>
