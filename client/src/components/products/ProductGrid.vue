<template>
  <section>
    <PreorderBanner />
    <div class="product-grid" id="product-grid">
      <ProductCard
        v-for="product in products"
        :key="product._id"
        :product="product"
      />
    </div>
  </section>
</template>

<script setup>
import ProductCard from "../products/ProductCard.vue";
import PreorderBanner from "../ui/PreorderBanner.vue";
import { ref, onMounted } from "vue";
import { createClient } from "@sanity/client";

const sanityClient = createClient({
  projectId: import.meta.env.VITE_SANITY_PROJECT_ID,
  dataset: import.meta.env.VITE_SANITY_DATASET,
  useCdn: true,
  apiVersion: "2023-08-22",
});

const products = ref([]);
const loading = ref(true);

const fetchProducts = async () => {
  const query = `*[_type == "products"]{
    _id,
    name,
    "imageUrl": image.asset->url,
    price,
    vendor,
    rating,
    reviews
  }`;

  try {
    const result = await sanityClient.fetch(query);
    products.value = result;
  } catch (error) {
    console.error("Error fetching products:", error);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchProducts();
});
</script>
