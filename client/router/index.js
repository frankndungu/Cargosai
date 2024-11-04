import store from "@/store";
import { createRouter, createWebHistory } from "vue-router";

import Home from "../views/Home.vue";
import About from "../views/About.vue";
import Blog from "../views/Blog.vue";
import BlogPost from "../views/BlogPost.vue";
import Contact from "../views/Contact.vue";
import Shop from "../views/Shop.vue";
import Login from "../views/Login.vue";
import Register from "../views/Register.vue";
import AdminLogin from "../views/AdminLogin.vue";
import ProductPage from "../views/ProductPage.vue";
import Recovery from "../views/Recovery.vue";
import NotFound from "../views/NotFound.vue";
import Cart from "../views/Cart.vue";
import TermsOfService from "../views/TermsOfService.vue";
import FrequentlyAskedQuestions from "../views/FrequentlyAskedQuestions.vue";
import AdminDashboard from "../views/AdminDashboard.vue";
import UserDashboard from "../views/UserDashboard.vue";
import UserProfile from "../views/UserProfile.vue"; // Import the UserProfile component
import UserOrders from "../views/UserOrders.vue"; // Import the UserOrders component
import OrderDetails from "../views/OrderDetails.vue"; // Import the OrderDetails component
import UserWishlist from "../views/UserWishlist.vue"; // Import the UserWishlist component

const routes = [
  { path: "/", component: Home },
  { path: "/about", component: About },
  { path: "/blog", component: Blog },
  { path: "/blog/:slug", component: BlogPost, name: "BlogPost", props: true },
  { path: "/contact", component: Contact },
  { path: "/login", component: Login },
  { path: "/register", component: Register },
  { path: "/admin/login", component: AdminLogin },
  { path: "/recovery", component: Recovery },
  { path: "/admin/dashboard", component: AdminDashboard },

  // Dashboard routes
  {
    path: "/dashboard",
    component: UserDashboard,
    meta: { requiresAuth: true },
  },
  {
    path: "/dashboard/profile",
    component: UserProfile, // Separate profile page
    meta: { requiresAuth: true },
  },
  {
    path: "/dashboard/orders",
    component: UserOrders, // Separate orders page
    meta: { requiresAuth: true },
  },
  {
    path: "/dashboard/orders/:id",
    component: OrderDetails,
    name: "OrderDetails",
    props: true,
    meta: { requiresAuth: true },
  },
  {
    path: "/dashboard/wishlist",
    component: UserWishlist, // Separate wishlist page
    meta: { requiresAuth: true },
  },

  // Other routes
  { path: "/cart", component: Cart },
  { path: "/shop", component: Shop },
  {
    path: "/shop/product/:slug",
    component: ProductPage,
    name: "ProductPage",
    props: true,
  },
  { path: "/terms-of-service", component: TermsOfService },
  { path: "/frequently-asked-questions", component: FrequentlyAskedQuestions },
  { path: "/404", component: NotFound },
  { path: "/:catchAll(.*)", redirect: "/404" },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Navigation Guard
router.beforeEach((to, from, next) => {
  const isAuthenticated = store.getters.isAuthenticated;

  if (
    to.matched.some((record) => record.meta.requiresAuth) &&
    !isAuthenticated
  ) {
    next({ path: "/login" }); // Redirect to login if not authenticated
  } else {
    next(); // Proceed to the route
  }
});

export default router;
