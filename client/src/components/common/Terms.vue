<template>
  <div class="terms-of-service">
    <h1 class="terms-title">{{ terms.title }}</h1>
    <p class="release-date">
      Released on: {{ formatReleaseDate(terms.releaseDate) }}
    </p>
    <div class="content">
      <PortableText class="content-data" :value="terms.content" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { createClient } from "@sanity/client";
import { PortableText } from "@portabletext/vue";

// Initialize Sanity client
const sanityClient = createClient({
  projectId: import.meta.env.VITE_SANITY_PROJECT_ID,
  dataset: import.meta.env.VITE_SANITY_DATASET,
  useCdn: true,
  apiVersion: "2023-08-22",
});

// State
const terms = ref({});
const termsContent = ref("");

// Fetch terms of service from Sanity
const fetchTermsOfService = async () => {
  const query = `*[_type == "termsOfService"][0]{
    title,
    content,
    releaseDate
  }`;
  const result = await sanityClient.fetch(query);
  terms.value = result;
  termsContent.value = PortableText(result.content);
};

// Format the release date
const formatReleaseDate = (dateStr) => {
  const date = new Date(dateStr);
  return date.toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};

// Fetch on component mount
onMounted(() => {
  fetchTermsOfService();
});
</script>
