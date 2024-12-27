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
          <span class="discount-icon">¤</span>Shop Authentic African Treasures
        </div>
        <div class="category-description">{{ category.description }}</div>
        <router-link to="/shop" class="see-more"
          >See more {{ category.name }} items →</router-link
        >
      </div>
    </div>
  </section>
</template>

<script setup>
const categories = [
  {
    id: 1,
    name: "jewelry",
    description:
      "Explore unique handmade brass earrings, necklaces and bracelets.",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1723194319/A-display-of-african-jewelry-earrings_-rings_-stylish-hoops-_1__ervi29.webp",
  },
  {
    id: 2,
    name: "wall decor",
    description:
      "Decorate your space with authentic Maasai art, wall baskets, plates and vases.",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1723189032/o_1h1sgnc2fba911bc14en1eskt4j1r_f78rer.webp",
  },
  {
    id: 3,
    name: "beaded",
    description:
      "Shop comfortable and stylish Maasai beaded necklaces, chokers and anklets",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1723193607/beads-_1__cwo5lb.webp",
  },
];
</script>

<style scoped>
/* featured categories */
.featured-categories {
  padding: 40px 50px;
  background-color: var(--background-color);
}

.header-categories {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.title {
  font-size: var(--font-size-heading);
  font-weight: bold;
}

.see-all {
  font-size: var(--h3-size-large);
  color: var(--accent-color);
  text-decoration: underline;
}

.see-all:hover {
  text-decoration: none;
}

.categories-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
}

.category-card {
  border: 1px solid #ddd;
  border-radius: 5px;
  padding: 15px;
  background-color: var(--background-color);
  width: 100%;
  height: auto;
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  -webkit-box-shadow: 4px 7px 14px 0px rgba(0, 0, 0, 0.55);
  -moz-box-shadow: 4px 7px 14px 0px rgba(0, 0, 0, 0.55);
  box-shadow: 4px 7px 14px 0px rgba(0, 0, 0, 0.55);
}

.category-image {
  width: 100%;
  height: auto;
  object-fit: cover;
  border-radius: 5px;
  margin-bottom: 10px;
}

.discount {
  font-size: var(--font-extra-small);
  background: var(--dark-tint);
  border-radius: 3px;
  padding: 2px 8px;
  color: var(--background-color);
  margin-bottom: 10px;
}

.discount-icon {
  margin-right: 5px;
}

.category-description {
  font-size: var(--h3-font-size);
  margin-bottom: auto;
}

.see-more {
  font-size: var(--normal-font-size);
  color: var(--accent-color);
  font-weight: var(--font-bold);
  margin-top: 10px;
}

.see-more:hover {
  text-decoration: underline;
}

@media (max-width: 768px) {
  .featured-categories {
    padding: 40px 20px;
  }

  .categories-grid {
    grid-template-columns: 1fr 1fr;
    gap: 15px;
  }
}

@media (max-width: 480px) {
  .categories-grid {
    grid-template-columns: 1fr;
  }

  .title {
    font-size: var(--h2-font-size);
  }

  .see-all {
    display: none;
  }

  .category-card {
    margin-bottom: 10px;
  }

  .category-description {
    font-size: var(--normal-font-size);
  }

  .see-more {
    font-size: var(--smaller-font-size);
  }
}
</style>
