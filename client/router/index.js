import { createRouter, createWebHistory } from "vue-router";
import store from "@/store/modules";

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
import Reset from "../views/Reset.vue";
import NotFound from "../views/NotFound.vue";
import Cart from "../views/Cart.vue";
import Checkout from "../views/Checkout.vue";
import PaymentFailed from "../views/PaymentFailed.vue";
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

// Define admin routes as children of AdminLayout
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
    path: "products/create",
    name: "AdminProductsCreate",
    component: () => import("../views/AdminCreateProducts.vue"),
  },
  {
    path: "products/:id/edit",
    name: "AdminProductsEdit",
    component: () => import("../views/AdminUpdateProducts.vue"),
    props: true,
  },
  {
    path: "customers",
    name: "AdminCustomers",
    component: AdminCustomers,
    meta: { title: "Customers" },
  },
  {
    path: "customers/:id",
    name: "AdminCustomerDetails",
    component: () => import("../views/AdminCustomerDetails.vue"),
    props: true,
  },
  {
    path: "orders",
    name: "AdminOrders",
    component: AdminOrders,
    meta: { title: "Orders" },
  },
  {
    path: "orders/:id",
    name: "AdminOrdersDetails",
    component: () => import("../views/AdminOrderDetails.vue"),
    props: true,
  },
];

// Define user routes
const userRoutes = [
  {
    path: "/dashboard",
    component: UserDashboard,
    meta: { requiresAuth: true, title: "Dashboard" },
  },
  {
    path: "/dashboard/profile",
    component: UserProfile,
    meta: { requiresAuth: true, title: "Profile" },
  },
  {
    path: "/dashboard/orders",
    component: UserOrders,
    meta: { requiresAuth: true, title: "Orders History" },
  },
  {
    path: "/dashboard/orders/:id",
    component: OrderDetails,
    name: "OrderDetails",
    props: true,
    meta: { requiresAuth: true, title: "Order Details" },
  },
  {
    path: "/dashboard/wishlist",
    component: UserWishlist,
    meta: { requiresAuth: true, title: "My Wishlist" },
  },
];

