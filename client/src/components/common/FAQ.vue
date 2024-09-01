<template>
  <section class="faq">
    <h2>Frequently Asked Questions</h2>
    <div v-if="loading" class="loading">
      <div class="spinner"></div>
    </div>
    <div v-if="error" class="error">{{ error }}</div>
    <div v-for="(faq, index) in faqs" :key="faq._id" class="faq-item">
      <button
        @click="toggle(index)"
        class="faq-question"
        :aria-expanded="activeIndex === index ? 'true' : 'false'"
      >
        {{ index + 1 }}. {{ faq.question }}
        <span :class="{ active: activeIndex === index }"
          ><i class="fa-solid fa-chevron-down"></i
        ></span>
      </button>
      <div v-if="activeIndex === index" class="faq-answer">
        <p>{{ faq.answer }}</p>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { createClient } from "@sanity/client";

// Create the Sanity client
const sanityClient = createClient({
  projectId: import.meta.env.VITE_SANITY_PROJECT_ID,
  dataset: import.meta.env.VITE_SANITY_DATASET,
  useCdn: true,
  apiVersion: "2023-08-22",
});

// Reactive references
const faqs = ref([]);
const activeIndex = ref(null);
const loading = ref(true);
const error = ref(null);

// Fetch FAQs from Sanity
const fetchFAQs = async () => {
  try {
    const query = `*[_type == "faq"] | order(_createdAt asc) {
      _id,
      question,
      answer
    }`;
    faqs.value = await sanityClient.fetch(query);
  } catch (err) {
    error.value = "Failed to load FAQs. Please try again later.";
    console.error(err);
  } finally {
    loading.value = false;
  }
};

// Toggle FAQ answer visibility
const toggle = (index) => {
  activeIndex.value = activeIndex.value === index ? null : index;
};

// Fetch data on component mount
onMounted(() => {
  fetchFAQs();
});
</script>
