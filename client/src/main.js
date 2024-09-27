import "./assets/main.css";
import ToastPlugin from "vue-toast-notification";
import "vue-toast-notification/dist/theme-bootstrap.css";

import { createApp } from "vue";
import router from "../router";
import App from "./App.vue";
import store from "./store";
import axios from "axios";

// Set the CSRF token for Axios
const csrfToken = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute("content");
axios.defaults.headers.common["X-CSRF-TOKEN"] = csrfToken;

const app = createApp(App);
app.use(store);
app.use(ToastPlugin);
app.use(router);
app.mount("#app");
