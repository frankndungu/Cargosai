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
import ProductCard from "@/components/layout/ProductCard.vue";
const products = [
  {
    id: 1,
    name: "Brass Bracelet",
    description:
      "Perfect for any occasion, it showcases intricate designs and timeless beauty",
    price: "$15.99",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1722319334/hero-jewelry_xdtiga.jpg",
  },
  {
    id: 2,
    name: "Maasai Choker",
    description: "Authentic Maasai design with vibrant, intricate patterns",
    price: "$17.99",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1722319334/hero-jewelry_xdtiga.jpg",
  },

  {
    id: 3,
    name: "Rings",
    description: "Elegant design with a timeless appeal",
    price: "$12.99",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1722319334/hero-jewelry_xdtiga.jpg",
  },

  {
    id: 4,
    name: "Maasai Sandals (W)",
    description: "Stylish and comfortable footwear with cultural flair",
    price: "$19.99",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1722319334/hero-jewelry_xdtiga.jpg",
  },
  {
    id: 5,
    name: "Hoop Earrings",
    description: "Bold and stylish, perfect for any occasion",
    price: "$13.99",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1722319334/hero-jewelry_xdtiga.jpg",
  },

  {
    id: 6,
    name: "Ankle Bracelets",
    description: " Charming and unique, adding a touch of elegance",
    price: "$15.99",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1722319334/hero-jewelry_xdtiga.jpg",
  },
];
</script>
