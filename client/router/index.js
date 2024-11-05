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
import UserDashboard from "../views/UserDashboard.vue";
import UserProfile from "../views/UserProfile.vue";
import UserOrders from "../views/UserOrders.vue";
import OrderDetails from "../views/OrderDetails.vue";
import UserWishlist from "../views/UserWishlist.vue";
import AdminLayout from "../layouts/AdminLayout.vue";
import AdminDashboard from "../views/AdminDashboard.vue";
import AdminProducts from "../views/AdminProducts.vue";
import AdminCustomers from "../views/AdminCustomers.vue";
import AdminOrders from "../views/AdminOrders.vue";

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

  // Admin Dashboard routes
  {
    path: "/admin",
    component: AdminLayout,
    meta: { requiresAuth: true, requiresAdmin: true },
    children: [
      {
        path: "dashboard",
        name: "AdminDashboard",
        component: AdminDashboard,
        meta: { title: "Dashboard" },
      },
      {
        path: "products",
        name: "AdminProducts",
        component: AdminProducts,
        meta: { title: "Products" },
      },
      {
        path: "customers",
        name: "AdminCustomers",
        component: AdminCustomers,
        meta: { title: "Customers" },
      },
      {
        path: "orders",
        name: "AdminOrders",
        component: AdminOrders,
        meta: { title: "Orders" },
      },
    ],
  },

  // User Dashboard routes
  {
    path: "/dashboard",
    component: UserDashboard,
    meta: { requiresAuth: true },
  },
  {
    path: "/dashboard/profile",
    component: UserProfile,
    meta: { requiresAuth: true },
  },
  {
    path: "/dashboard/orders",
    component: UserOrders,
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
    component: UserWishlist,
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
  const userRole = store.getters.userRole;

  if (
    to.matched.some((record) => record.meta.requiresAuth) &&
    !isAuthenticated
  ) {
    next({ path: "/login" }); // Redirect to login if not authenticated
  } else if (
    to.matched.some((record) => record.meta.requiresAdmin) &&
    userRole !== "admin"
  ) {
    next({ path: "/404" }); // Redirect to 404 if not an admin
  } else if (to.path === "/login" && isAuthenticated) {
    // Redirect to user dashboard if already logged in as a user
    if (userRole === "user") {
      next({ path: "/dashboard" });
    } else if (userRole === "admin") {
      next({ path: "/admin/dashboard" }); // Redirect to admin dashboard if logged in as an admin
    }
  } else {
    next(); // Proceed to the route
  }
});

export default router;
