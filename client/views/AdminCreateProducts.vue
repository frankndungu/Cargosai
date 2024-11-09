<template>
  <div class="admin-create-product">
    <h2>Add a new product</h2>

    <!-- Product Name -->
    <label for="productName">Product Name</label>
    <input
      id="productName"
      v-model="product.name"
      placeholder="Type product name"
    />
    <span v-if="errors.name" class="error-msg">{{ errors.name }}</span>

    <div class="form-grid">
      <!-- Stock -->
      <div>
        <label for="stock">Stock</label>
        <input
          id="stock"
          v-model="product.stock"
          placeholder="10 units"
          type="number"
        />
        <span v-if="errors.stock" class="error-msg">{{ errors.stock }}</span>
      </div>

      <!-- Price -->
      <div>
        <label for="price">Price</label>
        <input
          id="price"
          v-model="product.price"
          placeholder="$100"
          type="number"
        />
        <span v-if="errors.price" class="error-msg">{{ errors.price }}</span>
      </div>

      <!-- Vendor -->
      <div>
        <label for="vendor">Vendor</label>
        <input
          id="vendor"
          v-model="product.vendor"
          placeholder="Vendor store"
        />
      </div>

      <!-- Vendor Email -->
      <div>
        <label for="vendorEmail">Vendor Email</label>
        <input
          id="vendorEmail"
          v-model="product.vendorEmail"
          placeholder="Vendor email"
        />
        <span v-if="errors.vendorEmail" class="error-msg">{{
          errors.vendorEmail
        }}</span>
      </div>

      <!-- Vendor Location -->
      <div>
        <label for="vendorLocation">Vendor Location</label>
        <input
          id="vendorLocation"
          v-model="product.vendorLocation"
          placeholder="Nairobi, Kenya"
        />
      </div>

      <!-- Material -->
      <div>
        <label for="material">Material</label>
        <input id="material" v-model="product.material" placeholder="Brass" />
      </div>

      <!-- Dimensions -->
      <div>
        <label for="dimensions">Dimensions</label>
        <input
          id="dimensions"
          v-model="product.dimensions"
          placeholder="3cm x 4cm"
        />
      </div>

      <!-- Weight -->
      <div>
        <label for="weight">Weight</label>
        <input id="weight" v-model="product.weight" placeholder="1.25kg" />
      </div>
    </div>

    <!-- Description -->
    <label for="description">Description</label>
    <textarea
      id="description"
      v-model="product.description"
      placeholder="Write your product description here ..."
    ></textarea>
    <span v-if="errors.description" class="error-msg">{{
      errors.description
    }}</span>

    <!-- Product Images -->
    <label for="productImages">Product Images</label>
    <div class="upload-area">
      <p>Click to upload or drag and drop<br />Max. File Size: 30MB</p>
      <button type="button" class="upload-btn">Choose Files</button>
      <input
        id="productImages"
        type="file"
        multiple
        @change="handleFileUpload"
        ref="fileInput"
        class="file-input"
      />
    </div>

    <!-- Preview Selected Files -->
    <div v-if="product.images.length" class="file-preview">
      <h3>Selected Files:</h3>
      <ul>
        <li v-for="(file, index) in product.images" :key="index">
          {{ file.name }}
        </li>
      </ul>
    </div>

    <!-- Add Product Button -->
    <button @click="addProduct">Add product</button>
  </div>
</template>

<script setup>
import { ref, watchEffect } from "vue";
import { useStore } from "vuex";

// Access Vuex store
const store = useStore();

// Initialize product data from Vuex store if available
const product = ref({
  name: store.state.product.name || "",
  stock: store.state.product.stock || "",
  price: store.state.product.price || "",
  vendor: store.state.product.vendor || "",
  vendorEmail: store.state.product.vendorEmail || "",
  vendorLocation: store.state.product.vendorLocation || "",
  material: store.state.product.material || "",
  dimensions: store.state.product.dimensions || "",
  weight: store.state.product.weight || "",
  description: store.state.product.description || "",
  images: store.state.product.images || [], // Ensure we get the images from Vuex
});

// Watch product data and persist changes to Vuex store
watchEffect(() => {
  store.commit("SET_PRODUCT", product.value);
});

// Validation errors
const errors = ref({
  name: "",
  stock: "",
  price: "",
  vendorEmail: "",
  description: "",
});

const handleFileUpload = (event) => {
  const files = Array.from(event.target.files);
  // Add files to the product's images array
  product.value.images = [...product.value.images, ...files];
};

const addProduct = () => {
  // Reset errors
  errors.value = {
    name: "",
    stock: "",
    price: "",
    vendorEmail: "",
    description: "",
  };

  let valid = true;

  // Validate product name
  if (!product.value.name) {
    errors.value.name = "Product name is required.";
    valid = false;
  }

  // Validate stock (must be a number and required)
  if (!product.value.stock || isNaN(product.value.stock)) {
    errors.value.stock = "Stock must be a valid number.";
    valid = false;
  }

  // Validate price (must be a positive number)
  if (!product.value.price || product.value.price <= 0) {
    errors.value.price = "Price must be a positive number.";
    valid = false;
  }

  // Validate vendor email
  const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  if (
    !product.value.vendorEmail ||
    !emailPattern.test(product.value.vendorEmail)
  ) {
    errors.value.vendorEmail = "Please enter a valid email address.";
    valid = false;
  }

  // Validate description
  if (!product.value.description) {
    errors.value.description = "Description is required.";
    valid = false;
  }

  // If form is valid, proceed with adding the product
  if (valid) {
    console.log("Product added:", product.value);
    // Add product logic here (e.g., make an API call)
  }
};
</script>

<style scoped>
.admin-create-product {
  padding: 0 50px;
}

h2 {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 20px;
}

label {
  display: block;
  font-weight: bold;
  margin-top: 15px;
}

input,
textarea {
  width: 100%;
  padding: 8px;
  margin-top: 5px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

textarea {
  min-height: 100px;
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
}

.upload-area {
  margin-top: 10px;
  padding: 20px;
  border: 2px dashed #ccc;
  text-align: center;
  color: #888;
  border-radius: 4px;
  cursor: pointer;
  position: relative;
}

.upload-area p {
  margin: 0;
}

.upload-btn {
  display: inline-block;
  margin-top: 10px;
  padding: 8px 15px;
  background: var(--dark-tint);
  color: var(--background-color);
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.upload-btn:hover {
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0);
}

.upload-btn:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}

.file-input {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  cursor: pointer;
}

.file-preview {
  margin-top: 20px;
}

.file-preview h3 {
  font-size: 18px;
  font-weight: bold;
}

.file-preview ul {
  list-style: none;
  padding: 0;
}

.file-preview li {
  margin-top: 5px;
}

button {
  margin: 20px 0;
  padding: 10px 15px;
  background: var(--dark-tint);
  color: var(--background-color);
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0);
}

button:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}
</style>
