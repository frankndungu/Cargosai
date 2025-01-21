import "@/assets/main.css";
import { createApp } from "vue";
import { createMetaManager } from "vue-meta"; // Correct import for Vue 3
import ToastPlugin from "vue-toast-notification";
import "vue-toast-notification/dist/theme-bootstrap.css";
import axios from "axios";
import router from "../router";
import App from "./App.vue";
import store from "@/store/modules";

// Set the CSRF token for Axios
const csrfToken = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute("content");
axios.defaults.headers.common["X-CSRF-TOKEN"] = csrfToken;

// Add title handler to router
router.beforeEach((to, _, next) => {
  const defaultTitle = "Maasai Market Online";
  const pageTitle = to.meta.title;
  document.title = pageTitle ? `${pageTitle} | ${defaultTitle}` : defaultTitle;
  next();
});

const app = createApp(App);

// Fetch user data on app load if token exists
if (localStorage.getItem("token")) {
  store.dispatch("fetchUser");
}

const metaManager = createMetaManager(); // Create meta manager instance for Vue 3

app.use(store);
app.use(ToastPlugin);
app.use(router);
app.use(metaManager); // Use the vue-meta plugin for Vue 3
app.mount("#app");
