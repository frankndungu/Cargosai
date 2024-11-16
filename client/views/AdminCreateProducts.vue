<template>
  <div class="admin-create-product">
    <h2>Add a New Product</h2>

    <form @submit.prevent="handleSubmit">
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
          step="0.01"
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
        <label for="vendor">Vendor Name</label>
        <input
          id="vendor"
          v-model="product.vendor_name"
          placeholder="Vendor name"
        />
        <span v-if="errors.vendor_name" class="error-msg">{{
          errors.vendor_name
        }}</span>
      </div>

      <div class="form-field">
        <label for="vendorEmail">Vendor Email</label>
        <input
          id="vendorEmail"
          v-model="product.vendor_email"
          placeholder="vendor@example.com"
          type="email"
        />
        <span v-if="errors.vendor_email" class="error-msg">{{
          errors.vendor_email
        }}</span>
      </div>

      <div class="form-field">
        <label for="vendorLocation">Vendor Location</label>
        <input
          id="vendorLocation"
          v-model="product.vendor_location"
          placeholder="Location"
        />
        <span v-if="errors.vendor_location" class="error-msg">{{
          errors.vendor_location
        }}</span>
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
          type="number"
          step="0.01"
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
            accept="image/jpeg,image/png,image/gif,image/webp,image/avif"
            hidden
          />
        </div>
        <div v-if="mainImagePreview" class="file-preview">
          <img
            :src="mainImagePreview"
            alt="Main Image Preview"
            class="preview-img-main"
          />
          <button @click="removeMainImage" class="remove-btn-main">
            Remove
          </button>
        </div>
        <span v-if="errors.main_image" class="error-msg">{{
          errors.main_image
        }}</span>
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
            accept="image/jpeg,image/png,image/gif,image/webp,image/avif"
            class="file-input"
            hidden
          />
        </div>
        <div v-if="thumbnailPreviews.length" class="file-preview">
          <ul class="thumbnail-list">
            <li
              v-for="(preview, index) in thumbnailPreviews"
              :key="index"
              class="file-item"
            >
              <img
                :src="preview"
                alt="Thumbnail Preview"
                class="preview-img-thumbnail"
              />
              <button @click="removeThumbnail(index)" class="remove-btn">
                Remove
              </button>
            </li>
          </ul>
        </div>
        <span v-if="errors.thumbnails" class="error-msg">{{
          errors.thumbnails
        }}</span>
      </div>

      <div v-if="submitError" class="error-msg global-error">
        {{ submitError }}
      </div>

      <!-- Add Product Button -->
      <button type="submit" :disabled="isSubmitting">
        {{ isSubmitting ? "Adding product..." : "Add product" }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const router = useRouter();
const errors = ref({});
const submitError = ref("");
const isSubmitting = ref(false);
const mainImagePreview = ref(null);
const thumbnailPreviews = ref([]);
const mainImageFile = ref(null);
const thumbnailFiles = ref([]);

const product = reactive({
  name: "",
  stock: "",
  price: "",
  vendor_name: "",
  vendor_email: "",
  vendor_location: "",
  material: "",
  dimensions: "",
  weight: "",
  description: "",
});

const triggerMainImageInput = () => {
  document.getElementById("mainImage").click();
};

const handleMainImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    mainImageFile.value = file;
    const reader = new FileReader();
    reader.onload = () => (mainImagePreview.value = reader.result);
    reader.readAsDataURL(file);
  }
};

const removeMainImage = () => {
  mainImageFile.value = null;
  mainImagePreview.value = null;
};

const triggerFileInput = () => {
  document.getElementById("productThumbnails").click();
};

const handleThumbnailUpload = (event) => {
  const files = Array.from(event.target.files);
  thumbnailFiles.value = [...thumbnailFiles.value, ...files];

  files.forEach((file) => {
    const reader = new FileReader();
    reader.onload = () => thumbnailPreviews.value.push(reader.result);
    reader.readAsDataURL(file);
  });
};

const removeThumbnail = (index) => {
  thumbnailPreviews.value.splice(index, 1);
  thumbnailFiles.value.splice(index, 1);
};

const handleSubmit = async () => {
  try {
    isSubmitting.value = true;
    errors.value = {};
    submitError.value = "";

    const formData = new FormData();

    // Append all product data
    Object.keys(product).forEach((key) => {
      if (product[key] !== "") {
        formData.append(key, product[key]);
      }
    });

    // Append main image
    if (mainImageFile.value) {
      formData.append("main_image", mainImageFile.value);
    }

    // Append thumbnails
    thumbnailFiles.value.forEach((file) => {
      formData.append("thumbnails[]", file);
    });

    const token = `Bearer ${localStorage.getItem("token")}`;

    const response = await axios.post(
      "http://localhost:8000/api/products",
      formData,
      {
        headers: {
          "Content-Type": "multipart/form-data",
          Authorization: token,
        },
      }
    );

    if (response.status === 201) {
      router.push("/admin/products");
    }
  } catch (error) {
    console.error("Error details:", error.response?.data || error);

    if (error.response?.status === 422) {
      errors.value = error.response.data.errors;
    } else if (error.response?.status === 403) {
      submitError.value = "You do not have permission to create products.";
    } else if (error.response?.data?.message) {
      submitError.value = error.response.data.message;
    } else {
      submitError.value =
        "An error occurred while creating the product. Please try again.";
    }
  } finally {
    isSubmitting.value = false;
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

.error-msg {
  color: #dc2626;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.global-error {
  margin-bottom: 1rem;
  padding: 0.75rem;
  background-color: #fee2e2;
  border: 1px solid #dc2626;
  border-radius: 4px;
}

.upload-area {
  margin-top: 10px;
  padding: 20px;
  border: 2px dashed #ccc;
  border-radius: 4px;
  text-align: center;
  cursor: pointer;
  transition: border-color 0.3s ease;
}

.upload-area:hover {
  border-color: #666;
}

.upload-btn {
  margin-top: 10px;
  padding: 8px 15px;
  background: #1a1a1a;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.file-preview {
  margin-top: 10px;
}

.thumbnail-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  gap: 1rem;
  list-style: none;
  padding: 0;
}

.file-item {
  position: relative;
  border: 1px solid #ccc;
  border-radius: 4px;
  padding: 0.5rem;
}

.preview-img-main {
  width: 200px;
  height: auto;
  position: relative;
  border: 1px solid #ccc;
  border-radius: 4px;
  padding: 0.5rem;
}

.preview-img-thumbnail {
  width: 150px;
  height: auto;
  border-radius: 4px;
}

.remove-btn-main {
  position: relative;
  top: 0.25rem;
  right: 0.25rem;
  background: #dc2626;
  color: white;
  border: none;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.75rem;
}

.remove-btn {
  position: absolute;
  top: 0.25rem;
  right: 0.25rem;
  background: #dc2626;
  color: white;
  border: none;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.75rem;
}

button[type="submit"] {
  margin: 20px 0;
  padding: 12px 24px;
  background: #1a1a1a;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1rem;
  transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
  background-color: #333;
}

button[type="submit"]:disabled {
  background-color: #666;
  cursor: not-allowed;
}
</style>
