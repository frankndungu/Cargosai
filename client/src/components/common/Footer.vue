<template>
  <footer v-if="shouldShowFooter" class="footer">
    <div class="footer__content">
      <div class="footer__left">
        <div class="footer__logo">
          <span class="logo-main-title">maasai market</span>
          <span class="logo-sub-title">online</span>
        </div>
        <p class="footer__text">
          An online platform dedicated to selling authentic Maasai and African
          products
        </p>
        <p class="footer__copy">
          &copy; 2024 Fueled By Dosha LLC, All Rights Reserved
        </p>
      </div>
      <div class="footer__right">
        <ul class="footer__links">
          <li>
            <router-link to="/terms-of-service">Terms of Service</router-link>
          </li>
          <li>
            <router-link to="/frequently-asked-questions">FAQ</router-link>
          </li>
          <li><router-link to="/contact">Contact</router-link></li>
        </ul>
        <div class="footer__social-icons">
          <a
            href="https://x.com/maasaimarkethq"
            target="_blank"
            aria-label="X (Twitter)"
          >
            <i class="fab fa-x-twitter"></i>
          </a>
          <a href="" aria-label="Instagram">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="#" aria-label="TikTok">
            <i class="fab fa-tiktok"></i>
          </a>
          <a href="#" aria-label="Pinterest">
            <i class="fab fa-pinterest"></i>
          </a>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { computed } from "vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";

const store = useStore();
const route = useRoute();

// Check user role from the Vuex store
const userRole = computed(() => store.getters.userRole);

// Show or hide the footer based on route and user role
const shouldShowFooter = computed(() => {
  const hiddenRoutes = [
    "/checkout",
    "/cart",
    "/order/success", // Hide footer on order success page
    "/dashboard", // Hide footer on dashboard page
    "/dashboard/profile",
    "/dashboard/orders",
    "/dashboard/orders/:id",
    "/dashboard/wishlist",
    "/reset-password", // Hide footer on reset password page
  ];

  // Check if the current route is in the hidden routes array
  return !hiddenRoutes.includes(route.path) && userRole.value !== "admin";
});
</script>

<style scoped>
/* footer section */
.footer {
  background-color: var(--dark-tint);
  padding: 40px 50px;
  color: var(--background-color);
  margin-top: auto; /* Push the footer to the bottom */
}

.footer__content {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.footer__left {
  display: flex;
  flex-direction: column;
}

.footer__logo {
  display: flex;
  flex-direction: column;
}

.logo-main-title {
  font-family: "League Spartan", sans-serif;
  font-size: 24px;
  font-weight: 600;
  color: var(--background-color);
  margin-bottom: 2px;
}

.logo-sub-title {
  font-family: "Quicksand", sans-serif;
  font-size: 14px;
  letter-spacing: 5px;
  color: var(--background-color);
  margin-top: -4px;
  margin-bottom: 10px;
  margin-left: 38px;
}

.footer__left p {
  margin: 10px 0;
}

.footer__text {
  font-weight: var(--font-medium);
  font-size: var(--normal-font-size);
}

.footer__copy {
  font-weight: var(--font-light);
  font-size: var(--smaller-font-size);
}

.transform {
  text-decoration: underline;
}

.footer__right {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.footer__links {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  gap: 20px;
}

.footer__links li {
  margin-bottom: 20px;
}

.footer__links a {
  text-decoration: none;
  color: var(--background-color);
}

.footer__links a:hover {
  text-decoration: underline;
}

.footer__kofi img {
  width: 250px;
  height: auto;
  margin-bottom: 20px;
}

.footer__social-icons {
  display: flex;
  gap: 30px;
}

.footer__social-icons a {
  color: var(--background-color);
  font-size: 20px;
  transition: color 0.3s;
}

.footer__creator {
  display: none;
}

@media (max-width: 768px) {
  .footer__content {
    flex-direction: column;
    align-items: center;
  }

  .footer__left,
  .footer__right {
    align-items: center;
  }

  .footer__text {
    text-align: center;
  }

  .footer__links {
    justify-content: center;
    gap: 10px;
  }

  .footer__kofi img {
    margin-bottom: 20px;
  }
  .footer__copy {
    font-size: var(--font-extra-small);
  }
}
</style>
