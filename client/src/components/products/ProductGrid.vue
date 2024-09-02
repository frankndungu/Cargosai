<template>
  <section>
    <PreorderBanner />
    <div class="product-grid" id="product-grid">
      <ProductCard
        v-for="product in products"
        :key="product.id"
        :product="product"
      />
    </div>
  </section>
</template>

<script setup>
import ProductCard from "../products/ProductCard.vue";
import PreorderBanner from "../ui/PreorderBanner.vue";
const products = [
  {
    id: 1,
    name: "Maasai Necklace",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1725290282/IMG_1841_pxxkgj.jpg",
    price: "$29.99",
    discount: "Cultured Brass",
    rating: 4.9,
    reviews: 0,
  },
];
</script>
