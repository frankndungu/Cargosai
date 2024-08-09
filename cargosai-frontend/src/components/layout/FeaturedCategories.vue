<template>
  <section class="featured-categories">
    <div class="header-categories">
      <h2 class="title">Featured Categories</h2>
      <router-link to="/shop" class="see-all">See all categories</router-link>
    </div>
    <div class="categories-grid">
      <div
        class="category-card"
        v-for="category in categories"
        :key="category.id"
      >
        <img
          :src="category.image"
          alt="category image"
          class="category-image"
        />
        <div class="discount">
          <span class="discount-icon">★</span> Shop now
        </div>
        <div class="category-description">{{ category.description }}</div>
        <router-link to="/shop" class="see-more"
          >See more {{ category.name }} deals →</router-link
        >
      </div>
    </div>
  </section>
</template>

<script setup>
import router from "../../../router";

const categories = [
  {
    id: 1,
    name: "Jewelry",
    description:
      "Explore unique handmade brass earrings, necklaces and bracelets.",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1723194319/A-display-of-african-jewelry-earrings_-rings_-stylish-hoops-_1__ervi29.webp",
  },
  {
    id: 2,
    name: "Wall Decor",
    description:
      "Decorate your space with authentic Maasai art, wall baskets, plates and vases.",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1723189032/o_1h1sgnc2fba911bc14en1eskt4j1r_f78rer.webp",
  },
  {
    id: 3,
    name: "Sandals",
    description:
      "Shop comfortable and stylish Maasai beaded necklaces, chokers and anklets",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1723193607/beads-_1__cwo5lb.webp",
  },
];
</script>
