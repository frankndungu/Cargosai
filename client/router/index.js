import { createRouter, createWebHistory } from "vue-router";

import Home from "../views/Home.vue";
import About from "../views/About.vue";
import Blog from "../views/Blog.vue";
import BlogPost from "../views/BlogPost.vue";
import Contact from "../views/Contact.vue";
import Shop from "../views/Shop.vue";
import NotFound from "../views/NotFound.vue";
import Preorder from "../views/Preorder.vue";

const routes = [
  { path: "/", component: Home },
  { path: "/about", component: About },
  { path: "/blog", component: Blog },
  { path: "/blog/:slug", component: BlogPost, name: "BlogPost", props: true }, // Route for individual blog post
  { path: "/contact", component: Contact },
  { path: "/pre-order", component: Preorder },
  { path: "/shop", component: Shop },
  { path: "/404", component: NotFound },
  { path: "/:catchAll(.*)", redirect: "/404" }, // Catch-all route for 404
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
