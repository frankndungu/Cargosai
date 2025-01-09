<template>
  <section class="banner">
    <div class="pre-order-banner">
      <div class="banner-content">
        <h2>Authentic Maasai Art & Jewelry</h2>
        <p>Direct from Kenyan artisans..</p>
        <button class="pre-order-button" @click="scrollToProducts">
          Shop Now
        </button>
      </div>
      <div class="banner-image">
        <!-- Use an image or illustration that matches the Maasai theme -->
        <img
          src="https://res.cloudinary.com/kwishi/image/upload/v1723030094/crafting_r67xvv.png"
          alt="Maasai Artistry Illustration"
        />
      </div>
    </div>
  </section>
</template>

<script setup>
const scrollToProducts = () => {
  const featuredProducts = document.getElementById("product-grid");
  if (featuredProducts) {
    featuredProducts.scrollIntoView({ behavior: "smooth" });
  }
};
</script>
