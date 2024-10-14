<template>
  <aside class="sidebar">
    <nav class="sidebar-nav">
      <a
        v-for="item in navItems"
        :key="item.name"
        :href="item.href"
        class="nav-item"
      >
        <component :is="item.icon" />
        {{ item.name }}
      </a>
    </nav>
  </aside>
</template>

<script setup>
import {
  UserIcon,
  ShoppingBagIcon,
  HeartIcon,
  LogOutIcon,
} from "lucide-vue-next";

const navItems = [
  { name: "Profile", href: "#", icon: UserIcon },
  { name: "Orders", href: "#", icon: ShoppingBagIcon },
  { name: "Wishlist", href: "#", icon: HeartIcon },
  { name: "Logout", href: "#", icon: LogOutIcon },
];
</script>

<style scoped>
.sidebar {
  width: 250px;
  background: var(--secondary-color);
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
}

.sidebar-nav {
  margin-top: 1.5rem;
}

.nav-item {
  display: flex;
  align-items: center;
  padding: 0.75rem 50px;
  color: var(--dark-color);
  text-decoration: none;
  transition: background-color 0.2s, color 0.2s;
}

.nav-item svg {
  width: 1.25rem;
  height: 1.25rem;
  margin-right: 0.75rem;
}
</style>
