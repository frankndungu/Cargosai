<template>
  <div class="product-update-container">
    <!-- Loading State -->
    <div v-if="isLoading" class="loading-container">
      <div class="loading-spinner"></div>
    </div>

    <!-- Error Alert -->
    <div v-if="error" class="alert alert-error">
      <span>{{ error }}</span>
      <button @click="fetchProduct" class="btn btn-small">Retry</button>
    </div>

    <!-- Success Message -->
    <div v-if="successMessage" class="alert alert-success">
      {{ successMessage }}
    </div>

    <!-- Form -->
    <form v-if="product" @submit.prevent="updateProduct" class="update-form">
      <div class="form-grid">
        <!-- Basic Info Section -->
        <div class="form-section">
          <h3>Basic Information</h3>
          <div class="input-group">
            <label for="name">Product Name</label>
            <input
              id="name"
              v-model="formData.name"
              type="text"
              maxlength="255"
              required
              placeholder="Enter product name"
            />
          </div>

          <div class="input-group">
            <label for="price">Price ($)</label>
            <input
              id="price"
              v-model.number="formData.price"
              type="number"
              step="0.01"
              required
              placeholder="0.00"
            />
          </div>

          <div class="input-group">
            <label for="description">Description</label>
            <textarea
              id="description"
              v-model="formData.description"
              rows="4"
              placeholder="Enter product description"
            ></textarea>
          </div>

          <div class="input-group">
            <label for="stock">Stock</label>
            <input
              id="stock"
              v-model.number="formData.stock"
              type="number"
              min="0"
              required
              placeholder="Enter stock quantity"
            />
          </div>
        </div>

        <!-- Product Details Section -->
        <div class="form-section">
          <h3>Product Details</h3>
          <div class="input-group">
            <label for="dimensions">Dimensions</label>
            <input
              id="dimensions"
              v-model="formData.dimensions"
              type="text"
              placeholder="Length x Width x Height"
            />
          </div>

          <div class="input-group">
            <label for="weight">Weight (kg)</label>
            <input
              id="weight"
              v-model.number="formData.weight"
              type="number"
              step="0.01"
              placeholder="0.00"
            />
          </div>

          <div class="input-group">
            <label for="material">Material</label>
            <input
              id="material"
              v-model="formData.material"
              type="text"
              placeholder="Enter material type"
            />
          </div>
        </div>

        <!-- Vendor Information Section -->
        <div class="form-section">
          <h3>Vendor Information</h3>
          <div class="input-group">
            <label for="vendor_name">Vendor Name</label>
            <input
              id="vendor_name"
              v-model="formData.vendor_name"
              type="text"
              maxlength="255"
              required
              placeholder="Enter vendor name"
            />
          </div>

          <div class="input-group">
            <label for="vendor_email">Vendor Email</label>
            <input
              id="vendor_email"
              v-model="formData.vendor_email"
              type="email"
              maxlength="255"
              required
              placeholder="vendor@example.com"
            />
          </div>

          <div class="input-group">
            <label for="vendor_location">Vendor Location</label>
            <input
              id="vendor_location"
              v-model="formData.vendor_location"
              type="text"
              maxlength="255"
              required
              placeholder="Enter vendor location"
            />
          </div>
        </div>
      </div>

      <div class="form-actions">
        <button type="button" class="btn btn-secondary" @click="resetForm">
          Reset
        </button>
        <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
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

const fetchProduct = async () => {
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

watch(
  () => props.id,
  (newId, oldId) => {
    if (newId && newId !== oldId) {
      fetchProduct();
    }
  },
  { immediate: true }
);

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
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }
    );
    successMessage.value = "Product updated successfully";
    product.value = response.data;
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
.product-update-container {
  padding: 5px 50px;
}

.form-title {
  font-size: 24px;
  color: var(--dark-color);
  margin-bottom: 24px;
}

.loading-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 40px;
}

.loading-spinner {
  width: 40px;
  height: 40px;
  border: 3px solid #f3f3f3;
  border-top: 3px solid #ccc;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-top: 20px;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.alert {
  padding: 12px 16px;
  border-radius: 8px;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.alert-error {
  background-color: #fee2e2;
  color: #dc2626;
}

.alert-success {
  background-color: #dcfce7;
  color: #16a34a;
}

.update-form {
  background-color: #ffffff;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 24px;
  margin-bottom: 24px;
}

.form-section {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.form-section h3 {
  font-size: 18px;
  color: var(--dark-color);
  margin-bottom: 8px;
  padding-bottom: 8px;
  border-bottom: 2px solid #e5e7eb;
}

.input-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

label {
  font-size: 14px;
  font-weight: 500;
  color: var(--dark-color);
}

input,
textarea {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 14px;
  transition: all 0.2s ease;
}

input:hover,
textarea:hover {
  border-color: var(--border-color);
}

input:focus,
textarea:focus {
  outline: none;
  border-color: var(--dark-color);
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  margin-top: 24px;
  padding-top: 24px;
  border-top: 1px solid #ccc;
}

.btn {
  padding: 10px 20px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
  border: none;
}

.btn-small {
  padding: 6px 12px;
  font-size: 12px;
}

.btn-primary {
  background-color: var(--dark-tint);
  color: var(--background-color);
}

.btn-primary:hover:not(:disabled) {
  background-color: var(--dark-color);
}

.btn-secondary {
  background-color: var(--dark-color-lighter);
  color: var(--background-color);
}

.btn-secondary:hover {
  background-color: var(--primary-color);
}

.btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .product-update-container {
    padding: 16px;
  }

  .update-form {
    padding: 16px;
  }

  .form-grid {
    grid-template-columns: 1fr;
    gap: 20px;
  }

  .form-actions {
    flex-direction: column;
  }

  .btn {
    width: 100%;
  }

  .alert {
    flex-direction: column;
    gap: 8px;
    text-align: center;
  }
}

/* Small screen optimizations */
@media (max-width: 480px) {
  .form-title {
    font-size: 20px;
  }

  input,
  textarea {
    font-size: 16px; /* Prevents zoom on iOS */
  }
}
</style>
