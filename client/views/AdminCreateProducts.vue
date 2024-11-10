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

    <!-- Main Product Image -->
    <label for="mainImage">Main Image</label>
    <div class="upload-area" @click="triggerMainImageInput">
      <p>Click to upload the main image<br />Max. File Size: 30MB</p>
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
      <ul>
        <li class="file-item">
          <img
            :src="product.mainImage"
            alt="Main Image Preview"
            class="preview-img"
          />
          <button @click="removeMainImage">Remove</button>
        </li>
      </ul>
    </div>

    <!-- Product Thumbnails -->
    <label for="productThumbnails">Product Thumbnails</label>
    <div class="upload-area" @click="triggerFileInput">
      <p>Click to upload or drag and drop<br />Max. File Size: 30MB</p>
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
          <button @click="removeThumbnail(index)">Remove</button>
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

const store = useStore();

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
  mainImage: store.state.product.mainImage || null,
  images: store.state.product.images || [],
});

watchEffect(() => {
  store.commit("SET_PRODUCT", product.value);
});

const errors = ref({
  name: "",
  stock: "",
  price: "",
  vendorEmail: "",
  description: "",
});

const triggerMainImageInput = () => {
  const mainImageInput = document.getElementById("mainImage");
  if (mainImageInput) {
    mainImageInput.click();
  }
};

const handleMainImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = () => {
      product.value.mainImage = reader.result;
      store.commit("SET_MAIN_IMAGE", reader.result);
    };
    reader.readAsDataURL(file);
  }
};

const removeMainImage = () => {
  product.value.mainImage = null;
  store.commit("REMOVE_MAIN_IMAGE");
};

const triggerFileInput = () => {
  const fileInput = document.getElementById("productThumbnails");
  if (fileInput) {
    fileInput.click();
  }
};

const handleThumbnailUpload = (event) => {
  const files = Array.from(event.target.files);
  files.forEach((file) => {
    store.dispatch("uploadImage", file);
  });
};

const removeThumbnail = (index) => {
  store.dispatch("removeImage", index);
};

const addProduct = () => {
  errors.value = {
    name: "",
    stock: "",
    price: "",
    vendorEmail: "",
    description: "",
  };

  let valid = true;

  if (!product.value.name) {
    errors.value.name = "Product name is required.";
    valid = false;
  }

  if (!product.value.stock || isNaN(product.value.stock)) {
    errors.value.stock = "Stock must be a valid number.";
    valid = false;
  }

  if (!product.value.price || product.value.price <= 0) {
    errors.value.price = "Price must be a positive number.";
    valid = false;
  }

  const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  if (
    !product.value.vendorEmail ||
    !emailPattern.test(product.value.vendorEmail)
  ) {
    errors.value.vendorEmail = "Please enter a valid email address.";
    valid = false;
  }

  if (!product.value.description) {
    errors.value.description = "Description is required.";
    valid = false;
  }

  if (valid) {
    console.log("Product added:", product.value);
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

.upload-btn {
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

.file-preview ul {
  list-style: none;
  padding: 0;
}

.file-item {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top: 10px;
}

.preview-img {
  width: 75px;
  height: auto;
  border-radius: 4px;
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
