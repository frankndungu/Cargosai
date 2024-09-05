import "./assets/main.css";
import ToastPlugin from "vue-toast-notification";
import "vue-toast-notification/dist/theme-bootstrap.css";

import { createApp } from "vue";
import router from "../router";
import App from "./App.vue";
import store from "./store";

const app = createApp(App);
app.use(store);
app.use(ToastPlugin);
app.use(router);
app.mount("#app");
