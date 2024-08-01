<template>
  <section class="hero">
    <div class="hero-content">
      <h1>
        Get beautiful, <em class="em">handmade antiques</em> from Kenya,<br />
        <span class="highlight">shipped directly to your door</span>
      </h1>
      <p>
        maasai market online is your one-stop shop for authentic Maasai and
        African antiques, jewelry, and fabrics.
      </p>
      <div class="hero-buttons">
        <router-link class="button-link" to="/blog">
          <button class="btn btn-light">Learn more</button></router-link
        >
        <router-link class="button-link" to="/pre-order">
          <button class="btn btn-dark">Get Started</button>
        </router-link>
      </div>
    </div>
    <div class="as-seen-on">
      <h2>As featured in</h2>
      <div class="hero-gallery">
        <img
          src="https://res.cloudinary.com/kwishi/image/upload/v1722437667/1658832030reddit-logo-png_vjgjzf.svg"
          alt="Reddit logo"
          class="reddit-logo"
        />
        <img
          src="https://res.cloudinary.com/kwishi/image/upload/v1722436863/product-hunt-logo-horizontal-black_sipadd.svg"
          alt="Product Hunt logo"
          class="product-hunt-logo"
        />
        <img
          src="https://res.cloudinary.com/kwishi/image/upload/v1722436613/indiehackers_ww9uac.svg"
          alt="Indie Hackers logo"
          class="indie-hackers-logo"
        />
      </div>
    </div>
  </section>
  <section class="featured-products">
    <h2 class="featured-heading">Featured Products</h2>
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
