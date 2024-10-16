<template>
  <aside class="sidebar">
    <nav class="sidebar-nav">
      <router-link
        v-for="item in navItems"
        :key="item.name"
        :to="item.href"
        class="nav-item"
      >
        <component :is="item.icon" />
        {{ item.name }}
      </router-link>
    </nav>
  </aside>
</template>

<script setup>
import {
  LayoutGrid,
  UserIcon,
  ShoppingBagIcon,
  HeartIcon,
  LogOutIcon,
} from "lucide-vue-next";

const navItems = [
  { name: "Dashboard", href: "/dashboard", icon: LayoutGrid },
  { name: "Profile", href: "/dashboard/profile", icon: UserIcon },
  { name: "Orders", href: "/dashboard/orders", icon: ShoppingBagIcon },
  { name: "Wishlist", href: "/dashboard/wishlist", icon: HeartIcon },
  { name: "Logout", href: "/logout", icon: LogOutIcon },
];
</script>
