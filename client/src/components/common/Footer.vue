<template>
  <footer v-if="shouldShowFooter" class="footer">
    <div class="footer__container">
      <!-- Company Info Section -->
      <div class="footer__section footer__company">
        <div class="footer__logo">
          <span class="logo-main-title">maasai market</span>
          <span class="logo-sub-title">online</span>
        </div>
        <p class="footer__description">
          An online platform dedicated to selling authentic Maasai and African
          products
        </p>
      </div>

      <!-- Quick Links Section -->
      <div class="footer__section footer__links-section">
        <h3 class="footer__heading">Quick Links</h3>
        <ul class="footer__links">
          <li>
            <router-link to="/terms-of-service">Terms of Service</router-link>
          </li>
          <li>
            <router-link to="/frequently-asked-questions">FAQ</router-link>
          </li>
          <li><router-link to="/contact">Contact</router-link></li>
        </ul>
      </div>

      <!-- Social Media Section -->
      <div class="footer__section footer__social-section">
        <h3 class="footer__heading">Connect With Us</h3>
        <div class="footer__social-icons">
          <a
            href="https://x.com/maasaimarkethq"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="X (Twitter)"
            class="social-icon"
          >
            <i class="fab fa-x-twitter"></i>
          </a>
          <a
            href="#"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="Instagram"
            class="social-icon"
          >
            <i class="fab fa-instagram"></i>
          </a>
          <a
            href="#"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="TikTok"
            class="social-icon"
          >
            <i class="fab fa-tiktok"></i>
          </a>
        </div>
      </div>
    </div>

    <!-- Bottom Bar -->
    <div class="footer__bottom">
      <p class="footer__copy">
        &copy; 2024 Fueled By Dosha LLC, All Rights Reserved
      </p>
    </div>
  </footer>
</template>

<script setup>
import { computed } from "vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";

const store = useStore();
const route = useRoute();

const userRole = computed(() => store.getters.userRole);

const shouldShowFooter = computed(() => {
  const hiddenRoutes = [
    "/blog",
    "/checkout",
    "/cart",
    "/contact",
    "/order/success",
    "/dashboard",
    "/dashboard/profile",
    "/dashboard/orders",
    "/dashboard/orders/:id",
    "/dashboard/wishlist",
    "/reset-password",
  ];

  const dynamicHiddenRoutes = [/^\/blog\/.+/];

  const isHidden =
    hiddenRoutes.includes(route.path) ||
    dynamicHiddenRoutes.some((regex) => regex.test(route.path));

  return !isHidden && userRole.value !== "admin";
});
</script>

<style scoped>
.footer {
  background: #0f0f0f;
  color: var(--background-color);
  margin-top: auto;
  position: relative;
  overflow: hidden;
}

.footer::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
}

.footer__container {
  padding: 40px 50px;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 50px;
  position: relative;
}

.footer__section {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.footer__company {
  max-width: 350px;
}

.footer__logo {
  display: flex;
  flex-direction: column;
  margin-bottom: 20px;
}

.logo-main-title {
  font-family: "League Spartan", sans-serif;
  font-size: 32px;
  font-weight: 600;
  background: linear-gradient(45deg, #ffffff, #e6e6e6);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.logo-sub-title {
  font-family: "Quicksand", sans-serif;
  font-size: 16px;
  letter-spacing: 6px;
  color: #f0f0f0;
  opacity: 0.9;
  margin-left: 60px;
  margin-top: -2px;
}

.footer__description {
  font-size: var(--normal-font-size);
  line-height: 1.7;
  color: #f0f0f0;
  opacity: 0.85;
}

.footer__heading {
  font-size: 20px;
  font-weight: 600;
  margin-bottom: 12px;
  position: relative;
  display: inline-block;
}

.footer__heading::after {
  content: "";
  position: absolute;
  bottom: -6px;
  left: 0;
  width: 30px;
  height: 2px;
}

.footer__links {
  list-style: none;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 14px;
}

.footer__links a {
  color: #f0f0f0;
  text-decoration: none;
  opacity: 0.85;
  transition: all 0.3s ease;
  position: relative;
  padding-left: 0;
}

.footer__links a:hover {
  opacity: 1;
  padding-left: 8px;
  color: #ffffff;
}

.footer__social-icons {
  display: flex;
  gap: 24px;
}

.social-icon {
  color: #f0f0f0;
  font-size: 24px;
  opacity: 0.85;
  transition: all 0.3s ease;
  padding: 10px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.05);
}

.social-icon:hover {
  opacity: 1;
  transform: translateY(-3px);
  background: rgba(255, 255, 255, 0.1);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.footer__bottom {
  border-top: 1px solid hsla(0, 0%, 100%, 0.05);
  padding: 24px;
  text-align: center;
  background: #0f0f0f;
}

.footer__copy {
  font-size: var(--smaller-font-size);
  color: #f0f0f0;
  opacity: 0.7;
}

@media (max-width: 768px) {
  .footer__container {
    grid-template-columns: 1fr;
    text-align: center;
    gap: 40px;
    padding: 50px 24px;
  }

  .logo-sub-title {
    margin-left: 8px;
  }

  .footer__company {
    max-width: 100%;
  }

  .footer__section {
    align-items: center;
  }

  .footer__logo {
    align-items: center;
  }

  .footer__heading::after {
    left: 50%;
    transform: translateX(-50%);
    width: 40px;
  }

  .footer__social-icons {
    justify-content: center;
  }

  .footer__links {
    align-items: center;
  }

  .footer__links a:hover {
    padding-left: 0;
    transform: translateY(-2px);
  }
}
</style>
