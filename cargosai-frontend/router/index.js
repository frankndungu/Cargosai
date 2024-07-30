import { createRouter, createWebHistory } from "vue-router";

import Home from "../views/Home.vue";
import About from "../views/About.vue";
import Services from "../views/Services.vue";
import Blog from "../views/Blog.vue";
import Contact from "../views/Contact.vue";
import Shop from "../views/Shop.vue";
import NotFound from "../views/NotFound.vue";
import Preorder from "../views/Preorder.vue";

const routes = [
  { path: "/", component: Home },
  { path: "/about", component: About },
  { path: "/services", component: Services },
  { path: "/blog", component: Blog },
  { path: "/contact", component: Contact },
  { path: "/pre-order", component: Preorder },
  { path: "/:slug", component: Home }, // catch-all route for dynamic pages
  { path: "/shop", component: Shop },
  { path: "/404", component: NotFound },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
