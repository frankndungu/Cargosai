import "./assets/main.css";
import ToastPlugin from "vue-toast-notification";
import "vue-toast-notification/dist/theme-bootstrap.css";

import { createApp } from "vue";
import router from "../router";
import App from "./App.vue";
import store from "@/store/modules";
import axios from "axios";

// Set the CSRF token for Axios
const csrfToken = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute("content");
axios.defaults.headers.common["X-CSRF-TOKEN"] = csrfToken;

// Add title handler to router
router.beforeEach((to, _, next) => {
  // Default title
  const defaultTitle = "Maasai Market Online";

  // Get the page title from the route meta
  const pageTitle = to.meta.title;

  // Set the page title
  document.title = pageTitle ? `${pageTitle} | ${defaultTitle}` : defaultTitle;

  next();
});

const app = createApp(App);

// Fetch user data on app load if token exists
if (localStorage.getItem("token")) {
  store.dispatch("fetchUser");
}

app.use(store);
app.use(ToastPlugin);
app.use(router);
app.mount("#app");
