<template>
  <section class="featured-categories">
    <div class="header-categories">
      <h2 class="title">Featured Categories</h2>
      <router-link to="/shop" class="see-all">
        Explore All Categories
        <span class="arrow">→</span>
      </router-link>
    </div>
    <div class="categories-grid">
      <div
        class="category-card"
        v-for="category in categories"
        :key="category.id"
        :class="{ 'hover-effect': true }"
      >
        <div class="image-container">
          <img
            :src="category.image"
            :alt="category.name"
            class="category-image"
          />
          <div class="overlay">
            <router-link :to="`/shop`" class="shop-now-btn">
              Shop Now
            </router-link>
          </div>
        </div>
        <div class="card-content">
          <h3 class="category-name">{{ category.name }}</h3>
          <div class="discount">
            <span class="discount-icon">¤</span>
            <span>Authentic African Crafts</span>
          </div>
          <p class="category-description">{{ category.description }}</p>
          <router-link :to="`/shop`" class="see-more">
            Explore {{ category.name }}
            <span class="arrow">→</span>
          </router-link>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
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
    name: "Wall Canvas",
    description:
      "Decorate your space with authentic Maasai art, wall baskets, plates and vases.",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1723189032/o_1h1sgnc2fba911bc14en1eskt4j1r_f78rer.webp",
  },
  {
    id: 3,
    name: "Beaded",
    description:
      "Shop comfortable and stylish Maasai beaded necklaces, chokers and anklets",
    image:
      "https://res.cloudinary.com/kwishi/image/upload/v1723193607/beads-_1__cwo5lb.webp",
  },
];
</script>

<style scoped>
.featured-categories {
  padding: 60px 50px;
  background-color: var(--background-color);
}

.header-categories {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 40px;
}

.title {
  font-size: var(--font-size-heading);
  font-weight: bold;
  position: relative;
}

.title::after {
  content: "";
  position: absolute;
  bottom: -10px;
  left: 0;
  width: 60px;
  height: 3px;
  background-color: var(--accent-color);
}

.see-all {
  font-size: var(--h3-size-large);
  color: var(--accent-color);
  text-decoration: none;
  display: flex;
  align-items: center;
  transition: all 0.3s ease;
}

.see-all:hover {
  transform: translateX(5px);
}

.arrow {
  margin-left: 8px;
  transition: transform 0.3s ease;
}

.see-all:hover .arrow {
  transform: translateX(5px);
}

.categories-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 30px;
}

.category-card {
  border-radius: 12px;
  background-color: var(--background-color);
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  cursor: pointer;
}

.image-container {
  position: relative;
  overflow: hidden;
  aspect-ratio: 3/2;
}

.category-image {
  width: 100%;
  height: auto;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.category-card:hover .category-image {
  transform: scale(1.05);
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.category-card:hover .overlay {
  opacity: 1;
}

.shop-now-btn {
  padding: 12px 24px;
  background-color: var(--accent-color);
  color: white;
  border-radius: 25px;
  text-decoration: none;
  font-weight: bold;
  transform: translateY(20px);
  transition: all 0.3s ease;
}

.category-card:hover .shop-now-btn {
  transform: translateY(0);
}

.card-content {
  padding: 20px;
}

.category-name {
  font-size: var(--h3-size-large);
  font-weight: bold;
  margin-bottom: 10px;
  text-transform: capitalize;
}

.discount {
  display: inline-flex;
  align-items: center;
  font-size: var(--font-extra-small);
  background: var(--dark-tint);
  border-radius: 20px;
  padding: 6px 12px;
  color: var(--background-color);
  margin-bottom: 15px;
}

.discount-icon {
  margin-right: 8px;
  font-size: 1.2em;
}

.category-description {
  font-size: var(--h3-font-size);
  line-height: 1.6;
  color: var(--text-color-light);
  margin-bottom: 20px;
}

.see-more {
  display: inline-flex;
  align-items: center;
  font-size: var(--normal-font-size);
  color: var(--accent-color);
  font-weight: var(--font-bold);
  text-decoration: none;
  transition: all 0.3s ease;
}

.see-more:hover {
  transform: translateX(5px);
}

.see-more .arrow {
  margin-left: 8px;
}

@media (max-width: 992px) {
  .featured-categories {
    padding: 40px 30px;
  }

  .categories-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
  }
}

@media (max-width: 768px) {
  .featured-categories {
    padding: 30px 20px;
  }

  .header-categories {
    margin-bottom: 30px;
  }

  .image-container {
    aspect-ratio: 6/5;
  }

  .category-image {
    height: 100%;
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
    font-size: var(--normal-font-size);
    display: none;
  }

  .category-description {
    font-size: var(--normal-font-size);
  }

  .card-content {
    padding: 15px;
  }
}
</style>
