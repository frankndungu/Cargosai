<template>
  <div class="admin-create-product">
    <h2>Add a New Product</h2>

    <form @submit.prevent="addProduct">
      <!-- Product Form Fields -->
      <div class="form-field">
        <label for="productName">Product Name</label>
        <input
          id="productName"
          v-model="product.name"
          placeholder="Enter product name"
        />
        <span v-if="errors.name" class="error-msg">{{ errors.name }}</span>
      </div>

      <div class="form-field">
        <label for="stock">Stock</label>
        <input
          id="stock"
          v-model="product.stock"
          placeholder="Available quantity"
          type="number"
        />
        <span v-if="errors.stock" class="error-msg">{{ errors.stock }}</span>
      </div>

      <div class="form-field">
        <label for="price">Price</label>
        <input
          id="price"
          v-model="product.price"
          placeholder="0"
          type="number"
        />
        <span v-if="errors.price" class="error-msg">{{ errors.price }}</span>
      </div>

      <div class="form-field">
        <label for="description">Description</label>
        <textarea
          id="description"
          v-model="product.description"
          placeholder="Product description"
        ></textarea>
        <span v-if="errors.description" class="error-msg">{{
          errors.description
        }}</span>
      </div>

      <div class="form-field">
        <label for="vendor">Vendor</label>
        <input id="vendor" v-model="product.vendor" placeholder="Vendor name" />
      </div>

      <div class="form-field">
        <label for="vendorEmail">Vendor Email</label>
        <input
          id="vendorEmail"
          v-model="product.vendorEmail"
          placeholder="vendor@example.com"
        />
        <span v-if="errors.vendorEmail" class="error-msg">{{
          errors.vendorEmail
        }}</span>
      </div>

      <div class="form-field">
        <label for="vendorLocation">Vendor Location</label>
        <input
          id="vendorLocation"
          v-model="product.vendorLocation"
          placeholder="Location"
        />
      </div>

      <div class="form-field">
        <label for="material">Material</label>
        <input
          id="material"
          v-model="product.material"
          placeholder="Product material"
        />
      </div>

      <div class="form-field">
        <label for="dimensions">Dimensions</label>
        <input
          id="dimensions"
          v-model="product.dimensions"
          placeholder="Product dimensions"
        />
      </div>

      <div class="form-field">
        <label for="weight">Weight</label>
        <input
          id="weight"
          v-model="product.weight"
          placeholder="Weight in kg"
        />
      </div>

      <!-- Main Image Upload -->
      <div class="form-field">
        <label for="mainImage">Main Image</label>
        <div class="upload-area" @click="triggerMainImageInput">
          <h4>Click to upload or drag and drop</h4>
          <p>Max. File Size: 30MB</p>
          <button type="button" class="upload-btn">Choose Main Image</button>
          <input
            id="mainImage"
            type="file"
            @change="handleMainImageUpload"
            ref="mainImageInput"
            class="file-input"
            hidden
          />
        </div>
        <div v-if="product.mainImage" class="file-preview">
          <img
            :src="product.mainImage"
            alt="Main Image Preview"
            class="preview-img"
          />
          <button @click="removeMainImage" class="remove-btn">Remove</button>
        </div>
      </div>

      <!-- Thumbnails Upload -->
      <div class="form-field">
        <label for="productThumbnails">Product Thumbnails</label>
        <div class="upload-area" @click="triggerFileInput">
          <h4>Click to upload or drag and drop</h4>
          <p>Max. File Size: 30MB</p>
          <button type="button" class="upload-btn">Choose Thumbnails</button>
          <input
            id="productThumbnails"
            type="file"
            multiple
            @change="handleThumbnailUpload"
            ref="fileInput"
            class="file-input"
            hidden
          />
        </div>
        <div v-if="product.images.length" class="file-preview">
          <ul>
            <li
              v-for="(image, index) in product.images"
              :key="index"
              class="file-item"
            >
              <img :src="image" alt="Thumbnail Preview" class="preview-img" />
              <button @click="removeThumbnail(index)" class="remove-btn">
                Remove
              </button>
            </li>
          </ul>
        </div>
      </div>

      <!-- Add Product Button -->
      <button type="submit">Add product</button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { useStore } from "vuex";
import axios from "axios";

const store = useStore();
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
  mainImage: null,
  images: [],
});
const errors = ref({});

const triggerMainImageInput = () => {
  const mainImageInput = document.getElementById("mainImage");
  if (mainImageInput) mainImageInput.click();
};

const handleMainImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = () => {
      product.value.mainImage = reader.result;
      saveProductData(); // Save data to localStorage or Vuex
    };
    reader.readAsDataURL(file);
  }
};

const removeMainImage = () => {
  product.value.mainImage = null;
  saveProductData(); // Save data to localStorage or Vuex
};

const triggerFileInput = () => {
  const fileInput = document.getElementById("productThumbnails");
  if (fileInput) fileInput.click();
};

const handleThumbnailUpload = (event) => {
  const files = Array.from(event.target.files);
  if (files.length > 0) {
    files.forEach((file) => {
      const reader = new FileReader();
      reader.onload = () => {
        product.value.images.push(reader.result); // Add image to thumbnails array
        saveProductData(); // Save data to localStorage or Vuex
      };
      reader.readAsDataURL(file);
    });
  }
};

const removeThumbnail = (index) => {
  product.value.images.splice(index, 1);
  saveProductData(); // Save data to localStorage or Vuex
};

const saveProductData = () => {
  // Save the entire product object to localStorage or Vuex
  localStorage.setItem("productData", JSON.stringify(product.value));
};

const loadProductData = () => {
  const savedProductData = localStorage.getItem("productData");
  if (savedProductData) {
    product.value = JSON.parse(savedProductData);
  }
};

// Automatically save the product data whenever any form field changes
watch(
  product,
  () => {
    saveProductData();
  },
  { deep: true }
);

const addProduct = async () => {
  errors.value = {};
  let valid = true;

  // Validation checks
  if (!product.value.name) errors.value.name = "Product name is required.";
  if (!product.value.stock) errors.value.stock = "Stock is required.";
  if (!product.value.price) errors.value.price = "Price is required.";
  if (!product.value.vendorEmail)
    errors.value.vendorEmail = "Vendor email is required.";
  if (!product.value.description)
    errors.value.description = "Description is required.";
  if (!valid) return;

  // Prepare form data
  const formData = new FormData();
  formData.append("name", product.value.name);
  formData.append("stock", product.value.stock);
  formData.append("price", product.value.price);
  formData.append("vendor", product.value.vendor);
  formData.append("vendor_email", product.value.vendorEmail);
  formData.append("vendor_location", product.value.vendorLocation);
  formData.append("material", product.value.material);
  formData.append("dimensions", product.value.dimensions);
  formData.append("weight", product.value.weight);
  formData.append("description", product.value.description);

  if (product.value.mainImage) {
    formData.append("main_image", product.value.mainImage);
  }
  product.value.images.forEach((image, index) =>
    formData.append(`thumbnails[${index}]`, image)
  );

  // Send data to backend
  try {
    const response = await axios.post(
      "http://localhost:8000/api/products",
      formData,
      {
        headers: {
          Authorization: `Bearer ${store.state.user.token}`,
          "Content-Type": "multipart/form-data",
        },
      }
    );

    console.log("Product added:", response.data);
    // Clear localStorage or Vuex after successful submission
    localStorage.removeItem("productData");
  } catch (error) {
    console.error("Error adding product:", error);
  }
};

onMounted(() => {
  loadProductData(); // Load product data from localStorage when the component mounts
});
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

form {
  display: flex;
  flex-direction: column;
}

.form-field {
  margin-bottom: 20px;
}

label {
  display: block;
  font-weight: bold;
  margin-bottom: 5px;
}

input,
textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

textarea {
  min-height: 100px;
  resize: vertical;
}

.upload-area {
  margin-top: 10px;
  padding: 10px;
  border: 2px dashed #ccc;
  border-radius: 4px;
  text-align: center;
  cursor: pointer;
}

.upload-area h4 {
  font-weight: 500;
  font-size: medium;
}

.upload-area p {
  color: var(--stat-name);
  font-size: small;
}

.upload-btn {
  margin-top: 10px;
  padding: 8px 15px;
  background: var(--dark-tint);
  color: var(--background-color);
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.file-preview {
  display: flex;
  align-items: center;
  margin-top: 10px;
}

.preview-img {
  max-width: 100px;
  margin-right: 10px;
  border-radius: 4px;
}

.remove-btn {
  background: var(--cancel-color);
  color: var(--background-color);
  border: none;
  padding: 5px 10px;
  cursor: pointer;
  border-radius: 4px;
  margin-left: 10px; /* Added margin to space the button from the image */
}

button[type="submit"] {
  margin: 20px 0;
  padding: 10px 15px;
  max-width: 200px;
  width: 100%;
  background: var(--dark-tint);
  color: var(--background-color);
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button[type="submit"]:hover {
  background-color: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

button[type="submit"]:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}
</style>
