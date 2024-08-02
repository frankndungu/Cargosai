<template>
  <section class="featured-products">
    <h2 class="featured-heading">Pre-Order Now!</h2>
    <p class="featured-text">
      We haven't launched yet, but we are accepting pre-orders.
    </p>
    <div class="product-grid">
      <ProductCard
        v-for="product in products"
        :key="product.id"
        :product="product"
      />
    </div>
  </section>
</template>

<script setup>
import ProductCard from "@/components/ProductCard.vue";
const products = [
  {
    id: 1,
    name: "Maasai Beaded Necklace",
    description: "Handcrafted necklace with traditional Maasai beads.",
    price: "$25",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1722319334/hero-jewelry_xdtiga.jpg",
  },
  {
    id: 2,
    name: "Maasai Beaded Necklace",
    description: "Handcrafted necklace with traditional Maasai beads.",
    price: "$25",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1722319334/hero-jewelry_xdtiga.jpg",
  },

  {
    id: 3,
    name: "Maasai Beaded Necklace",
    description: "Handcrafted necklace with traditional Maasai beads.",
    price: "$25",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1722319334/hero-jewelry_xdtiga.jpg",
  },

  {
    id: 4,
    name: "Maasai Beaded Necklace",
    description: "Handcrafted necklace with traditional Maasai beads.",
    price: "$25",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1722319334/hero-jewelry_xdtiga.jpg",
  },
  {
    id: 5,
    name: "Maasai Beaded Necklace",
    description: "Handcrafted necklace with traditional Maasai beads.",
    price: "$25",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1722319334/hero-jewelry_xdtiga.jpg",
  },

  {
    id: 6,
    name: "Maasai Beaded Necklace",
    description: "Handcrafted necklace with traditional Maasai beads.",
    price: "$25",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1722319334/hero-jewelry_xdtiga.jpg",
  },
];
</script>
