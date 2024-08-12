<template>
  <section class="featured-products">
    <h2 class="featured-heading">Pre-Order Now!</h2>
    <p class="featured-text">
      We haven't launched yet, but we are accepting pre-orders
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
    name: "Maasai Beaded Neckpieces",
    description: "Perfect for any occasion (Set of 5)",
    price: "$95.95",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1723452565/6D70881A-9F58-4819-B478-61ACD4CA710E_ib7vty.jpg",
  },

  {
    id: 2,
    name: "Brass Rings",
    description: "Versatile adjustable brass rings (Set of 5)",
    price: "$65.95",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1723451902/62180B51-9E28-438B-9C93-5BD7227193CC_wnlwco.jpg",
  },

  {
    id: 3,
    name: "Wall Art",
    description: "Contemporary wall canvases (Set of 3)",
    price: "$255.00",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1723447821/IMG_1874_o6xagp.jpg",
  },

  {
    id: 4,
    name: "Beaded Dining Table Mats",
    description: "Handcrafted table mats (Set of 5)",
    price: "$175.00",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1723442262/IMG_1875_l717yl.jpg",
  },
  {
    id: 5,
    name: "Chic Beaded Wall Mirror",
    description: "Adding a touch of elegance",
    price: "$65.00",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1723444931/IMG_1878_ztwfgq.jpg",
  },
  {
    id: 6,
    name: "Bags",
    description: "Stylish Big Five Africa bag",
    price: "$55.00",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1723441934/IMG_1887_cndofb.jpg",
  },
];
</script>
