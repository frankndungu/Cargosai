<template>
  <aside class="sidebar">
    <nav class="sidebar-nav">
      <router-link
        v-for="item in navItems"
        :key="item.name"
        :to="item.href !== '/logout' ? item.href : '#'"
        class="nav-item"
        @click="item.name === 'Logout' ? logout() : null"
      >
        <component :is="item.icon" />
        {{ item.name }}
      </router-link>
    </nav>
  </aside>
</template>

<script setup>
import { useStore } from "vuex";
import { useToast } from "vue-toast-notification"; // Correct import
import {
  LayoutGrid,
  UserIcon,
  ShoppingBagIcon,
  HeartIcon,
  LogOutIcon,
} from "lucide-vue-next";

const store = useStore();
const toast = useToast(); // Create an instance of toast

const navItems = [
  { name: "Dashboard", href: "/dashboard", icon: LayoutGrid },
  { name: "Profile", href: "/dashboard/profile", icon: UserIcon },
  { name: "Orders", href: "/dashboard/orders", icon: ShoppingBagIcon },
  { name: "Wishlist", href: "/dashboard/wishlist", icon: HeartIcon },
  { name: "Logout", href: "/logout", icon: LogOutIcon },
];

const logout = () => {
  store.dispatch("logout").then(() => {
    // Show a toast notification on successful logout
    toast.success("Successfully logged out!", {
      duration: 5000, // duration in milliseconds
      position: "bottom-right", // position of the toast
    });

    // Optionally redirect after logout
    // this.$router.push('/login');
  });
};
</script>
