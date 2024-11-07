import store from "@/store";
import { createRouter, createWebHistory } from "vue-router";

// Import views and layouts
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

// Define admin routes
const adminRoutes = [
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
];

// Define user routes
const userRoutes = [
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
];

// Main routes configuration
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

  // Admin dashboard routes
  {
    path: "/admin",
    component: AdminLayout,
    meta: { requiresAuth: true, requiresAdmin: true },
    children: adminRoutes,
  },

  // User dashboard routes
  ...userRoutes,

  // Error handling routes
  { path: "/404", component: NotFound, meta: { title: "Page Not Found" } },
  { path: "/:catchAll(.*)", redirect: "/404" },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Navigation Guard for authentication and role-based access
router.beforeEach((to, _, next) => {
  const isAuthenticated = store.getters.isAuthenticated;
  const userRole = store.getters.userRole;

  // Redirect to login if route requires auth and user isn't authenticated
  if (
    to.matched.some((record) => record.meta.requiresAuth) &&
    !isAuthenticated
  ) {
    return next({ path: "/login" });
  }

  // Redirect to 404 if route requires admin and user isn't an admin
  if (
    to.matched.some((record) => record.meta.requiresAdmin) &&
    userRole !== "admin"
  ) {
    return next({ path: "/404" });
  }

  // Redirect authenticated users trying to access login to their respective dashboards
  if (to.path === "/login" && isAuthenticated) {
    return next(
      userRole === "admin"
        ? { path: "/admin/dashboard" }
        : { path: "/dashboard" }
    );
  }

  // Allow navigation to the requested route
  next();
});

export default router;
