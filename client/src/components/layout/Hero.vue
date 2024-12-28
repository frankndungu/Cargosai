<template>
  <section class="hero">
    <div class="hero-content">
      <h1 class="hero-title">
        Get beautiful, <em class="em">handmade products</em> from Kenya,
        <span class="highlight">shipped directly to your door</span>
      </h1>
      <p class="hero-description">
        maasai market online is your one-stop shop for authentic Maasai and
        African products
      </p>
      <div class="hero-buttons">
        <router-link class="button-link" to="/blog">
          <button class="btn btn-light">
            <span>Learn more</span>
            <svg
              class="arrow-icon"
              width="20"
              height="20"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <line x1="5" y1="12" x2="19" y2="12"></line>
              <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
          </button>
        </router-link>
        <router-link class="button-link" to="/register">
          <button class="btn btn-dark">
            <span>Get Started</span>
            <svg
              class="arrow-icon"
              width="20"
              height="20"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <line x1="5" y1="12" x2="19" y2="12"></line>
              <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
          </button>
        </router-link>
      </div>
    </div>
    <div class="as-seen-on">
      <h2>As featured on</h2>
      <div class="hero-gallery">
        <img
          src="https://res.cloudinary.com/kwishi/image/upload/v1723619463/betalist_yeov5t.svg"
          alt="Betalist logo"
          class="betalist-logo"
        />
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
</template>

<style scoped>
/* hero section */
.hero {
  background: linear-gradient(
    135deg,
    var(--secondary-color) 0%,
    var(--background-color) 100%
  );
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  padding: 120px 20px 80px;
  position: relative;
  overflow: hidden;
}

.hero-content {
  max-width: 900px;
  margin-bottom: 40px;
  position: relative;
  z-index: 1;
}

.hero-title {
  font-size: 3rem;
  font-weight: 800;
  line-height: 1.2;
  margin-bottom: 1.5rem;
}

.em {
  font-style: normal;
  position: relative;
  display: inline-block;
  color: var(--accent-color);
}

.em::after {
  content: "";
  position: absolute;
  bottom: -4px;
  left: 0;
  width: 100%;
  height: 4px;
  background: var(--dark-tint);
  opacity: 0.3;
}

.highlight {
  color: var(--dark-color);
}

.hero-description {
  font-size: 1.25rem;
  line-height: 1.6;
  color: var(--dark-color);
  margin-bottom: 2.5rem;
  max-width: 700px;
  margin-left: auto;
  margin-right: auto;
}

.hero-buttons {
  display: flex;
  gap: 24px;
  justify-content: center;
  margin-bottom: 3rem;
}

.button-link {
  text-decoration: none;
}

.btn {
  padding: 16px 32px;
  border-radius: 50px;
  font-weight: 600;
  font-size: 1.1rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
}

.btn-light {
  background: transparent;
  color: var(--dark-color);
  border: 2px solid #ccc;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.btn-light:hover {
  background: #f5f5f5;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.btn-dark {
  background: var(--dark-tint);
  color: white;
  border: none;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.btn-dark:hover {
  background: var(--dark-color);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.arrow-icon {
  transition: transform 0.3s ease;
}

.btn:hover .arrow-icon {
  transform: translateX(4px);
}

.as-seen-on {
  margin-top: 20px;
  text-align: center;
}

.as-seen-on h2 {
  font-size: 1.25rem;
  color: var(--dark-color);
  margin-bottom: 40px;
  text-transform: uppercase;
  letter-spacing: 2px;
  position: relative;
  display: inline-block;
}

.as-seen-on h2::after {
  content: "";
  position: absolute;
  bottom: -10px;
  left: 50%;
  transform: translateX(-50%);
  width: 60px;
  height: 2px;
  background-color: var(--accent-color);
}

.hero-gallery {
  margin-top: 30px;
  display: flex;
  gap: 80px;
}

.hero-gallery img {
  opacity: 0.7;
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.hero-gallery img:hover {
  opacity: 1;
  transform: scale(1.05);
}

.indie-hackers-logo {
  width: 100px;
  justify-content: center;
  flex-wrap: wrap;
  height: auto;
}

.product-hunt-logo {
  width: 200px;
  justify-content: center;
  flex-wrap: wrap;
  height: auto;
}

.reddit-logo {
  width: 120px;
  border-radius: 5px;
  justify-content: center;
  flex-wrap: wrap;
  height: auto;
}
.betalist-logo {
  width: 200px;
  border-radius: 5px;
  justify-content: center;
  flex-wrap: wrap;
  height: auto;
}

@media (max-width: 768px) {
  .hero {
    padding: 100px 20px;
  }

  .hero-title {
    font-size: 1.22rem;
  }

  .hero-description {
    font-size: 0.75rem;
    padding: 0 20px;
  }

  .em::after {
    background: none;
  }

  .as-seen-on h2 {
    font-size: 1.1rem;
  }

  .hero-buttons {
    flex-direction: column;
    align-items: center;
    gap: 16px;
    width: 100%;
    max-width: 300px;
    margin-left: auto;
    margin-right: auto;
  }

  .button-link {
    width: 100%;
  }

  .btn {
    width: 100%;
    justify-content: center;
    padding: 14px 24px;
  }

  .hero-gallery {
    flex-direction: column;
    gap: 20px;
    width: 100%;
    margin-top: 30px;
  }

  .betalist-logo {
    width: 200px;
    margin: 0 auto;
  }

  .reddit-logo {
    width: 170px;
    margin: 0 auto;
  }

  .product-hunt-logo {
    width: 190px;
    margin: 0 auto;
  }

  .indie-hackers-logo {
    width: 160px;
    margin: 0 auto;
  }
}
</style>
