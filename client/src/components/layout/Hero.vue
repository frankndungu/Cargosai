<template>
  <section class="hero">
    <div class="hero-content">
      <h1 class="hero-title">
        Get beautiful, <em class="em">handmade products</em> from Kenya,<br />
        <span class="highlight">shipped directly to your door</span>
      </h1>
      <p class="hero-description">
        maasai market online is your one-stop shop for authentic Maasai and
        African products
      </p>
      <div class="hero-buttons">
        <router-link class="button-link" to="/blog">
          <button class="btn btn-light">Learn more</button></router-link
        >
        <router-link class="button-link" to="/register">
          <button class="btn btn-dark">Get Started</button>
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
  background: var(--secondary-color);
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  padding: 100px 20px;
}

.hero-content {
  max-width: 800px;
  margin-bottom: 40px;
}

.hero h1 {
  font-size: var(--font-size-heading);
  font-weight: var(--font-bold);
  margin-bottom: 20px;
}

.em {
  font-weight: var(--font-medium);
}

.highlight {
  color: var(--accent-color);
}

.hero p {
  font-size: var(--h3-font-size);
  margin-bottom: 30px;
}

.hero-buttons {
  display: flex;
  gap: 20px;
  justify-content: center;
  width: 100%;
}

.btn {
  padding: 10px 20px;
  border-radius: 5px;
  font-weight: var(--font-medium);
  cursor: pointer;
}

.btn-light {
  border: 1px solid black;
  color: black;
  background-color: transparent;
}

.btn-light:hover {
  background-color: transparent;
  box-shadow: none;
}

.btn-dark {
  background: var(--dark-tint);
  color: var(--background-color);
  border: none;
}

.btn-dark:hover {
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0);
}

.btn-dark:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}

.as-seen-on {
  margin-top: 20px;
  text-align: center;
}

.as-seen-on h2 {
  font-size: var(--font-size-large);
  color: var(--accent-color);
  margin-bottom: 10px;
  font-weight: var(--font-medium);
}

.hero-gallery {
  margin-top: 30px;
  display: flex;
  gap: 80px;
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
    padding: 80px 20px;
  }

  .hero-content {
    max-width: 100%;
    padding: 0 20px;
  }

  .hero h1 {
    font-size: var(--font-size-large);
  }

  .hero p {
    font-size: var(--h3-font-size);
  }

  .hero-buttons {
    flex-direction: column;
    align-items: center;
    gap: 10px;
  }

  .btn,
  .button-link {
    padding: 8px 16px;
    width: 100%;
    max-width: 300px;
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
