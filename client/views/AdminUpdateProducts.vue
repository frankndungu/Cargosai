<template>
  <div class="product-update-form">
    <h2>Update Product</h2>

    <!-- Loading State -->
    <div v-if="isLoading" class="loading-state">
      <div class="spinner"></div>
      <p>Loading product details...</p>
    </div>

    <!-- Error Alert -->
    <div v-if="error" class="error-alert">
      {{ error }}
      <button @click="fetchProduct" class="retry-button">Retry</button>
    </div>

    <!-- Success Message -->
    <div v-if="successMessage" class="success-alert">
      {{ successMessage }}
    </div>

    <!-- Form only shows when product is loaded -->
    <form v-if="product" @submit.prevent="updateProduct" class="form">
      <div class="form-group">
        <label for="name">Product Name:</label>
        <input
          id="name"
          v-model="formData.name"
          type="text"
          maxlength="255"
          required
        />
      </div>

      <div class="form-group">
        <label for="price">Price:</label>
        <input
          id="price"
          v-model.number="formData.price"
          type="number"
          step="0.01"
          required
        />
      </div>

      <div class="form-group">
        <label for="description">Description:</label>
        <textarea
          id="description"
          v-model="formData.description"
          rows="4"
        ></textarea>
      </div>

      <div class="form-group">
        <label for="stock">Stock:</label>
        <input
          id="stock"
          v-model.number="formData.stock"
          type="number"
          min="0"
          required
        />
      </div>

      <div class="form-group">
        <label for="dimensions">Dimensions:</label>
        <input id="dimensions" v-model="formData.dimensions" type="text" />
      </div>

      <div class="form-group">
        <label for="weight">Weight:</label>
        <input
          id="weight"
          v-model.number="formData.weight"
          type="number"
          step="0.01"
        />
      </div>

      <div class="form-group">
        <label for="material">Material:</label>
        <input id="material" v-model="formData.material" type="text" />
      </div>

      <div class="form-group">
        <label for="vendor_name">Vendor Name:</label>
        <input
          id="vendor_name"
          v-model="formData.vendor_name"
          type="text"
          maxlength="255"
          required
        />
      </div>

      <div class="form-group">
        <label for="vendor_email">Vendor Email:</label>
        <input
          id="vendor_email"
          v-model="formData.vendor_email"
          type="email"
          maxlength="255"
          required
        />
      </div>

      <div class="form-group">
        <label for="vendor_location">Vendor Location:</label>
        <input
          id="vendor_location"
          v-model="formData.vendor_location"
          type="text"
          maxlength="255"
          required
        />
      </div>

      <div class="form-actions">
        <button type="button" class="cancel-button" @click="resetForm">
          Reset
        </button>
        <button type="submit" :disabled="isSubmitting">
          {{ isSubmitting ? "Updating..." : "Update Product" }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, watch } from "vue";
import axios from "axios";

const props = defineProps({
  id: {
    // Changed from productId to id
    type: [String, Number],
    required: true,
    validator: (value) => value !== undefined && value !== null && value !== "",
  },
});

const emit = defineEmits(["product-updated"]);

const product = ref(null);
const formData = ref({
  name: "",
  price: 0,
  description: "",
  stock: 0,
  dimensions: "",
  weight: null,
  material: "",
  vendor_name: "",
  vendor_email: "",
  vendor_location: "",
});

const error = ref("");
const successMessage = ref("");
const isLoading = ref(false);
const isSubmitting = ref(false);

// Fetch product data
const fetchProduct = async () => {
  // Check if id is valid before making the request
  if (!props.id) {
    error.value = "Invalid product ID";
    return;
  }

  error.value = "";
  isLoading.value = true;

  try {
    const response = await axios.get(
      `${import.meta.env.VITE_API_URL}/products/${props.id}`
    );
    product.value = response.data;
    // Initialize form data with product values
    formData.value = { ...response.data };
  } catch (err) {
    if (err.response?.status === 404) {
      error.value = "Product not found";
    } else if (err.response?.status === 500) {
      error.value = "Server error: Please check if product ID is valid";
    } else {
      error.value = "Failed to load product data";
    }
    console.error("Error fetching product:", err);
  } finally {
    isLoading.value = false;
  }
};

// Watch for changes in id
watch(
  () => props.id,
  (newId, oldId) => {
    if (newId && newId !== oldId) {
      fetchProduct();
    }
  },
  { immediate: true }
);

// Reset form to initial product data
const resetForm = () => {
  if (product.value) {
    formData.value = { ...product.value };
  }
  successMessage.value = "";
  error.value = "";
};

const updateProduct = async () => {
  if (!props.id) {
    error.value = "Invalid product ID";
    return;
  }

  error.value = "";
  successMessage.value = "";
  isSubmitting.value = true;

  try {
    const response = await axios.put(
      `${import.meta.env.VITE_API_URL}/products/${props.id}`,
      formData.value,
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`, // Add Authorization header
        },
      }
    );
    successMessage.value = "Product updated successfully";
    product.value = response.data;
    // Emit event to notify parent component
    emit("product-updated", response.data);
  } catch (err) {
    if (err.response?.status === 403) {
      error.value = "Unauthorized: Only admins can update products";
    } else if (err.response?.status === 404) {
      error.value = "Product not found";
    } else if (err.response?.status === 500) {
      error.value = "Server error: Please check if product ID is valid";
    } else if (err.response?.data?.errors) {
      error.value = Object.values(err.response.data.errors).join(", ");
    } else {
      error.value = "Failed to update product";
    }
    console.error("Error updating product:", err);
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<style>
.product-update-form {
  padding: 0 50px;
}

.loading-state {
  text-align: center;
  padding: 40px;
}

.spinner {
  width: 40px;
  height: 40px;
  margin: 0 auto 20px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #007bff;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.form {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
}

label {
  font-weight: bold;
  color: #333;
}

input,
textarea {
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
}

input:focus,
textarea:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

button {
  padding: 10px 20px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.2s;
}

.cancel-button {
  background-color: #6c757d;
}

.cancel-button:hover {
  background-color: #5a6268;
}

.retry-button {
  margin-left: 12px;
  padding: 4px 12px;
  font-size: 14px;
}

button:hover:not(:disabled) {
  background-color: #0056b3;
}

button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.error-alert {
  padding: 12px;
  background-color: #fee;
  color: #c00;
  border-radius: 4px;
  margin-bottom: 16px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.success-alert {
  padding: 12px;
  background-color: #efe;
  color: #0a0;
  border-radius: 4px;
  margin-bottom: 16px;
}
</style>
