<template>
  <div class="admin-products">
    <div class="top-actions">
      <input
        type="text"
        class="search-bar"
        placeholder="Search for products"
        v-model="searchQuery"
        @input="searchProducts"
      />
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
          v-for="(product, index) in products"
          :key="product.id"
        >
          <div class="column">{{ product.name }}</div>
          <div class="column">{{ product.stock }}</div>
          <div class="column">{{ product.vendor_name }}</div>
          <div class="column">${{ product.price }}</div>
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
      <div class="pagination-info">
        Showing {{ products.length }} of {{ totalProducts }} products
      </div>
      <div class="pagination-buttons">
        <button
          class="pagination-button"
          @click="previousPage"
          :disabled="currentPage === 1"
        >
          &laquo; Previous
        </button>
        <span class="pagination-page-info"
          >Page {{ currentPage }} of {{ totalPages }}</span
        >
        <button
          class="pagination-button"
          @click="nextPage"
          :disabled="currentPage === totalPages"
        >
          Next &raquo;
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { useStore } from "vuex";
import axios from "axios";

const store = useStore();

const currentPage = computed(() => store.getters.currentPage);
const totalPages = computed(() => store.getters.totalPages);
const totalProducts = computed(() => store.getters.totalProducts);

const products = ref([]);
const searchQuery = ref("");

// Fetch products from API
const fetchProducts = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_API_URL}/products`,
      {
        params: {
          page: currentPage.value,
          per_page: 16, // set to 16 items per page
          search: searchQuery.value,
        },
      }
    );

    products.value = response.data.products.data;
    store.dispatch("setTotalPages", response.data.products.last_page);
    store.dispatch("setTotalProducts", response.data.total);
  } catch (error) {
    console.error("Error fetching products:", error);
  }
};

// Pagination methods
const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    store.dispatch("setCurrentPage", currentPage.value + 1);
    fetchProducts();
  }
};

const previousPage = () => {
  if (currentPage.value > 1) {
    store.dispatch("setCurrentPage", currentPage.value - 1);
    fetchProducts();
  }
};

// Search function
const searchProducts = () => {
  store.dispatch("setCurrentPage", 1); // reset to first page on new search
  fetchProducts();
};

// Toggle dropdown visibility
const activeDropdowns = ref({});
const toggleDropdown = (index) => {
  Object.keys(activeDropdowns.value).forEach((key) => {
    if (key !== String(index)) activeDropdowns.value[key] = false;
  });
  activeDropdowns.value[index] = !activeDropdowns.value[index];
};

// Initialize fetch on mount
onMounted(() => {
  fetchProducts();
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
  color: var(--background-color);
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  margin-right: 8px;
}

.add-product:hover {
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0);
}

.add-product:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
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
  color: var(--dark-color);
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {
  background: #ddd;
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
  align-items: center;
  gap: 8px;
}

.pagination-button {
  padding: 8px 14px;
  border: 1px solid #ccc;
  background: var(--dark-tint);
  color: var(--background-color);
  font-weight: 500;
  cursor: pointer;
  border-radius: 4px;
  transition: all 0.3s ease;
}

.pagination-button:hover {
  background: var(--dark-tint);
  color: var(--dark-color);
  border-color: var(--dark-tint);
}

.pagination-button:disabled {
  background: #ddd;
  color: #aaa;
  cursor: not-allowed;
  border-color: #ddd;
}

.pagination-page-info {
  margin: 0 10px;
  color: #555;
  font-weight: 500;
}
</style>
