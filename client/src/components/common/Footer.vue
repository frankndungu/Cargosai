<template>
  <footer class="footer">
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
        <div class="footer__kofi">
          <a href="https://ko-fi.com" target="_blank">
            <img
              src="https://res.cloudinary.com/kwishi/image/upload/v1722645593/64f1a9ddd0246590df69ea10_kofi_long_button_stroke_2x_dsqnnc.png"
              alt="Support me on Ko-fi"
            />
          </a>
        </div>
        <div class="footer__social-icons">
          <a href="#" aria-label="Instagram">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="#" aria-label="X (Twitter)">
            <i class="fab fa-x-twitter"></i>
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