// Main routes configuration
const routes = [
  {
    path: "/",
    component: Home,
    meta: {
      title: "Authentic Handmade Maasai & African Crafts, Art & Jewelry",
      description:
        "Discover authentic handmade African crafts, Maasai jewelry, beadwork, and traditional home decor. Shop our curated collection of fair-trade artisan pieces direct from Kenya. Free shipping on orders over $50.",
      keywords:
        "Maasai jewelry, African crafts, handmade African jewelry, Maasai beadwork, African home decor, authentic African art, Kenyan crafts, fair trade African crafts, traditional Maasai accessories, African tribal art, African cultural gifts, Maasai market, African artisan crafts, authentic African decor, handcrafted African items",
      canonical: "https://maasaimarketonline.com/",
      ogTitle: "Authentic Maasai & African Crafts | Handmade Jewelry & Decor",
      ogDescription:
        "Shop authentic handmade African crafts, Maasai jewelry, and traditional home decor. Direct from Kenyan artisans with free shipping over $50.",
      twitterCard: "summary_large_image",
      twitterTitle:
        "Authentic Maasai & African Crafts | Handmade Jewelry & Decor",
      twitterDescription:
        "Shop authentic handmade African crafts, Maasai jewelry, and traditional home decor. Direct from Kenyan artisans.",
      twitterImage: "/images/maasai-market-crafts.jpg",
      robots: "index, follow",
      alternateLanguages: {
        en: "https://maasaimarketonline.com/",
        sw: "https://maasaimarketonline.com/sw",
      },
    },
  },
  {
    path: "/about",
    component: About,
    meta: { title: "Our Story & Heritage" },
    description:
      "Learn about our story and heritage behind our African crafts.",
    keywords: "African heritage, African story, handmade crafts",
  },
  {
    path: "/blog",
    component: Blog,
    meta: {
      title:
        "African Art & Culture Blog | Traditional Crafts & Maasai Heritage | Maasai Market Online",
      description:
        "Explore the rich heritage of African art, Maasai culture, and traditional craftsmanship. Read expert insights on African jewelry making, cultural ceremonies, sustainable practices, and artisan stories from Kenya.",
      keywords:
        "African art blog, Maasai culture, African craft techniques, traditional African jewelry, Kenyan art history, Maasai heritage, African cultural ceremonies, sustainable African crafts, African artisan stories, Maasai traditions, African design inspiration, African craft tutorials, East African culture",
      canonical: "https://maasaimarketonline.com/blog",
      ogTitle:
        "African Art & Culture Blog | Traditional Crafts & Maasai Heritage",
      ogDescription:
        "Dive into African art, Maasai culture, and traditional craftsmanship. Expert insights on jewelry making, cultural ceremonies, and artisan stories.",
      ogImage: "/images/blog-header.jpg",
      twitterCard: "summary_large_image",
      twitterTitle: "African Art & Culture Blog | Maasai Market Online",
      twitterDescription:
        "Explore African art, Maasai culture, and traditional craftsmanship through our expert articles and artisan stories.",
      twitterImage: "/images/blog-twitter.jpg",
      schema: {
        "@type": "Blog",
        name: "Maasai Market Online Blog",
        description:
          "Expert insights into African art, culture, and traditional crafts",
        publisher: {
          "@type": "Organization",
          name: "Maasai Market Online",
          logo: {
            "@type": "ImageObject",
            url: "https://maasaimarketonline.com/logo.png",
          },
        },
      },
    },
  },
  {
    path: "/blog/:slug",
    component: BlogPost,
    name: "BlogPost",
    props: true,
    meta: {
      title:
        "{{ post.title }} | African Art & Culture Blog | Maasai Market Online",
      description:
        "{{ post.excerpt }} Read more about {{ post.topic }} on our African art and culture blog. Expert insights from Maasai Market Online.",
      keywords:
        "{{ post.keywords }}, {{ post.category }}, African culture, Maasai traditions, {{ post.tags }}",
      canonical: "https://maasaimarketonline.com/blog/{{ post.slug }}",
      ogTitle: "{{ post.title }} | African Art & Culture",
      ogDescription:
        "{{ post.excerpt }} Discover more about {{ post.topic }} in our expert blog.",
      ogImage: "{{ post.featuredImage }}",
      twitterCard: "summary_large_image",
      twitterTitle: "{{ post.title }} | African Art & Culture",
      twitterDescription:
        "{{ post.excerpt }} Expert insights on {{ post.topic }}.",
      twitterImage: "{{ post.featuredImage }}",
      schema: {
        "@type": "BlogPosting",
        headline: "{{ post.title }}",
        image: "{{ post.featuredImage }}",
        author: {
          "@type": "Person",
          name: "{{ post.author.name }}",
          url: "https://maasaimarketonline.com/authors/{{ post.author.slug }}",
        },
        datePublished: "{{ post.publishDate }}",
        dateModified: "{{ post.modifiedDate }}",
        keywords: "{{ post.keywords }}",
        description: "{{ post.excerpt }}",
        articleBody: "{{ post.content }}",
        publisher: {
          "@type": "Organization",
          name: "Maasai Market Online",
          logo: {
            "@type": "ImageObject",
            url: "https://maasaimarketonline.com/logo.png",
          },
        },
      },
      articleTags: "{{ post.tags }}",
      articleCategory: "{{ post.category }}",
      publishDate: "{{ post.publishDate }}",
      modifiedDate: "{{ post.modifiedDate }}",
      author: "{{ post.author.name }}",
      readingTime: "{{ post.readingTime }}",
    },
  },
  {
    path: "/contact",
    component: Contact,
    meta: { title: "Get in Touch" },
    description:
      "Contact us for inquiries or more information about our products.",
  },
  {
    path: "/login",
    component: Login,
    meta: { title: "Welcome Back" },
    description: "Login to your account to access your personal dashboard",
  },
  {
    path: "/register",
    component: Register,
    meta: { title: "Join Our Community" },
    description:
      "Create an account and join our community of African craft lovers",
  },
  {
    path: "/admin/login",
    component: AdminLogin,
    meta: { title: "Admin Portal" },
    description: "Admin login portal to manage products and orders.",
  },
  {
    path: "/reset-password",
    component: Recovery,
    meta: { title: "Forgot Your Password" },
    description: "Reset your password to regain access to your account",
  },
  {
    path: "/recovery-email",
    component: Reset,
    meta: { title: "Recover Your Account" },
    description: "Receive an email to recover your account..",
  },
  {
    path: "/cart",
    component: Cart,
    meta: { title: "Your Shopping Cart" },
    description: "Review the items in your shopping cart before checking out.",
  },
  {
    path: "/shop",
    component: Shop,
    meta: { title: "Shop African Crafts" },
    description: "Explore our curated selection of handmade African crafts.",
  },
  {
    path: "/shop/product/:slug",
    component: ProductPage,
    name: "ProductPage",
    props: true,
    meta: { title: "Product Details" },
    description: "Learn more about this handcrafted African product.",
    keywords:
      "African crafts, African products, handmade, shop, earrings, rings, bracelets, necklaces, chokers",
  },
  {
    path: "/terms-of-service",
    component: TermsOfService,
    meta: { title: "Terms of Service" },
    description: "Review our website's terms of service.",
  },
  {
    path: "/frequently-asked-questions",
    component: FrequentlyAskedQuestions,
    meta: { title: "FAQ & Help Center" },
    description: "Find answers to your frequently asked questions.",
  },

  // User dashboard routes
  ...userRoutes,

  // Admin routes
  {
    path: "/admin",
    component: AdminLayout,
    children: adminRoutes,
    meta: { requiresAdmin: true },
  },

  // Fallback route for 404 Not Found
  {
    path: "/:catchAll(.*)",
    component: NotFound,
    meta: {
      title: "Page Not Found",
      description: "The page you are looking for could not be found.",
    },
  },
  // Checkout route for authenticated users and guest users
  {
    path: "/checkout",
    component: Checkout,
    meta: { title: "Checkout" }, // Removed requiresAuth: true
    description: "Complete your purchase and enter payment details.",
  },
  // failed payment route
  {
    path: "/payment/failed",
    name: "PaymentFailed",
    meta: { title: "Payment Failed" },
    description: "Your payment was not successful. Please try again.",
    component: PaymentFailed,
  },
  // Updated order confirmation route to handle both guest and authenticated orders
  {
    path: "/order/success/",
    name: "OrderConfirmation",
    component: () => import("../views/OrderConfirmation.vue"),
    meta: {
      title: "Order Confirmation",
      description:
        "Thank you for your order. View your order confirmation details.",
    },
  },
];

// Router configuration
const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Update Navigation Guard to handle guest checkout
router.beforeEach((to, _, next) => {
  const isAuthenticated = store.getters.isAuthenticated;
  const userRole = store.getters.userRole;
  const isGuestCheckout = store.getters.isGuestCheckout;

  // Allow checkout access for guest checkout
  if (to.path === "/checkout" && !isAuthenticated && isGuestCheckout) {
    return next();
  }

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
