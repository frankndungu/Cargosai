<template>
  <div class="admin-products">
    <div class="top-actions">
      <input type="text" class="search-bar" placeholder="Search for products" />
      <div class="right-actions">
        <button class="add-product">Add product</button>
        <div class="dropdown">
          <button class="actions-button">
            Actions
            <i class="fas fa-chevron-down"></i>
          </button>
          <div class="dropdown-content">
            <a href="#">Delete all</a>
          </div>
        </div>
      </div>
    </div>

    <div class="product-table">
      <div class="table-header">
        <div class="column-title">Product Name</div>
        <div class="column-title">Stock</div>
        <div class="column-title">Vendor</div>
        <div class="column-title">Price</div>
        <div class="column-title">Actions</div>
      </div>

      <div class="table-body">
        <div
          class="table-row"
          v-for="(product, index) in paginatedProducts"
          :key="product.id"
        >
          <div class="column">{{ product.name }}</div>
          <div class="column">{{ product.stock }}</div>
          <div class="column">{{ product.vendor }}</div>
          <div class="column">{{ product.price }}</div>
          <div class="column">
            <div
              class="actions-dropdown"
              :class="{ active: activeDropdowns[index] }"
              ref="dropdowns"
            >
              <button class="actions-button" @click="toggleDropdown(index)">
                <i class="fas fa-ellipsis-h"></i>
              </button>
              <div class="dropdown-content">
                <a href="#">Edit</a>
                <a href="#">Delete</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="bottom-actions">
      <div class="pagination-info">Showing 1-10 of 1000</div>
      <div class="pagination-buttons">
        <button
          v-for="page in totalPages"
          :key="page"
          @click="currentPage = page"
        >
          {{ page }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, reactive, onMounted, onUnmounted } from "vue";

const products = ref([
  { id: 1, name: "Product 1", stock: 10, vendor: "Vendor A", price: 9.99 },
  { id: 2, name: "Product 2", stock: 5, vendor: "Vendor B", price: 14.99 },
]);

const itemsPerPage = 10;
const currentPage = ref(1);

const totalPages = computed(() =>
  Math.ceil(products.value.length / itemsPerPage)
);
const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return products.value.slice(start, end);
});

const activeDropdowns = reactive({});

const toggleDropdown = (index) => {
  // Close all dropdowns before opening the current one
  Object.keys(activeDropdowns).forEach((key) => {
    if (key !== String(index)) {
      activeDropdowns[key] = false;
    }
  });
  // Toggle the current dropdown
  activeDropdowns[index] = !activeDropdowns[index];
};

// Add clickaway functionality
const dropdownRef = ref(null);
const handleClickOutside = (event) => {
  // Close all dropdowns if the click is outside any dropdown
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    Object.keys(activeDropdowns).forEach((key) => {
      activeDropdowns[key] = false;
    });
  }
};

onMounted(() => {
  document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener("click", handleClickOutside);
});
</script>

<style scoped>
.admin-products {
  padding: 0 50px;
}

.top-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.search-bar {
  padding: 8px 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  width: 400px;
}

.right-actions {
  display: flex;
  align-items: center;
}

.add-product {
  background: var(--dark-tint);
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  margin-right: 8px;
}

.actions-button {
  display: flex;
  align-items: center;
  padding: 8px 16px;
  background: #f1f1f1;
  border: 1px solid #ccc;
  border-radius: 4px;
  cursor: pointer;
}

.actions-button .fa-chevron-down {
  margin-left: 8px;
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f1f1f1;
  min-width: 120px;
  box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {
  background-color: #ddd;
}

.dropdown:hover .dropdown-content {
  display: block;
}

.product-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.table-header,
.table-row {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
  border-bottom: 1px solid #ccc;
  padding: 12px 0;
}

.column-title {
  padding: 0 8px;
  font-weight: bold;
}

.column {
  padding: 0 8px;
}

.actions-dropdown {
  position: relative;
  display: inline-block;
}

.actions-dropdown .dropdown-content {
  right: 0;
  display: none;
}

.actions-dropdown.active .dropdown-content {
  display: block;
}

.actions-dropdown .actions-button {
  background-color: transparent;
  border: none;
  padding: 0;
}

.bottom-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 20px;
}

.pagination-buttons {
  display: flex;
}

.pagination-buttons button {
  padding: 8px 12px;
  margin: 0 4px;
  border: 1px solid #ccc;
  background: #f1f1f1;
  cursor: pointer;
}

.pagination-info {
  font-size: 14px;
  color: #666;
}
</style>
