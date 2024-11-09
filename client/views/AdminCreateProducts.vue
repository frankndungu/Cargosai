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

    <div class="form-grid">
      <!-- Stock -->
      <div>
        <label for="stock">Stock</label>
        <input id="stock" v-model="product.stock" placeholder="10 units" />
      </div>

      <!-- Price -->
      <div>
        <label for="price">Price</label>
        <input id="price" v-model="product.price" placeholder="$100" />
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

    <!-- Product Images -->
    <label for="productImages">Product Images</label>
    <div class="upload-area" @click="triggerFileInput">
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
import { ref } from "vue";

const product = ref({
  name: "",
  stock: "",
  price: "",
  vendor: "",
  vendorEmail: "",
  vendorLocation: "",
  material: "",
  dimensions: "",
  weight: "",
  description: "",
  images: [],
});

const fileInput = ref(null);

const handleFileUpload = (event) => {
  product.value.images = Array.from(event.target.files);
};

const triggerFileInput = () => {
  fileInput.value.click();
};

const addProduct = () => {
  // Logic to handle adding the product
  console.log(product.value);
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
  margin-top: 20px;
  padding: 10px 15px;
  background: var(--dark-tint);
  color: var(--background-color);
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
</style>
