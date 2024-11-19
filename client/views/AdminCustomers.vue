<template>
  <div class="admin-users">
    <div v-if="loading" class="loading">Loading customers...</div>
    <div v-else-if="error" class="error">
      {{ error }}
    </div>

    <table v-else class="users-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(user, index) in paginatedUsers" :key="user.id">
          <td>{{ user.id }}</td>
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.role }}</td>
          <td>
            <button @click="editUser(user.id)">View</button>
            <button @click="deleteUser(user.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="successMessage" class="success-message">
      {{ successMessage }}
    </div>

    <div class="pagination">
      <button :disabled="currentPage === 1" @click="prevPage">Previous</button>
      <span>Page {{ currentPage }} of {{ totalPages }}</span>
      <button :disabled="currentPage === totalPages" @click="nextPage">
        Next
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";

// Local state
const users = ref([]);
const loading = ref(true);
const error = ref(null);
const successMessage = ref("");
const currentPage = ref(1);
const usersPerPage = 10; // Display 10 users per page

// Methods
const fetchUsers = async () => {
  try {
    loading.value = true;
    const response = await axios.get(`${import.meta.env.VITE_API_URL}/users`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      },
    });
    users.value = response.data;
  } catch (err) {
    error.value = err.response?.data?.message || "Failed to fetch user data";
  } finally {
    loading.value = false;
  }
};

const editUser = (id) => {
  // Navigate to edit user page
  console.log(`Editing user with ID: ${id}`);
};

const deleteUser = async (id) => {
  try {
    await axios.delete(`${import.meta.env.VITE_API_URL}/admin/users/${id}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      },
    });
    successMessage.value = "User deleted successfully!";
    setTimeout(() => {
      successMessage.value = "";
    }, 3000);
    fetchUsers(); // Refresh user list
  } catch (err) {
    error.value = err.response?.data?.message || "Failed to delete user";
    setTimeout(() => {
      error.value = null;
    }, 3000);
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};

// Computed properties
const totalPages = computed(() => {
  return Math.ceil(users.value.length / usersPerPage);
});

const paginatedUsers = computed(() => {
  const start = (currentPage.value - 1) * usersPerPage;
  const end = start + usersPerPage;
  return users.value.slice(start, end);
});

// Lifecycle
onMounted(() => {
  fetchUsers();
});
</script>

<style>
.admin-users {
  padding: 0 50px;
  margin-bottom: 20px;
}

h2 {
  margin-bottom: 20px;
}

.loading {
  text-align: center;
}

.error {
  color: var(--error-message);
  text-align: center;
}

.users-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.users-table th,
.users-table td {
  padding: 10px;
  text-align: left;
  border: 1px solid #ddd;
}

.users-table th {
  background-color: #f4f4f4;
}

button {
  padding: 5px 10px;
  margin-right: 10px;
  border: none;
  border-radius: 3px;
  background: var(--dark-tint);
  color: var(--background-color);
  cursor: pointer;
}

button:hover {
  background: var(--dark-color);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0);
}

button:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.6);
}

.success-message,
.error {
  padding: 1rem;
  margin-top: 20px;
  background-color: #dff0d8;
  color: #3c763d;
  border-radius: 6px;
  text-align: center;
}

.error {
  background-color: #f2dede;
  color: #a94442;
}

.pagination {
  margin-top: 20px;
  text-align: center;
}

.pagination button {
  padding: 5px 10px;
  margin: 0 5px;
  background: var(--dark-tint);
  color: var(--background-color);
  cursor: pointer;
}

.pagination button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}
</style>
