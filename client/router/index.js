import { createRouter, createWebHistory } from "vue-router";

import Home from "../views/Home.vue";
import About from "../views/About.vue";
import BlogList from "../views/Blog.vue";
import BlogPost from "@/components/layout/BlogPost.vue";
import Contact from "../views/Contact.vue";
import Shop from "../views/Shop.vue";
import NotFound from "../views/NotFound.vue";
import Preorder from "../views/Preorder.vue";

const routes = [
  { path: "/", component: Home },
  { path: "/about", component: About },
  { path: "/blog", component: BlogList },
  { path: "/blog/:slug", component: BlogPost, props: true },
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
