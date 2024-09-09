import { createRouter, createWebHistory } from "vue-router";

import Home from "../views/Home.vue";
import About from "../views/About.vue";
import Blog from "../views/Blog.vue";
import BlogPost from "../views/BlogPost.vue";
import Contact from "../views/Contact.vue";
import Shop from "../views/Shop.vue";
import ProductPage from "../views/ProductPage.vue";
import NotFound from "../views/NotFound.vue";
import Cart from "../views/Cart.vue";
import TermsOfService from "../views/TermsOfService.vue";
import FrequentlyAskedQuestions from "../views/FrequentlyAskedQuestions.vue";

const routes = [
  { path: "/", component: Home },
  { path: "/about", component: About },
  { path: "/blog", component: Blog },
  { path: "/blog/:slug", component: BlogPost, name: "BlogPost", props: true }, // Route for individual blog post
  { path: "/contact", component: Contact },
  { path: "/cart", component: Cart },
  { path: "/shop", component: Shop },
  {
    path: "/shop/product",
    component: ProductPage,
    name: "ProductPage",
    props: true,
  }, // Route for individual product page
  { path: "/terms-of-service", component: TermsOfService },
  { path: "/frequently-asked-questions", component: FrequentlyAskedQuestions },
  { path: "/404", component: NotFound },
  { path: "/:catchAll(.*)", redirect: "/404" }, // Catch-all route for 404
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
